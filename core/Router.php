<?php

namespace Core;

use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Config\FileLocator;

class Router
{
    private $matcher;

    public function __construct(RequestContext $context, FileLocator $fileLocator, $fileName)
    {
        $loader = new YamlFileLoader($fileLocator);
        $routes = $loader->load($fileName);

        // Init UrlMatcher object
        $this->matcher = new UrlMatcher($routes, $context);
    }

    public function getControllerInfo()
    {
        // Find the current route
        $parameters = $this->matcher->match($_SERVER['REQUEST_URI']);
        list($class, $method) = explode('::', $parameters['_controller']);

        // Prepare method parameters
        $methodParams = [];
        foreach ($parameters as $key => $parameter) {
            if (substr($key, 0, 1) != '_') {
                $methodParams[$key] = $parameter;
            }
        }

        return [$class, $method, $methodParams];
    }
}
