<?php

require 'vendor/autoload.php';

\Craft\Env\Auth::login(1);


/**
 * Config
 */

define('HC_SEP', DIRECTORY_SEPARATOR);
define('HC_ROOT', __DIR__ . HC_SEP . 'storage' . HC_SEP);
define('HC_USERNAME', 'Babor');
define('HC_PASSWORD', '4a87f9df5abef2e9ba38f603f37b3fad52787fa4');

if(!file_exists(HC_ROOT) and !mkdir(HC_ROOT)) {
    die('Cannot create "' . HC_ROOT . '" folder, please update chmod.');
}


/**
 * Routing
 */
$app = new Craft\Kernel\App([

    '/'         => 'My\Logic\Cloud::index',
    '/:path'    => 'My\Logic\Cloud::index',

    '/_404'     => 'My\Logic\Error::lost',
    '/_403'     => 'My\Logic\Error::login'

]);


/**
 * Error events
 */
$app->on(404, function() use($app) {
    $app->plug('/_404');
});

$app->on(403, function() use($app) {
    $app->plug('/_403');
});


/**
 * Go !
 */
$app->plug();