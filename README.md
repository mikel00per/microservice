# PHP Slim Infrastructure

This package has been created to manage the creation of a simple microservice. Slim php was wrapped into MicroserviceSlim, also the package control the cache routes, log errors and handle the correct response.

## Configuration

To use the package you need config the container dependence. You can copy the following files to config your http microservice:

- config/container.php
- config/settings.php
- config/bootstrap.php // File executed by index.php

## Make commands

````shell
$ make help

Usage: make [target] ...

Tests:
  test                Execute tests
  test-coverage       Execute tests with coverage
                      
Miscellaneous:
  help                Show this help
                      
Container:
  run                 Build and run php container
  build               Build php container
  stop                Stop php container
  destroy             Remove all data related with php container
  shell               SSH in container
  logs                SSH logs in container
                      
Code:
  exec                Execute composer commands
                      
Style:
  lint                Show style errors
  lint-fix            Fix style errors
                      
Written by Antonio Miguel Morillo Chica, version v1.0
Please report any bug or error to the author.
````
