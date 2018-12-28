<?php

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/ExceptionsHandler.php';
require __DIR__.'/ConsoleKernel.php';

try {
    (new Dotenv\Dotenv(__DIR__))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__)
);

$app->withFacades();

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    Tests\ExceptionsHandler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    Tests\ConsoleKernel::class
);

$app->register(BenjaminChen\TableActivityRecord\ServiceProvider::class);

$app->configure('logging');

$app->boot();

return $app;