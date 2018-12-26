<?php

namespace Core\Validator;

use BadFunctionCallException;
use InvalidArgumentException;

abstract class Validator
{
    /**
     * @var array
     */
    private $dataToValidate;

    /**
     * @var array
     */
    private $results;

    public function __construct(array $dataToValidate)
    {
        $this->dataToValidate = $dataToValidate;
    }

    public abstract function rules(): array;

    public function validate(): bool
    {
        $this->results = [];
        $valid = true;
        $basicValidationRules = self::basicValidationRules();

        foreach ($this->rules() as $key => $validationFunc) {
            if (is_callable($validationFunc)) {
                $keys = explode('|', $key);
                $params = [];
                foreach ($keys as $valueKey) {
                    $params[] = $this->dataToValidate[$valueKey];
                }

                $result = call_user_func_array($validationFunc, $params);
                if (!is_array($this->dataToValidate[$keys[0]])) {
                    $result->setOldValue($this->dataToValidate[$keys[0]]);
                }
                $this->results[$key] = $result;
            } elseif (is_string($validationFunc)) {
                if (strpos($key, '|')) {
                    throw new InvalidArgumentException("String-based doesn't support multiple validation keys. Use anonymous function instead.");
                }

                $validationFunctions = explode('|', $validationFunc);

                foreach ($validationFunctions as $validationFunction) {
                    $parsedFunc = explode(':', $validationFunction);

                    $funcName = $parsedFunc[0];
                    $params = [];

                    if (count($parsedFunc) > 1) {
                        $params = explode(',', $parsedFunc[1]);
                    }

                    $result = call_user_func_array($basicValidationRules[$funcName], array_merge([$key, $this->dataToValidate[$key]], $params));
                    if (!is_array($this->dataToValidate[$key])) {
                        $result->setOldValue($this->dataToValidate[$key]);
                    }

                    if (isset($this->results[$key]) && $this->results[$key] instanceof ValidatorReport) {
                        $this->results[$key]->merge($result);
                    } else {
                        $this->results[$key] = $result;
                    }
                }
            } else {
                throw new BadFunctionCallException('Given validation function is not callable');
            }

            if (!$result->getResult()) {
                $valid = false;
            }
        }

        return $valid;
    }

    /**
     * @return array
     */
    public function getReports(): array
    {
        if ($this->results === null) {
            $this->validate();
        }

        return $this->results;
    }

    public static function basicValidationRules(): array
    {
        return [
            'required'     => function ($name, $value) {
                if (!is_array($value)) {
                    return new ValidatorReport(!empty(preg_replace('/\s|(&nbsp;)/', '', strip_tags(trim($value)))), [
                        vmessage('required', vfield($name)),
                    ]);
                } else {
                    return new ValidatorReport(!empty($value['name'] ?? ''), [
                        vmessage('required', vfield($name)),
                    ]);
                }
            },
            'minlen'       => function ($name, $value, $min) {
                return new ValidatorReport(strlen($value) >= $min, [
                    vmessage('minlen', vfield($name), $min),
                ]);
            },
            'maxlen'       => function ($name, $value, $max) {
                return new ValidatorReport(strlen($value) <= $max, [
                    vmessage('maxlen', vfield($name), $max),
                ]);
            },
            'email'        => function ($name, $value) {
                return new ValidatorReport(filter_var($value, FILTER_VALIDATE_EMAIL), [
                    vmessage('email', vfield($name)),
                ]);
            },
            'file_maxsize' => function ($name, $value, $maxsize) {
                return new ValidatorReport($value['size'] <= $maxsize, [
                    vmessage('file_maxsize', $value['name'], $maxsize / 1000000),
                ]);
            },
        ];
    }
}
