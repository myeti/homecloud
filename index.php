<?php

require 'vendor/autoload.php';


/**
 * Config
 */

// directory config
define('HC_SEP', DIRECTORY_SEPARATOR);
define('HC_DIR', 'storage' . HC_SEP);
define('HC_ROOT', __DIR__ . HC_SEP . HC_DIR);

// imap config
define('HC_IMAP', true);
define('HC_IMAP_DIR', 'Imported' . HC_SEP);
define('HC_IMAP_HOST', 'pop3.yourdomain.com:993/ssl/novalidate-cert');
define('HC_IMAP_USERNAME', 'receiver@yourdomain.com');
define('HC_IMAP_PASSWORD', 'yourpassword');

// user config
define('HC_USERNAME', 'Babor');
define('HC_PASSWORD', '3f6f6d7c89d3c8b71750424d1ffc3c481ac351c5'); // Lelefan

if(!file_exists(HC_ROOT) and !mkdir(HC_ROOT)) {
    die('Cannot create "' . HC_ROOT . '" folder, please update chmod.');
}


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

    '/import'            => 'My\Logic\Cloud::import'

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