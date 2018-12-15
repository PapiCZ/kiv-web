<?php

namespace Core;

use Core\Database\Database;
use Twig_Environment;

class App
{
    /**
     * @var Twig_Environment
     */
    private $templateRenderer;

    /**
     * @var Database
     */
    private $db;

    public function __construct(Twig_Environment $templateRenderer, Database $db)
    {
        $this->templateRenderer = $templateRenderer;
        $this->db = $db;
    }

    public function runController(string $class, string $method, array $parameters)
    {
        $result = call_user_func_array([new $class(), $method], $parameters);

        if ($result instanceof View) {
            echo $this->templateRenderer->render($result->getTemplate(), $result->getData());
        }
    }
}
