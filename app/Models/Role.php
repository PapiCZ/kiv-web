<?php

namespace App\Models;

use Core\Database\Database;

class Role
{
    public static function getByName($name)
    {
        return Database::query(
            'SELECT * FROM roles WHERE roles.name = :name',
            [
                'name' => $name
            ]
        )->fetch();
    }
}
