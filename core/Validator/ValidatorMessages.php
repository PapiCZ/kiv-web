<?php

namespace Core\Validator;

class ValidatorMessages
{
    /**
     * @var Database|null
     */
    private static $singleton = null;

    /**
     * @var array
     */
    private $messages;

    public function __construct(array $messages)
    {
        $this->messages = $messages;
    }

    /**
     * @param array $messages
     * @return ValidatorMessages
     */
    public static function createSingleValidatorMessages(array $messages): ValidatorMessages
    {
        if (self::$singleton === null) {
            self::$singleton = new self($messages);
        }

        return self::$singleton;
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([self::$singleton, '_' . $name], $arguments);
    }

    public function _get(string $messageKey, array $params): string
    {
        return vsprintf($this->messages[$messageKey], $params);
    }
}
