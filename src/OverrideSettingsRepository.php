<?php

namespace ClarkWinkelmann\LocalExtenders;

use Flarum\Settings\SettingsRepositoryInterface;

/**
 * Same as Flarum's OverrideSettingsRepository but does not override all() to prevent exposing the value to the admin panel
 * Also calls $inner->get($key) instead of Arr::get($inner->all(), $key)
 */
class OverrideSettingsRepository implements SettingsRepositoryInterface
{
    protected $inner;

    protected $overrides = [];

    public function __construct(SettingsRepositoryInterface $inner, array $overrides)
    {
        $this->inner = $inner;
        $this->overrides = $overrides;
    }

    public function all(): array
    {
        return $this->inner->all();
    }

    public function get($key, $default = null)
    {
        if (array_key_exists($key, $this->overrides)) {
            return $this->overrides[$key];
        }

        return $this->inner->get($key, $default);
    }

    public function set($key, $value)
    {
        $this->inner->set($key, $value);
    }

    public function delete($key)
    {
        $this->inner->delete($key);
    }
}
