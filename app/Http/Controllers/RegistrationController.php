<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Validators\RegistrationValidator;
use Core\Controller;
use Core\Database\Database;

class RegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('registration/register.twig', []);
    }

    public function register()
    {
        $validator = new RegistrationValidator(array_merge($_POST, $_FILES));
        if ($validator->validate()) {
            User::create($_POST['username'], $_POST['name'], $_POST['surname'], $_POST['email'], password_hash($_POST['password'], PASSWORD_BCRYPT));
            $userId = Database::lastInsertId();

            // Assign author role
            User::assignRole($userId, Role::getByName('author')['id']);

            storage('public/user')->moveToStorage($_FILES['avatar']['tmp_name'], (string)$userId);

            return redirect('index')->with(['__SUCCESS__' => 'Děkujeme za Vaší registraci. Nyní se můžete přihlásit.']);
        } else {
            return redirect('registration.form')->withValidatorReports($validator->getReports());
        }
    }
}
