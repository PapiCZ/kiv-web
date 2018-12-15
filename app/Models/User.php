<?php

namespace App\Models;

use Core\Database\Database;

class User
{
    public static function create($username, $name, $surname, $email, $password)
    {
        Database::query(
            'INSERT INTO users (username, name, surname, email, password) VALUES(:username, :name, :surname, :email, :password)',
            [
                'username' => $username,
                'name'     => $name,
                'surname'  => $surname,
                'email'    => $email,
                'password' => $password,
            ]
        )->execute();
    }
}
