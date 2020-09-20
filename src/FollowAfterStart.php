<?php

namespace ClarkWinkelmann\LocalExtenders;

use Flarum\Discussion\Event\Started;
use Flarum\Event\ConfigureUserPreferences;
use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use Flarum\Frontend\Assets;
use Flarum\Frontend\Compiler\Source\SourceCollector;
use Flarum\Locale\LocaleManager;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\Translation\Loader\ArrayLoader;

/**
 * Adds a new user preference that controls a new feature "Follow discussions that I start"
 * Works exactly like the "Follow discussions that I reply to" feature bundled with Subscriptions, but for the discussion start
 */
class FollowAfterStart implements ExtenderInterface
{
    protected $defaultPreferenceValue;

    public function __construct($defaultPreferenceValue = true)
    {
        $this->defaultPreferenceValue = $defaultPreferenceValue;
    }

    public function extend(Container $container, Extension $extension = null)
    {
        $container['events']->listen(Started::class, [$this, 'started']);

        $container['events']->listen(ConfigureUserPreferences::class, function (ConfigureUserPreferences $event) {
            $event->add('followAfterStart', 'boolval', $this->defaultPreferenceValue);
        });

        $container->resolving('flarum.assets.forum', function (Assets $assets) {
            $assets->js(function (SourceCollector $sources) {
                $sources->addString(function () {
                    return "app.initializers.add('local-extenders/follow-after-start', function () {" .
                        "  flarum.core.compat.extend.extend(flarum.core.compat['components/SettingsPage'].prototype, 'notificationsItems', function (items) {" .
                        "    items.add('followAfterStart', flarum.core.compat['components/Switch'].component({state: this.user.preferences().followAfterStart, onchange: this.preferenceSaver('followAfterStart')}, app.translator.trans('local-extenders.forum.followAfterStart')));" .
                        "  });" .
                        "}, -100);";
                });
            });
        });

        $container->resolving(
            LocaleManager::class,
            function (LocaleManager $locales) {
                $locales->getTranslator()->addLoader('array', new ArrayLoader());
                $locales->getTranslator()->addResource('array', [
                    'local-extenders.forum.followAfterStart' => 'Automatically follow discussions that I start',
                ], 'en');
            }
        );
    }

    public function started(Started $event)
    {
        $actor = $event->actor;

        if ($actor && $actor->exists && $actor->getPreference('followAfterStart')) {
            $state = $event->discussion->stateFor($actor);

            $state->subscription = 'follow';
            $state->save();
        }
    }
}
