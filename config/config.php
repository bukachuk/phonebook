<?php
// Debug mode in var/logs
define('DEBUG', true);

/**
 * Database configuration
 */
return $connectionParams = array(
    'dbname' => 'phonebook',
    'user' => '',
    'password' => '',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
);