<?php

namespace Core;

class View
{
    private $template;

    private $data;

    public function __construct($template, $data)
    {
        $this->template = $template;
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
