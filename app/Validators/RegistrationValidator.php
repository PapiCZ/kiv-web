<?php

namespace App\Validators;

use Core\Validator\Validator;
use Core\Validator\ValidatorReport;

class RegistrationValidator extends Validator
{
    public function rules(): array
    {
        return [
            'username'                => 'required|minlen:4',
            'name'                    => 'required',
            'surname'                 => 'required',
            'email'                   => 'required|email',
            'avatar'                  => 'required',
            'password|password_again' => function ($password, $passwordAgain) {
                $validatorReport = new ValidatorReport();

                if (empty($password)) {
                    $validatorReport->merge(new ValidatorReport(false, [
                        vmessage('required', vfield('password')),
                    ]));
                }

                if (strlen($password) <= 5) {
                    $validatorReport->merge(new ValidatorReport(false, [
                        vmessage('minlen', vfield('password'), 5),
                    ]));
                }

                if ($password !== $passwordAgain) {
                    $validatorReport->merge(new ValidatorReport(false, [
                        vmessage('same_as', vfield('password'), vfield('password_again')),
                    ]));
                }

                return $validatorReport;
            },
        ];
    }
}
