<?php

require 'vendor/autoload.php';
require 'config.php';


/**
 * Routing
 */
$app = new Forge\App([

    '/'                 => 'My\Logic\Cloud::explore',

    '/::path/create'    => 'My\Logic\Cloud::create',
    '/::path/rename'    => 'My\Logic\Cloud::rename',
    '/::path/delete'    => 'My\Logic\Cloud::delete',
    '/::path/upload'    => 'My\Logic\Cloud::upload',

    '/:/create'         => 'My\Logic\Cloud::create',
    '/:/rename'         => 'My\Logic\Cloud::rename',
    '/:/delete'         => 'My\Logic\Cloud::delete',
    '/:/upload'         => 'My\Logic\Cloud::upload',

    '/::path'           => 'My\Logic\Cloud::explore',
    '/:'                => 'My\Logic\Cloud::explore',

    '/lost'             => 'My\Logic\Error::lost',
    '/login'            => 'My\Logic\User::login',

]);


/**
 * Error events
 */
$app->on(404, function() use($app) {
    go('/lost');
});

$app->on(403, function() use($app) {
    go('/login');
});


/**
 * Go !
 */
$app->handle();