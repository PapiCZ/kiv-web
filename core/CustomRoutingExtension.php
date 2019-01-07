<?php

namespace Core;

use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Twig\TwigFunction;

class CustomRoutingExtension extends RoutingExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('url', [$this, 'getUrl'], ['is_safe' => ['html'], 'is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
            new TwigFunction('path', [$this, 'getPath'], ['is_safe' => ['html'], 'is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
        ];
    }

    public function getPath($name, $parameters = [], $relative = false)
    {
        return urldecode(parent::getPath($name, $parameters, $relative));
    }

    public function getUrl($name, $parameters = [], $schemeRelative = false)
    {
        return urldecode(parent::getUrl($name, $parameters, $schemeRelative));
    }
}
