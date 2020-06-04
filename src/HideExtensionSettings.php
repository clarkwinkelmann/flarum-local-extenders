<?php

namespace ClarkWinkelmann\LocalExtenders;

use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use Flarum\Frontend\Assets;
use Flarum\Frontend\Compiler\Source\SourceCollector;
use Illuminate\Contracts\Container\Container;

/**
 * Hides an extension's settings modal.
 * This does not prevent seeing or editing the settings present in the modal.
 * It will hide the "Settings" button from the 3-dot menu on the Extensions page for the given list of extensions.
 * If the extension provides another way to access the modal or page, the user will still be able to access it.
 * @deprecated Use option hideSettings in AlterExtensionListInAdmin instead
 */
class HideExtensionSettings implements ExtenderInterface
{
    protected $extensionIds;

    public function __construct(array $extensionIds = [])
    {
        $this->extensionIds = $extensionIds;
    }

    public function extend(Container $container, Extension $extension = null)
    {
        $container->resolving('flarum.assets.admin', function (Assets $assets) {
            $assets->js(function (SourceCollector $sources) {
                $sources->addString(function () {
                    return "app.initializers.add('local-extenders/hide-extension-settings', () => {\n" .
                        implode("\n", array_map(function ($extensionId) {
                            return "  delete app.extensionSettings[" . json_encode($extensionId) . "]";
                        }, $this->extensionIds)) .
                        "\n}, -100);\n";
                });
            });
        });
    }
}
