<?php

use ClarkWinkelmann\LocalExtenders;

// This file shows an example for each of the extenders
// It is meant as a way to quickly test the package

return [
    (new LocalExtenders\AlterExtensionListInAdmin())
        ->extension('flarum-approval', function ($extension) {
            $extension->title = 'Approval renamed';
            $extension->description = 'Description renamed';
            $extension->iconName = 'fas fa-tree';
            $extension->iconColor = '#000000';
            $extension->iconBackgroundColor = '#ff0000';

            $extension->hideSettings();
            $extension->hideVersion();
        })
        ->extension('flarum-emoji', function ($extension) {
            $extension->hide();
        })
    ,
    new LocalExtenders\FollowAfterStart(),
    (new LocalExtenders\FrontendWithoutModule('forum'))
        ->css(__DIR__ . '/forum.less')
        ->js(__DIR__ . '/forum.js'),
    new LocalExtenders\HideExtensionVersionInAdmin(),
    new LocalExtenders\HideFlarumVersionInAdmin(),
    new LocalExtenders\HideSystemInfoInAdmin(),
    new LocalExtenders\OverrideSettings([
        'welcome_title' => 'Overridden title',
    ], [
        'welcome_message',
    ]),
    new LocalExtenders\RememberMeByDefault(),
    (new LocalExtenders\ReplaceAdminComponentViewWithHtml())
        ->string('MailPage', '<p>overridden MailPage</p>')
        ->file('AppearancePage', __DIR__ . '/AppearancePage.html'),
];
