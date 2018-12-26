<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Core\Controller;

class UsersController extends Controller
{
    public function showUsers()
    {
        $this->data['users'] = User::all();

        return view('admin/users.twig', $this->data);
    }

    public function showUserDetail($id)
    {
        $this->data['user'] = User::getWithRoles($id);

        return view('admin/user_detail.twig', $this->data);
    }
}
