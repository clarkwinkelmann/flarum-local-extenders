<?php

namespace ClarkWinkelmann\LocalExtenders;

/**
 * Defines a modification to an extension's look in the admin.
 * @internal Do not create this object yourself. It's passed as the argument to the callback of AlterExtensionListInAdmin::extension()
 */
class ExtensionListAltering
{
    public $hide;
    public $hideVersion;
    public $hideSettings;
    public $title;
    public $description;
    public $iconName;
    public $iconColor;
    public $iconBackgroundColor;
    public $iconImage;

    public function hide(): self
    {
        $this->hide = true;

        return $this;
    }

    public function hideVersion(): self
    {
        $this->hideVersion = true;

        return $this;
    }

    public function hideSettings(): self
    {
        $this->hideSettings = true;

        return $this;
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function iconName(string $name): self
    {
        $this->iconName = $name;

        return $this;
    }

    public function iconColor(string $color): self
    {
        $this->iconColor = $color;

        return $this;
    }

    public function iconBackgroundColor(string $color): self
    {
        $this->iconBackgroundColor = $color;

        return $this;
    }

    public function iconImage(string $path): self
    {
        $this->iconImage = $path;

        return $this;
    }
}
