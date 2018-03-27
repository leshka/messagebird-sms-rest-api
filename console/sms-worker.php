<?php
declare(strict_types=1);

use RestApi\Application\Container;
use RestApi\Console\MessageWorker;

include_once __DIR__ . '/../vendor/autoload.php';

$container = new Container();
$container->addDefinitions(include __DIR__ . '/../config/config.php');
$container->addDefinitions(include __DIR__ . '/../config/services.php');

/**
 * @var MessageWorker $handler
 */
$handler = $container->get(MessageWorker::class);
$handler();
