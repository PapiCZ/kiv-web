<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Validators\RegistrationValidator;
use Core\Controller;

class RegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('registration/register.twig', []);
    }

    public function register()
    {
        $validator = new RegistrationValidator($_POST);
        if ($validator->validate()) {
            User::create($_POST['username'], $_POST['name'], $_POST['surname'], $_POST['email'], password_hash($_POST['password'], PASSWORD_BCRYPT));

            return redirect('index') ;
        } else {
            return redirect('register');
        }
    }
}
