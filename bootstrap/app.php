<?php

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/env.php';

if (getenv('DEBUG')) {
    require_once __DIR__ . '/whoops.php';
}

require_once __DIR__ . '/core.php';
