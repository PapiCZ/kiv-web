<?php

namespace Core;

class Redirect
{
    /**
     * @var string
     */
    private $routeName;

    /**
     * @var array
     */
    private $data = [];

    /**
     * Redirect constructor.
     *
     * @param string $routeName
     */
    public function __construct(string $routeName)
    {
        $this->routeName = $routeName;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function with(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return string
     */
    public function getRouteName(): string
    {
        return $this->routeName;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
