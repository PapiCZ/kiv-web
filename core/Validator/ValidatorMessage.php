<?php

namespace Core\Validator;

class ValidatorMessage
{
    /**
     * @var string
     */
    private $messageKey;

    /**
     * @var array
     */
    private $params;

    public function __construct(string $messageKey, ...$params)
    {
        $this->messageKey = $messageKey;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getMessageKey(): string
    {
        return $this->messageKey;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
