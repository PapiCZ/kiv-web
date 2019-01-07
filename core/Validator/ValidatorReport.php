<?php

namespace Core\Validator;

class ValidatorReport
{
    /**
     * @var bool
     */
    private $result;

    /**
     * @var array
     */
    private $errorMessages;

    /**
     * @var string
     */
    private $oldValue;

    /**
     * ValidatorReport constructor.
     *
     * @param bool  $result
     * @param array $errorMessages
     */
    public function __construct(?bool $result = true, array $errorMessages = [])
    {
        $this->result = $result;
        $this->errorMessages = [];

        if (!$this->result) {
            foreach ($errorMessages as $errorMessage) {
                /**
                 * @var $errorMessage ValidatorMessage
                 */
                $this->errorMessages[] = ValidatorMessages::get($errorMessage->getMessageKey(), $errorMessage->getParams());
            }
        }
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
    public function getErrorMessages(): array
    {
        return $this->errorMessages;
    }

    /**
     * @param ValidatorReport $validatorReport
     */
    public function merge(ValidatorReport $validatorReport)
    {
        $this->errorMessages = array_merge($this->errorMessages, $validatorReport->getErrorMessages());

        $result = $validatorReport->getResult();
        if (!$result) {
            $this->result = $result;
        }
    }

    /**
     * @return string|array|null
     */
    public function getOldValue()
    {
        return $this->oldValue;
    }

    /**
     * @param string|array $oldValue
     */
    public function setOldValue($oldValue): void
    {
        $this->oldValue = $oldValue;
    }
}
