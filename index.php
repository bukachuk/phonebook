<?php
require "vendor/autoload.php";

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Monolog init
$log = new Logger('debug');
$log->pushHandler(new StreamHandler('var/logs/debug.log', Logger::DEBUG));

// Get request
$request = Request::createFromGlobals();

require __DIR__.'/bootstrap.php';
$routes = include __DIR__.'/config/routes.php';

// Start route matching
$context = new Routing\RequestContext();
$context->fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

// Call appropriate controller method
try {
    $parameters = $matcher->match($request->getPathInfo());
    $classname = "Project\Controller\\" . $parameters['_controller'];

    $controller = new $classname($entityManager);
    $method = $parameters['_route'];

    unset($parameters['_route']);
    unset($parameters['_controller']);

    array_unshift($parameters, $request);
    $response = call_user_func_array(array($controller, $method), $parameters);
} catch (Routing\Exception\ResourceNotFoundException $e) {
    $response = new Response('Page not Found ' . $e->getMessage(), 404);
    if(DEBUG) $log->warning($e->getMessage());
} catch(Exception $e) {
    $response = new Response('An error occurred ' . $e->getMessage(), 500);
    if(DEBUG) $log->warning($e->getMessage());
}

$response->send();

