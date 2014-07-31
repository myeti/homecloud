<?php

/**
 * Directory config
 */
define('HC_SEP', DIRECTORY_SEPARATOR);
define('HC_DIR', 'storage' . HC_SEP);
define('HC_ROOT', __DIR__ . HC_SEP . HC_DIR);

if(!file_exists(HC_ROOT) and !mkdir(HC_ROOT)) {
    die('Cannot create "' . HC_ROOT . '" folder, please update chmod.');
}


/**
 * Imap config
 */
define('HC_IMAP', true);
define('HC_IMAP_DIR', 'Imported' . HC_SEP);
define('HC_IMAP_HOST', 'pop3.yourdomain.com:993/ssl/novalidate-cert');
define('HC_IMAP_USERNAME', 'receiver@yourdomain.com');
define('HC_IMAP_PASSWORD', 'yourpassword');


/**
 * User config
 */
define('HC_USERNAME', 'Babor');
define('HC_PASSWORD', '3f6f6d7c89d3c8b71750424d1ffc3c481ac351c5'); // Lelefan