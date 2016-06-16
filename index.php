<?php

// Path to your desk/ folder
$deskPath = './desk';

// Desk default environment (DEV)
define('DESK_DEV_MODE', TRUE);

/* DO NOT EDIT BELOW THIS LINE */
$path = rtrim($deskPath, '/') . '/app/index.php';

if (!file_exists($path)) {
    if (extension_loaded('http_response_code')) {
        http_response_code(503);
    }

    exit('Could not locate desk/app/index.php file. Please ensure <strong>$deskPath</strong> is set correctly in ' . __FILE__);
}

require_once $path;