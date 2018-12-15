<?php

namespace Core;

use BadFunctionCallException;

abstract class Validator
{
    /**
     * @var array
     */
    private $validatedData;

    /**
     * @var array
     */
    private $results;

    public function __construct(array $validatedData)
    {
        $this->validatedData = $validatedData;
    }

    public abstract function rules(): array;

    public function validate() : bool
    {
        $this->results = [];
        $valid = true;
        foreach ($this->rules() as $key => $validationFunc) {
            if (is_callable($validationFunc)) {
                $keys = explode(',', $key);
                $params = [];

                foreach ($keys as $valueKey) {
                    $params[] = $_POST[$valueKey];
                }

                $result = call_user_func_array($validationFunc, $params);
                $this->results[$key] = $result;

                if (!$result->getResult()) {
                    $valid = false;
                }
            } else {
                throw new BadFunctionCallException('Given validation function is not callable');
            }
        }

        return $valid;
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }
}
