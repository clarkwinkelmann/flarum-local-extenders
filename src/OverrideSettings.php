<?php

namespace ClarkWinkelmann\LocalExtenders;

use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Container\Container;

/**
 * Allows to hard-code key-value pairs as the output of ::get() and hide values from the output of ::all()
 */
class OverrideSettings implements ExtenderInterface
{
    private $overrides;
    private $hidden;

    public function __construct(array $overrides = [], array $hidden = [])
    {
        $this->overrides = $overrides;
        $this->hidden = $hidden;
    }

    /**
     * Set some key-value pairs to be returned by SettingsRepositoryInterface::get()
     * Even if another value is set via the admin panel, the values defined via this extender will take priority
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function set(string $key, $value): self
    {
        $this->overrides[$key] = $value;

        return $this;
    }

    /**
     * Hide some keys from the SettingsRepositoryInterface::all() payload
     * @param string|array $keys
     * @return OverrideSettings
     */
    public function hide($keys)
    {
        if (is_array($keys)) {
            $this->hidden = array_merge($this->hidden, $keys);
        } else {
            $this->hidden[] = $keys;
        }

        return $this;
    }

    public function extend(Container $container, Extension $extension = null)
    {
        $container->extend(SettingsRepositoryInterface::class, function ($settings) {
            return new OverrideSettingsRepository($settings, $this->overrides, $this->hidden);
        });
    }
}
