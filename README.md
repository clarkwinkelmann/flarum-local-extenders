# Local extenders for Flarum

[![MIT license](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/clarkwinkelmann/flarum-local-extenders/blob/master/LICENSE.md) [![Latest Stable Version](https://img.shields.io/packagist/v/clarkwinkelmann/flarum-local-extenders.svg)](https://packagist.org/packages/clarkwinkelmann/flarum-local-extenders) [![Total Downloads](https://img.shields.io/packagist/dt/clarkwinkelmann/flarum-local-extenders.svg)](https://packagist.org/packages/clarkwinkelmann/flarum-local-extenders) [![Donate](https://img.shields.io/badge/paypal-donate-yellow.svg)](https://www.paypal.me/clarkwinkelmann)

This package provides useful extenders for your local Flarum extend.php

This is not an actual extension, and will not appear in your Flarum admin panel.

## Installation

    composer require clarkwinkelmann/flarum-local-extenders

## Configuration

The extenders can be used by adding them in the array of `extend.php` at the root of the Flarum install.

### Hide extension settings

Example:

    new ClarkWinkelmann\LocalExtenders\OverrideSettings([
        'fof-stopforumspam.ip' => '1',
        'fof-stopforumspam.email' => '0',
        'fof-stopforumspam.api_key' => 'abcdefg',
    ]),

### Override settings

Example:

    new ClarkWinkelmann\LocalExtenders\HideExtensionSettings([
        'fof-stopforumspam',
    ]),

## Links

- [GitHub](https://github.com/clarkwinkelmann/flarum-local-extenders)
- [Packagist](https://packagist.org/packages/clarkwinkelmann/flarum-local-extenders)
