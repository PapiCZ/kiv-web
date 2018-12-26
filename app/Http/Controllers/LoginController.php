<?php

namespace App\Http\Controllers;

use App\Models\User;
use Core\Controller;

class LoginController extends Controller
{
    public function login()
    {
        $userData = User::getUserByUsername($_POST['username']);

        if (password_verify($_POST['password'], $userData['password'])) {
            $_SESSION['user'] = $userData;
            $roles = User::getRoles($_SESSION['user']['id']);
            foreach ($roles as $role) {
                $_SESSION['user']['roles'][$role['name']] = $role;
            }

            return response(['status' => 'ok'], 'json');
        } else {
            return response(['status' => 'error'], 'json');
        }
    }

    public function logout()
    {
        unset($_SESSION['user']);

        return redirect('index');
    }
}
