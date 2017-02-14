<?php
require __DIR__ . '/../autoload.php';

$env = getenv('SYMFONY_ENV');
$debug = getenv('SYMFONY_DEBUG');

if ($debug) {
    Symfony\Component\Debug\Debug::enable();
}

$kernel = new App\AppKernel($env, $debug);
$kernel->loadClassCache();

$request = Symfony\Component\HttpFoundation\Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
