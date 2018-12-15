<?php

namespace Core;

class ValidatorReport
{
    /**
     * @var bool
     */
    private $result;

    /**
     * @var array
     */
    private $messages;

    /**
     * ValidatorReport constructor.
     *
     * @param bool  $result
     * @param array $messages
     */
    public function __construct(bool $result, array $messages = [])
    {

        $this->result = $result;
        $this->messages = $messages;
    }

    /**
     * @return bool
     */
    public function getResult(): bool
    {
        return $this->result;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
