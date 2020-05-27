<?php

namespace ClarkWinkelmann\LocalExtenders;

use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Support\Arr;

/**
 * Proxy to Flarum's original OverrideSettingsRepository but allows overriding values in get() and hiding values in all()
 * Also calls $inner->get($key) instead of Arr::get($inner->all(), $key)
 * @internal Do not use this class directly. Only the OverrideSettings extender is part of this package's public API
 */
class OverrideSettingsRepository implements SettingsRepositoryInterface
{
    private $inner;

    private $overrides = [];
    private $hidden = [];

    public function __construct(SettingsRepositoryInterface $inner, array $overrides, array $hidden)
    {
        $this->inner = $inner;
        $this->overrides = $overrides;
        $this->hidden = $hidden;
    }

    public function all(): array
    {
        return Arr::except($this->inner->all(), $this->hidden);
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
