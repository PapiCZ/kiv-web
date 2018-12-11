<?php

namespace Core;

use Twig_Environment;

class App
{
    private $templateRenderer;

    public function __construct(Twig_Environment $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }

    public function runController($class, $method, $parameters)
    {
        $result = call_user_func_array([new $class(), $method], $parameters);

        if ($result instanceof View) {
            echo $this->templateRenderer->render($result->getTemplate(), $result->getData());
        }
    }
}
