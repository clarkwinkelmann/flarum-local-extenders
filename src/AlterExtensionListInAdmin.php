<?php

namespace ClarkWinkelmann\LocalExtenders;

use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use Flarum\Frontend\Assets;
use Flarum\Frontend\Compiler\Source\SourceCollector;
use Flarum\Frontend\Document;
use Flarum\Frontend\Frontend;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Changes the way an extension is displayed on the Extensions page of the admin panel.
 * Hiding an extension or extension version will effectively remove that information from the extension list,
 * however the information could still be guessed.
 * Changing an extension name or icon won't prevent finding out the original name or icon.
 * Enabling/disabling extensions or editing settings is still possible via the API in any case.
 */
class AlterExtensionListInAdmin implements ExtenderInterface
{
    private $extensions = [];

    public function extension(string $extensionId, callable $callback): self
    {
        $altering = Arr::get($this->extensions, $extensionId) ?? new ExtensionListAltering();

        $callback($altering);

        $this->extensions[$extensionId] = $altering;

        return $this;
    }

    public function extend(Container $container, Extension $extension = null)
    {
        $container->resolving('flarum.frontend.admin', function (Frontend $frontend) {
            $frontend->content(function (Document $document, ServerRequestInterface $request) {
                if (!$document->payload['extensions']) {
                    return;
                }

                foreach ($document->payload['extensions'] as $key => &$attributes) {
                    /**
                     * @var $altering ExtensionListAltering
                     */
                    $altering = Arr::get($this->extensions, $key);

                    if (!$altering) {
                        continue;
                    }

                    if ($altering->hide) {
                        unset($document->payload['extensions'][$key]);
                        continue;
                    }

                    if ($altering->title) {
                        Arr::set($attributes, 'extra.flarum-extension.title', $altering->title);
                    }

                    if ($altering->description) {
                        Arr::set($attributes, 'description', $altering->description);
                    }

                    if ($altering->iconName) {
                        Arr::set($attributes, 'icon.name', $altering->iconName);
                    }

                    if ($altering->iconColor) {
                        Arr::set($attributes, 'icon.color', $altering->iconColor);
                    }

                    if ($altering->iconBackgroundColor) {
                        Arr::set($attributes, 'icon.backgroundColor', $altering->iconBackgroundColor);
                    }

                    // Based on the code from Extension::getIcon
                    if ($altering->iconImage && file_exists($altering->iconImage)) {
                        $extension = pathinfo($altering->iconImage, PATHINFO_EXTENSION);
                        if (!array_key_exists($extension, Extension::LOGO_MIMETYPES)) {
                            throw new \RuntimeException('Invalid image type');
                        }

                        $mimetype = Extension::LOGO_MIMETYPES[$extension];
                        $data = base64_encode(file_get_contents($altering->iconImage));

                        Arr::set($attributes, 'icon.backgroundImage', "url('data:$mimetype;base64,$data')");
                    }

                    if ($altering->hideVersion) {
                        Arr::set($attributes, 'version', null);
                        Arr::set($attributes, 'version_normalized', null);
                        Arr::set($attributes, 'source', null);
                        Arr::set($attributes, 'dist', null);
                        Arr::set($attributes, 'require', []);
                        Arr::set($attributes, 'time', null);
                    }
                }
            });
        });

        $hideSettingsForExtensionIds = array_keys(array_filter($this->extensions, function (ExtensionListAltering $altering) {
            return $altering->hideSettings === true;
        }));

        if (count($hideSettingsForExtensionIds)) {
            $container->resolving('flarum.assets.admin', function (Assets $assets) use ($hideSettingsForExtensionIds) {
                $assets->js(function (SourceCollector $sources) use ($hideSettingsForExtensionIds) {
                    $sources->addString(function () use ($hideSettingsForExtensionIds): string {
                        return "app.initializers.add('local-extenders/alter-extension-list-hide-settings', function () {\n" .
                            implode("\n", array_map(function ($extensionId) {
                                $safeExtensionId = json_encode($extensionId);
                                return "  app.extensionData.for($safeExtensionId);\n" . // Just to create the key in case it's not there already
                                    "  delete app.extensionData.state.data[$safeExtensionId].settings"; // Cannot just have an empty list, the key must be removed so the save button doesn't appear
                            }, $hideSettingsForExtensionIds)) .
                            "\n}, -100);\n";
                    });
                });
            });
        }
    }
}
