<?php

namespace ClarkWinkelmann\LocalExtenders;

/**
 * Defines a modification to an extension's look in the admin.
 * Do not create this object yourself. It's passed as the argument to the callback of AlterExtensionListInAdmin::extension().
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

    public function hide()
    {
        $this->hide = true;

        return $this;
    }

    public function hideVersion()
    {
        $this->hideVersion = true;

        return $this;
    }

    public function hideSettings()
    {
        $this->hideSettings = true;

        return $this;
    }

    public function title(string $title)
    {
        $this->title = $title;

        return $this;
    }

    public function description(string $description)
    {
        $this->description = $description;

        return $this;
    }

    public function iconName(string $name)
    {
        $this->iconName = $name;

        return $this;
    }

    public function iconColor(string $color)
    {
        $this->iconColor = $color;

        return $this;
    }

    public function iconBackgroundColor(string $color)
    {
        $this->iconBackgroundColor = $color;

        return $this;
    }

    public function iconImage(string $path)
    {
        $this->iconImage = $path;

        return $this;
    }
}
