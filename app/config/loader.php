<?php

use Phalcon\Loader;

$loader = new Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerNamespaces([
    'GeoEntreprise\Models'      => $config->application->modelsDir,
    'GeoEntreprise\Controllers' => $config->application->controllersDir,
    'GeoEntreprise\Forms'       => $config->application->formsDir,
    'GeoEntreprise'             => $config->application->libraryDir
]);

$loader->register();

// Use composer autoloader to load vendor classes
require_once BASE_PATH . '/vendor/autoload.php';
