<?php

namespace ClarkWinkelmann\LocalExtenders;

use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use Flarum\Frontend\Assets;
use Flarum\Frontend\Compiler\Source\SourceCollector;
use Illuminate\Contracts\Container\Container;

/**
 * Replace an admin component's view method with the given HTML.
 * The content will be injected using Mithril's m.trust() method.
 */
class ReplaceAdminComponentViewWithHtml implements ExtenderInterface
{
    private $components;

    public function __construct(array $components = [])
    {
        $this->components = $components;
    }

    /**
     * Replace an admin component's view method with the HTML from a given file
     * @param string $componentName Component name without the path or extension, example `MailPage`
     * @param string $fileName Absolute path to the file to read the HTML content from
     * @return ReplaceAdminComponentViewWithHtml
     */
    public function file(string $componentName, string $fileName)
    {
        $this->components[$componentName] = function () use ($fileName) {
            return file_get_contents($fileName);
        };

        return $this;
    }

    /**
     * Replace an admin component's view method with the HTML from a given file
     * @param string $componentName Component name without the path or extension, example `MailPage`
     * @param string $htmlContent HTML content to inject as a string
     * @return ReplaceAdminComponentViewWithHtml
     */
    public function string(string $componentName, string $htmlContent)
    {
        $this->components[$componentName] = function () use ($htmlContent) {
            return $htmlContent;
        };

        return $this;
    }

    public function extend(Container $container, Extension $extension = null)
    {
        $container->resolving('flarum.assets.admin', function (Assets $assets) {
            $assets->js(function (SourceCollector $sources) {
                $sources->addString(function () {
                    return "app.initializers.add('local-extenders/replace-component-view', () => {\n" .
                        implode("\n", array_map(function (callable $value, string $componentName) {
                            return
                                "  flarum.core.compat['extend'].override(flarum.core.compat['components/$componentName'].prototype, 'view', function() {" .
                                "    return m.trust(" . json_encode($value()) . ");" .
                                "  });";
                        }, $this->components, array_keys($this->components))) .
                        "\n}, -100);\n";
                });
            });
        });
    }
}
