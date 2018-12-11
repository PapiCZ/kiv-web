<?php

use Core\App;
use Core\Router;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\RequestContext;

$context = new RequestContext();

// Setup context
$context->setMethod($_SERVER['REQUEST_METHOD']);
$context->setBaseUrl(getenv('APP_ROOT'));
$context->setHost($_SERVER['HTTP_HOST']);
$context->setHttpPort($_SERVER['SERVER_PORT']);
$context->setPathInfo($_SERVER['REQUEST_URI']);
$context->setQueryString($_SERVER['QUERY_STRING']);

$router = new Router($context, new FileLocator([__DIR__ . '/../routes']), 'web.yaml');

list($class, $method, $parameters) = $router->getControllerInfo();

// Setup twig
$loader = new Twig_Loader_Filesystem(__DIR__ . '/../resources/templates');
$twig = new Twig_Environment($loader);
$app = new App($twig);
$app->runController($class, $method, $parameters);
