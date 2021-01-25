<?php

namespace ClarkWinkelmann\LocalExtenders;

use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use Flarum\Frontend\Assets;
use Flarum\Frontend\Compiler\Source\SourceCollector;
use Illuminate\Contracts\Container\Container;

/**
 * Enables the "remember me" checkbox of the LogIn modal by default
 * When this feature is used, we stop reading `this.props.remember` completely
 * This shouldn't have any impact seeing that prop is never used by Flarum itself
 */
class RememberMeByDefault implements ExtenderInterface
{
    public function extend(Container $container, Extension $extension = null)
    {
        $container->resolving('flarum.assets.forum', function (Assets $assets) {
            $assets->js(function (SourceCollector $sources) {
                $sources->addString(function () {
                    return "app.initializers.add('local-extenders/remember-me', () => {\n" .
                        "  flarum.core.compat['extend'].extend(flarum.core.compat['components/LogInModal'].prototype, 'oninit', function () {\n" .
                        "    this.remember(true);\n" .
                        "  });\n" .
                        "});\n";
                });
            });
        });
    }
}
