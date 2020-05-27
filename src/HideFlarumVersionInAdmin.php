<?php

namespace ClarkWinkelmann\LocalExtenders;

use Flarum\Api\Event\Serializing;
use Flarum\Api\Serializer\ForumSerializer;
use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use Flarum\Settings\Event\Deserializing;
use Illuminate\Contracts\Container\Container;

/**
 * Hides Flarum version from the payloads available via the admin panel.
 * This is not a security measure. There are still many ways to guess the Flarum version, even without admin access.
 */
class HideFlarumVersionInAdmin implements ExtenderInterface
{
    public function extend(Container $container, Extension $extension = null)
    {
        // The "version" value in the settings is not the one displayed in the admin, but we want to hide it as well
        $container['events']->listen(Deserializing::class, function (Deserializing $event) {
            unset($event->settings['version']);
        });

        $container['events']->listen(Serializing::class, function (Serializing $event) {
            if ($event->isSerializer(ForumSerializer::class) && $event->actor->can('administrate')) {
                $event->attributes['version'] = null;
            }
        });
    }
}
