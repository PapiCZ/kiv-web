<?php

use Core\App;
use Core\Database\Database;
use Core\Router;
use Core\Validator\ValidatorFields;
use Core\Validator\ValidatorMessages;
use Core\CustomRoutingExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Yaml\Yaml;

// Init flash messages
if (!isset($_SESSION['flash'])) {
    $_SESSION['flash'] = [];
}

$context = new RequestContext();

// Setup context
$context->setMethod($_SERVER['REQUEST_METHOD']);
$context->setBaseUrl(getenv('APP_ROOT'));
$context->setHost($_SERVER['HTTP_HOST']);
$context->setHttpPort($_SERVER['SERVER_PORT']);
$context->setPathInfo($_SERVER['REQUEST_URI']);
$context->setQueryString($_SERVER['QUERY_STRING']);

$router = new Router($context, new FileLocator([__DIR__ . '/../routes']), 'web.yaml');

list($class, $method, $parameters, $route) = $router->getControllerInfo();

// Setup validator messages and fields
ValidatorMessages::createSingleValidatorMessages(Yaml::parse(file_get_contents(__DIR__ . '/../resources/validation/messages.yaml')));
ValidatorFields::createSingleValidatorFields(Yaml::parse(file_get_contents(__DIR__ . '/../resources/validation/fields.yaml')));

// Setup twig
$loader = new Twig_Loader_Filesystem(__DIR__ . '/../resources/templates');
$twig = new Twig_Environment($loader, [
    'debug' => getenv('DEBUG'),
]);


$loader->addPath(__DIR__ . '/../resources/templates', 'project');

$twig->addExtension(new CustomRoutingExtension(new UrlGenerator($router->getRoutes(), $context, null, getenv('URLS_TYPE'))));
$twig->addFunction(new Twig_Function('getenv', function ($variableName) {
    return getenv($variableName);
}));
$twig->addFunction(new Twig_Function('empty_html', function($html) {
    return empty(preg_replace('/\s|(&nbsp;)/', '', strip_tags(trim($html))));
}));

if (getenv('DEBUG')) {
    $twig->addExtension(new Twig_Extension_Debug());
}

// Setup database
$db = Database::createSingleDatabaseConnection(getenv('MYSQL_HOST'), getenv('MYSQL_DATABASE'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'));

$app = new App($context, $router, $twig, $db);
$app->runController($route, $class, $method, $parameters);
