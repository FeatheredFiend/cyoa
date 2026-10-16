<?php

use App\Kernel;

$_SERVER['APP_RUNTIME_OPTIONS'] = require dirname(__DIR__).'/config/runtime.php';
require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
