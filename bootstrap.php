<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

date_default_timezone_set('America/Lima');

require_once "vendor/autoload.php";
$isDevMode = true;

$config = Setup::createYAMLMetadataConfiguration(array(__DIR__ . "/config/yaml"), $isDevMode);

$conn = array(
'host' => 'ec2-52-31-233-101.eu-west-1.compute.amazonaws.com',
'port' => '5432',
'driver' => 'pdo_pgsql',
'user' => 'wlyfxrndazeptl',
'password' => 'fc71721331f20387a3ed70fae53331cc310ba02bba144c65f1b2f64d63eba0c7',
'dbname' => 'dbnrl8tduuo1ah',
);
$entityManager = EntityManager::create($conn, $config);
