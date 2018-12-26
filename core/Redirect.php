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
    private $flashData = [];

    /**
     * @var array
     */
    private $args;

    /**
     * Redirect constructor.
     *
     * @param string $routeName
     * @param array  $args
     */
    public function __construct(string $routeName, array $args = [])
    {
        $this->routeName = $routeName;
        $this->args = $args;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function with(array $data)
    {
        $this->flashData = $data;

        return $this;
    }

    /**
     * @param array $validatorReports
     * @return $this
     */
    public function withValidatorReports(array $validatorReports)
    {
        $this->with(['validator' => ['reports' => $validatorReports]]);

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
    public function getFlashData()
    {
        return $this->flashData;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }
}
