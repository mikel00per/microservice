<?php

date_default_timezone_set('UTC');

$settings = [];

# Paths
$settings['root'] = dirname(__DIR__);
$settings['config'] = $settings['root'] . '/config/';
$settings['tmp'] = $settings['root'] . '/tmp/';
$settings['cache'] = $settings['root'] . '/tmp/cache/';
$settings['src'] = $settings['root'] . '/src/';
$settings['tests'] = $settings['root'] . '/tests/';

# Microservice
$settings['environment'] = (string) getenv('ENVIRONMENT');
$settings['microservice_name'] = (string) getenv('MICROSERVICE_NAME');
$settings['microservice_version'] = (string) getenv('MICROSERVICE_VERSION');

// Logger settings
$settings['logger'] = [
    'name' => (string) getenv('LOGGER_NAME'),
    'path' => $settings['tmp'] . ((string) getenv('LOGGER_PATH')),
    'filename' => 'microservice.log',
    'level' => (int) getenv('LOGGER_LEVEL'),
    'file_permission' => 0775
];

$settings['error_middleware'] = [
    'display_details' => (bool) getenv('DISPLAY_ERROR_DETAILS'), // Should be set to false in production
    'log_errors' => (bool) getenv('LOGS_ERRORS'), // Should be set to false for the test environment
    'log_details' => (bool) getenv('LOG_ERROR_DETAILS') // Display error details in error log
];

$settings['container'] = [
    'definitions' => $settings['config'] . ((string) getenv('PATH_CONTAINER_DEFINITION')),
    'cache' => [
        'enabled' => ((bool) getenv('COMPILE_CONTAINER')),
        'path' => $settings['cache'] . ((string) getenv('PATH_CONTAINER_COMPILATION'))
    ]
];

$settings['middlewares']['definition'] = $settings['config'] . ((string) getenv('PATH_MIDDLEWARES_DEFINITION'));

$settings['router']['cache']['enabled'] = ((bool) getenv('COMPILE_ROUTES'));
$settings['router'] = [
    'definition' =>  $settings['config'] . ((string) getenv('PATH_ROUTES_DEFINITION')),
    'cache' => [
        'enabled' => $settings['router']['cache']['enabled'],
        'path' => !$settings['router']['cache']['enabled'] ?
            null :
            $settings['cache'] . ((string) getenv('PATH_ROUTES_CACHE'))
    ],
];

return $settings;
