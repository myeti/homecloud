<?php

require 'vendor/autoload.php';


/**
 * Config
 */

define('HC_SEP', DIRECTORY_SEPARATOR);
define('HC_DIR', 'storage' . HC_SEP);
define('HC_ROOT', __DIR__ . HC_SEP . HC_DIR);
define('HC_USERNAME', 'Lapeluche');
define('HC_PASSWORD', '634f0628e15e156c1e2bb8149b0e5ca92bd8fc68');

if(!file_exists(HC_ROOT) and !mkdir(HC_ROOT)) {
    die('Cannot create "' . HC_ROOT . '" folder, please update chmod.');
}


/**
 * Routing
 */
$app = new Craft\Kernel\App([

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
    '/login'            => 'My\Logic\User::login'

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
$app->plug();