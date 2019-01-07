<?php

namespace Core;

use App\Models\Role;
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

    public function runController(string $route, string $class, string $method, array $parameters)
    {
        $role = explode('.', $route)[0];
        if (Role::getByName($role)) {
            if (empty($_SESSION['user']) || (!empty($_SESSION['user']) && !in_array($role, array_keys($_SESSION['user']['roles'], true)))) {
                $result = view('403.twig');
            } else {
                $result = call_user_func_array([new $class(), $method], $parameters);
            }
        } else {
            $result = call_user_func_array([new $class(), $method], $parameters);
        }

        if ($result instanceof View) {
            $data = $result->getData();

            // Add basic data to twig
            $data['session'] = $_SESSION;

            // Add alias for validator reports
            if (isset($_SESSION['flash']['validator'])) {
                $data['validator'] = $_SESSION['flash']['validator'];
            }

            // Clear flash data
            $_SESSION['flash'] = [];

            echo $this->templateRenderer->render($result->getTemplate(), $data);
        } elseif ($result instanceof Redirect) {
            $_SESSION['flash'] = $result->getFlashData();

            $urlGenerator = new UrlGenerator($this->router->getRoutes(), $this->requestContext, null, getenv('URLS_TYPE'));
            header('Location: ' . $urlGenerator->generate($result->getRouteName(), $result->getArgs()), true);
        } elseif ($result instanceof Response) {
            echo $result->serialize();
        }
    }
}
