<?php

namespace ClarkWinkelmann\LocalExtenders;

use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Container\Container;

/**
 * Forces a value for a given setting key.
 * Even if another value is set via the admin panel, the values defined via this extender will take priority.
 */
class OverrideSettings implements ExtenderInterface
{
    protected $overrides;

    public function __construct(array $overrides = [])
    {
        $this->overrides = $overrides;
    }

    public function set(string $key, $value)
    {
        $this->overrides[$key] = $value;
    }

    public function extend(Container $container, Extension $extension = null)
    {
        $container->extend(SettingsRepositoryInterface::class, function ($settings) {
            return new OverrideSettingsRepository($settings, $this->overrides);
        });
    }
}
