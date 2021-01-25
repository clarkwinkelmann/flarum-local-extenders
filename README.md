# Local extenders for Flarum

[![MIT license](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/clarkwinkelmann/flarum-local-extenders/blob/master/LICENSE.md) [![Latest Stable Version](https://img.shields.io/packagist/v/clarkwinkelmann/flarum-local-extenders.svg)](https://packagist.org/packages/clarkwinkelmann/flarum-local-extenders) [![Total Downloads](https://img.shields.io/packagist/dt/clarkwinkelmann/flarum-local-extenders.svg)](https://packagist.org/packages/clarkwinkelmann/flarum-local-extenders) [![Donate](https://img.shields.io/badge/paypal-donate-yellow.svg)](https://www.paypal.me/clarkwinkelmann)

This package provides useful extenders for your local Flarum extend.php

This is not an actual extension, and will not appear in your Flarum admin panel.

## Installation

    composer require clarkwinkelmann/flarum-local-extenders

## Configuration

The extenders can be used by adding them in the array of `extend.php` at the root of the Flarum install.

Below you will find a summary of the features along with examples.
See the PHPDoc blocks in the source code for the full documentation and warnings.

### Alter extension list in admin

Lets you customize how the extension list is rendered in the admin panel.

"Hide extension" and "Hide version" effectively hide the data, but it can still be guessed when the extension is enabled.

All other options are just cosmetic and just visually change the look without hiding the original values or features.

It's always possible to enable/disable an extension or edit its settings via the API.

Example:

```php
(new ClarkWinkelmann\LocalExtenders\AlterExtensionListInAdmin())
    ->extension('flarum-markdown', function ($extension) {
        // To Hide the extension completely
        $extension->hide();
        
        // To hide select information or buttons
        $extension->hideVersion();
        $extension->hideSettings();
        
        // To change the look
        $extension->title = 'New title';
        $extension->description = 'New description';
        $extension->iconName = 'fas fa-tree';
        $extension->iconColor = '#aa0000';
        $extension->iconBackgroundColor = '#aa0000';
        $extension->iconImage = __DIR__.'/path/to/image.png'; // SVG/PNG/JPG
        
        // You can also chain calls via the methods
        $extension
            ->title('New title')
            ->iconName('fas fa-tree');
    }),
```

### Follow after start

Adds a new user preference that controls a new feature "Follow discussions that I start".

Works exactly like the "Follow discussions that I reply to" feature bundled with Subscriptions, but for the discussion start.

Example:

```php
new ClarkWinkelmann\LocalExtenders\FollowAfterStart(),
```

It's enabled by default. To set it disabled by default, pass a parameter to the constructor:

```php
new ClarkWinkelmann\LocalExtenders\FollowAfterStart(false),
```

### Frontend without modules

Similar to Flarum's Frontend extender for js & css, but with a few differences:

- Doesn't handle javascript files as modules, allowing custom code that isn't designed for a module loader
- Allow adding multiple javascript files with a single extender

Example:

```php
(new ClarkWinkelmann\LocalExtenders\FrontendWithoutModule('admin'))
    ->js(__DIR__.'/local/one-file.js')
    ->js(__DIR__.'/local/one-other-file.js')
    ->css(__DIR__.'/local/you-can-also-import-css-but-its-identical-to-the-core-extender.less'),
```

### Hide extension version in admin

Removes the extension version of all extensions from the admin panel's data payload.

Example:

```php
new ClarkWinkelmann\LocalExtenders\HideExtensionVersionInAdmin(),
```

### Hide Flarum version in admin

Removes the Flarum version from the admin panel's data payload.

Example:

```php
new ClarkWinkelmann\LocalExtenders\HideExtensionVersionInAdmin(),
```

### Hide system info in admin

Removes the PHP and MySQL version from the admin panel's data payload.

Example:

```php
new ClarkWinkelmann\LocalExtenders\HideSystemInfoInAdmin(),
```

### Override settings

Set some key-value pairs to be returned by SettingsRepositoryInterface::get().
Even if another value is set via the admin panel, the values defined via this extender will take priority.

Example:

```php
(new ClarkWinkelmann\LocalExtenders\OverrideSettings())
    ->set('mail_driver', 'log')
    ->set('forum_title', 'Hello'),
```

Hide some keys from the SettingsRepositoryInterface::all() payload.
This only hides values coming from the database as values overridden via this extender are never returned by ::all() anyway.

Example:

```php
(new ClarkWinkelmann\LocalExtenders\OverrideSettings())
    ->hide('mail_driver'),
```

The overridden values can also be set via an associative array passed to the constructor:

```php
new ClarkWinkelmann\LocalExtenders\OverrideSettings([
    'fof-stopforumspam.ip' => '1',
    'fof-stopforumspam.email' => '0',
    'fof-stopforumspam.api_key' => 'abcdefg',
]),
```

### Remember Me by default

Checks the "remember me" checkbox of the LogIn modal when it opens.

Example:

```php
new ClarkWinkelmann\LocalExtenders\RememberMeByDefault(),
```

### Replace admin component view with HTML

Replace an admin component's view method with the given HTML.

Example:

```php
(new ClarkWinkelmann\LocalExtenders\ReplaceAdminComponentViewWithHtml())
    ->string('MailPage', '<p>We have already configured email for you</p>'),
```

```php
(new ClarkWinkelmann\LocalExtenders\ReplaceAdminComponentViewWithHtml())
    ->file('MailPage', __DIR__.'/local/mail.html'),
```

## Links

- [GitHub](https://github.com/clarkwinkelmann/flarum-local-extenders)
- [Packagist](https://packagist.org/packages/clarkwinkelmann/flarum-local-extenders)
