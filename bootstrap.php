<?php
// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

// database configuration parameters
require_once __DIR__ . '/config/config.php';

if(file_exists( __DIR__ . '/config/config.local.php')){
    require_once __DIR__ . '/config/config.local.php';
}

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src/Entity"), DEBUG);

// obtaining the entity manager
$entityManager = EntityManager::create($connectionParams, $config);
