<?php

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('Freepius\Tests', __DIR__);
**
if (!class_exists('Pimple\Container') || !class_exists('Twig_Environment')) {
    echo "You must install the dev dependencies using:\n";
    echo "    composer install --dev\n";
    exit(1);
}
