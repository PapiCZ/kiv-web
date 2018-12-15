<?php

namespace App\Validators;

use Core\Validator;
use Core\ValidatorReport;

class RegistrationValidator extends Validator
{
    public function rules(): array
    {
        return [
            'username' => function ($username) {
                return new ValidatorReport(!empty($username) && strlen($username) <= 50);
            },
            'name' => function ($name) {
                return new ValidatorReport(!empty($name) && strlen($name) <= 50);
            },
            'surname' => function ($surname) {
                return new ValidatorReport(!empty($surname) && strlen($surname) <= 50);
            },
            'email' => function ($email) {
                return new ValidatorReport(!empty($email) && strlen($email) <= 50);
            },
            'password,password_again' => function ($password, $passwordAgain) {
                return new ValidatorReport(strlen($password) > 5 && $password == $passwordAgain);
            },
        ];
    }
}
