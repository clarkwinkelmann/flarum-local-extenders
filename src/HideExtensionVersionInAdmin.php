<?php

namespace ClarkWinkelmann\LocalExtenders;

use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use Flarum\Frontend\Document;
use Flarum\Frontend\Frontend;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Hides multiple attributes from the extensions payload of the admin panel that would identify the version.
 * This is not a security measure. There are still ways to guess an extension's version, even without admin access.
 */
class HideExtensionVersionInAdmin implements ExtenderInterface
{
    public function extend(Container $container, Extension $extension = null)
    {
        $container->resolving('flarum.frontend.admin', function (Frontend $frontend) {
            $frontend->content(function (Document $document, ServerRequestInterface $request) {
                foreach (Arr::get($document->payload, 'extensions', []) as $key => $attributes) {
                    $document->payload['extensions'][$key]['version'] = null;
                    $document->payload['extensions'][$key]['version_normalized'] = null;
                    $document->payload['extensions'][$key]['source'] = null;
                    $document->payload['extensions'][$key]['dist'] = null;
                    $document->payload['extensions'][$key]['require'] = [];
                    $document->payload['extensions'][$key]['time'] = null;
                }
            });
        });
    }
}
