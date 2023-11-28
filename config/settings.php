<?php

date_default_timezone_set('UTC');


$settings = [];

# Paths
$rootPath = dirname(__DIR__);
$settings['rootPath'] = $rootPath;
$settings['rootNamespace'] = "Shared\\Infrastructure\\Slim\\";
$settings['configPath'] = $rootPath . '/config/';
$settings['tmpPath'] = $rootPath . '/tmp/';
$settings['cachePath'] = $rootPath . '/tmp/cache/';

# Microservice
$settings['environment'] = (string) getenv('ENVIRONMENT');
$settings['microservice_name'] = (string) getenv('MICROSERVICE_NAME');
$settings['microservice_version'] = (string) getenv('MICROSERVICE_VERSION');

# Container
$settings['di']['container']['path'] = "$rootPath/" . ((string) getenv('PATH_CONTAINER_DEFINITIONS'));
$settings['di']['auto_wires']['path'] = "$rootPath/" . ((string) getenv('PATH_AUTO_WIRES'));
$settings['di']['container_adders']['path'] = "$rootPath/" . ((string) getenv('PATH_CONTAINER_ADDERS_DEFINITION'));
$settings['di']['auto_wires']['enabled'] = ((bool) getenv('ENABLE_AUTO_WIRES'));
$settings['di']['attributes']['enabled'] = ((bool) getenv('ENABLE_ATTRIBUTES'));
$settings['di']['cache']['container']['path'] = "$rootPath/" . ((string) getenv('PATH_COMPILED_CONTAINER'));
$settings['di']['cache']['resolver']['path'] = "$rootPath/" . ((string) getenv('PATH_COMPILED_RESOLVER'));

// Logger settings
$settings['logger']['name'] = (string) getenv('LOGGER_NAME');
$settings['logger']['path'] = "$rootPath/" . ((string) getenv('LOGGER_PATH'));
$settings['logger']['filename'] = 'microservice.log';
$settings['logger']['level'] = (int) getenv('LOGGER_LEVEL');
$settings['logger']['file_permission'] = 0775;

# Middlewares
$settings['middlewares']['definition'] = "$rootPath/" . ((string) getenv('PATH_MIDDLEWARES_DEFINITION'));

# Error middleware
$settings['error_middleware']['display_details'] = (bool) getenv('DISPLAY_ERROR_DETAILS'); // Should be set to false in production
$settings['error_middleware']['log_errors'] = (bool) getenv('LOGS_ERRORS'); // Should be set to false for the test environment
$settings['error_middleware']['log_details'] = (bool) getenv('LOG_ERROR_DETAILS'); // Display error details in error log

# Router
$settings['router']['cache']['enabled'] = ((bool) getenv('COMPILE_ROUTES'));
$settings['router']['definition'] = "$rootPath/" . ((string) getenv('PATH_ROUTES_DEFINITION'));
$settings['router']['cache']['enabled'] = true;
$settings['router']['cache']['path'] = "$rootPath/" . ((string) getenv('PATH_ROUTES_CACHE'));

return $settings;
