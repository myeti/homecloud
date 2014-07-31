<?php

/**
 * Setup AutoLoader
 */

require __DIR__ . '/Craft/Reflect/ClassLoaderInterface.php';
require __DIR__ . '/Craft/Reflect/ClassLoader.php';
require __DIR__ . '/Forge/Loader.php';

Forge\Loader::set(
    new Craft\Reflect\ClassLoader(true)
);

Forge\Loader::add('Craft'   , __DIR__ . '/Craft');
Forge\Loader::add('Forge'   , __DIR__ . '/Forge');
Forge\Loader::add('Psr'     , __DIR__ . '/Psr');
Forge\Loader::add('My'      , dirname($_SERVER['SCRIPT_FILENAME']));


/**
 * Start logger
 */

Forge\Logger::info('Hello :)');


/**
 * Load helpers
 */

require __DIR__ . '/Craft/helpers.php';
require __DIR__ . '/Forge/helpers.php';