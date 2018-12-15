<?php

namespace Core;

use Core\Database\Database;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Twig_Environment;

class App
{
    /**
     * @var RequestContext
     */
    private $requestContext;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Twig_Environment
     */
    private $templateRenderer;

    /**
     * @var Database
     */
    private $db;

    public function __construct(RequestContext $requestContext, Router $router, Twig_Environment $templateRenderer, Database $db)
    {
        $this->requestContext = $requestContext;
        $this->router = $router;
        $this->templateRenderer = $templateRenderer;
        $this->db = $db;
    }

    public function runController(string $class, string $method, array $parameters)
    {
        $result = call_user_func_array([new $class(), $method], $parameters);

        if ($result instanceof View) {
            $data = $result->getData();

            // Add flash messages
            $data['flash'] = $_SESSION['flash'];
            $_SESSION['flash'] = [];

            echo $this->templateRenderer->render($result->getTemplate(), $data);
        } elseif ($result instanceof Redirect) {
            $_SESSION['flash'] = $result->getData();

            $urlGenerator = new UrlGenerator($this->router->getRoutes(), $this->requestContext);
            header('Location: ' . $urlGenerator->generate($result->getRouteName()), true);
        }
    }
}
