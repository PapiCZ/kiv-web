<?php

namespace Core;

class Response
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $type;

    public function __construct($data, string $type = 'raw')
    {
        $this->data = $data;
        $this->type = $type;
    }

    public function serialize()
    {
        if (is_string($this->data) && $this->type === 'raw') {
            return $this->data;
        } elseif ($this->type === 'json') {
            return json_encode($this->data);
        }
    }
}
