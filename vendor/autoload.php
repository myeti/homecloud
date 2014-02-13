<?php

/**
 * Setup autoloader
 */

require __DIR__ . '/Craft/Reflect/ClassLoader.php';

$loader = new Craft\Reflect\ClassLoader();
$loader->register();
$loader->vendors([
    'Craft' => __DIR__ . '/Craft',
    'Psr'   => __DIR__ . '/Psr',
    'My'    => dirname($_SERVER['SCRIPT_FILENAME'])
]);


/**
 * Setup dev tools
 */

$logger = new Craft\Debug\Logger();
$tracker = new Craft\Debug\Tracker();
$tracker->start('craft.app');


/**
 * Setup session
 */

ini_set('session.use_trans_sid', 0);
ini_set('session.use_only_cookies', 1);
ini_set("session.cookie_lifetime", 604800);
ini_set("session.gc_maxlifetime", 604800);
session_set_cookie_params(604800);
session_start();


/**
 * Load helpers
 */

require __DIR__ . '/helpers.php';