<?php
declare(strict_types=1);

use RestApi\Http\Response;

include_once __DIR__ . '/../vendor/autoload.php';
$routes = include __DIR__ . '/../config/routes.php';
if (!is_array($routes)) {
    throw new LogicException('Routes config should return an array');
}

$container = new \RestApi\Application\Container();
$container->addDefinitions(include __DIR__ . '/../config/config.php');
$container->addDefinitions(include __DIR__ . '/../config/services.php');

$request = new \RestApi\Http\Request(
    $_SERVER,
    file_get_contents('php://input')
);


$requestMethod = $request->getMethod();
$requestPath = $request->getRequestUri();

$response = new Response('Not found', Response::HTTP_NOT_FOUND);

foreach ($routes as $route) {
    [ $method, $path, $handler ] = $route;

    if ($requestMethod === $method && $requestPath === $path) {
        $handler = $container->get($handler);
        $response = $handler($request);
    }
}

if ($response) {
    $response->send();
}
