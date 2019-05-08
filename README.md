# Utopia Registry

[![Build Status](https://travis-ci.org/utopia-php/registry.svg?branch=master)](https://travis-ci.org/utopia-php/registry)
![Total Downloads](https://img.shields.io/packagist/dt/utopia-php/registry.svg)
![License](https://img.shields.io/github/license/utopia-php/registry.svg)

Utopia Registry library is simple and lite library for managing dependency management and lazy load initialization of PHP objects or resources. This library is aiming to be as simple and easy to learn and use.

Although this library is part of the [Utopia Framework](https://github.com/utopia-php/framework) project it is dependency free and can be used as standalone with any other PHP project or framework.

## Getting Started

Install using composer:
```bash
composer require utopia-php/registry
```

script.php
```php
<?php

require_once '../vendor/autoload.php';

use Utopia\Registry\Registry;

global $dbHost, $dbUser, $dbPass, $dbScheme;

$register = new Registry();

$register->set('db', function() use ($dbHost, $dbUser, $dbPass, $dbScheme) { // Register DB connection
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbScheme}", $dbUser, $dbPass, array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        PDO::ATTR_TIMEOUT => 5 // Seconds
    ));

    // Connection settings
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);   // Return arrays
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);        // Handle all errors with exceptions

    return $pdo;
});

/**
 * Execute callback and create database connection only when
 *  you need it and not a second before
 */
$register->get('db');

/**
 * Second call for db service will return the instance that has been created
 *  in the previous line of code
 */
$register->get('db');

/**
 * Third call for db service when passing the value 'true' to the $fresh argument
 *  will return a fresh and new instance of the db service
 */
$register->get('db', true);

var_dump($register->counter['db']); // Counting number of calls for db service
var_dump($register->created['db']); // Counter of all db service instances
```

## System Requirements

Utopia Framework requires PHP 7.1 or later. We recommend using the latest PHP version whenever possible.

## Authors

**Eldad Fux**

+ [https://twitter.com/eldadfux](https://twitter.com/eldadfux)
+ [https://github.com/eldadfux](https://github.com/eldadfux)

## Copyright and license

The MIT License (MIT) [http://www.opensource.org/licenses/mit-license.php](http://www.opensource.org/licenses/mit-license.php)