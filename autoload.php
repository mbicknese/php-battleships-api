<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

// require Composer's autoloader
$loader = require __DIR__.'/vendor/autoload.php';

// auto-load annotations
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
