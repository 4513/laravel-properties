<?php

declare(strict_types=1);

use Illuminate\Contracts\Console\Kernel as ContractConsoleKernel;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Http\Kernel as ContractKernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Foundation\Http\Kernel;

$app = new Application(( dirname(__DIR__)));

$app->singleton(ContractKernel::class, Kernel::class);
$app->singleton(ContractConsoleKernel::class, ConsoleKernel::class);
$app->singleton(ExceptionHandler::class, Handler::class);

return $app;
