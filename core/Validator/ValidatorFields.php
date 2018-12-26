<?php

namespace Core\Validator;

class ValidatorFields
{
    /**
     * @var Database|null
     */
    private static $singleton = null;

    /**
     * @var array
     */
    private $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @param array $messages
     * @return ValidatorFields
     */
    public static function createSingleValidatorFields(array $messages): ValidatorFields
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

    public function _get(string $fieldKey): string
    {
        return $this->fields[$fieldKey] ?? $fieldKey;
    }
}
