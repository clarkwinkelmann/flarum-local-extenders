<?php

namespace ClarkWinkelmann\LocalExtenders;

use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use Flarum\Frontend\Assets;
use Flarum\Frontend\Compiler\Source\SourceCollector;
use Illuminate\Contracts\Container\Container;

/**
 * Similar to Flarum's Frontend extender for js & css, but with a few differences:
 * - Doesn't handle javascript files as modules, allowing custom code that isn't designed for a module loader
 * - Allow adding multiple javascript files with a single extender
 */
class FrontendWithoutModule implements ExtenderInterface
{
    private $frontend;

    private $css = [];
    private $js = [];

    public function __construct(string $frontend)
    {
        $this->frontend = $frontend;
    }

    public function css(string $path): self
    {
        $this->css[] = $path;

        return $this;
    }

    public function js(string $path): self
    {
        $this->js[] = $path;

        return $this;
    }

    public function extend(Container $container, Extension $extension = null)
    {
        $this->registerAssets($container);
    }

    private function registerAssets(Container $container)
    {
        $container->resolving('flarum.assets.' . $this->frontend, function (Assets $assets) {
            if ($this->js) {
                $assets->js(function (SourceCollector $sources) {
                    foreach ($this->js as $path) {
                        $sources->addFile($path);
                    }
                });
            }

            if ($this->css) {
                $assets->css(function (SourceCollector $sources) {
                    foreach ($this->css as $path) {
                        $sources->addFile($path);
                    }
                });
            }
        });
    }
}
