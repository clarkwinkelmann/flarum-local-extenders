<?php

namespace ClarkWinkelmann\LocalExtenders;

use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use Flarum\Frontend\Document;
use Flarum\Frontend\Frontend;
use Illuminate\Contracts\Container\Container;

/**
 * Removes PHP and MySQL versions from the admin's page payload.
 */
class HideSystemInfoInAdmin implements ExtenderInterface
{
    public function extend(Container $container, Extension $extension = null)
    {
        $container->resolving('flarum.frontend.admin', function (Frontend $frontend) {
            $frontend->content(function (Document $document) {
                $document->payload['phpVersion'] = null;
                $document->payload['mysqlVersion'] = null;
            });
        });
    }
}
