<?php

namespace Core;

use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Config\FileLocator;

class Router
{
    private $matcher;

    /**
     * @var \Symfony\Component\Routing\RouteCollection
     */
    private $routes;

    public function __construct(RequestContext $context, FileLocator $fileLocator, string $fileName)
    {
        $loader = new YamlFileLoader($fileLocator);
        $this->routes = $loader->load($fileName);

        // Init UrlMatcher object
        $this->matcher = new UrlMatcher($this->routes, $context);
    }

    public function getControllerInfo(): array
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

    /**
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function getRoutes(): \Symfony\Component\Routing\RouteCollection
    {
        return $this->routes;
    }
}
