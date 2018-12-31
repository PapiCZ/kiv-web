<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
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
        $this->data['user'] = User::get($id);
        $this->data['user']['roles'] = User::getRoles($id);
        $this->data['roles'] = Role::all();

        return view('admin/user_detail.twig', $this->data);
    }

    public function updateRoles($id)
    {
        $userRoles = User::getRoles($id);
        $userRoleNames = [];

        foreach ($userRoles as $userRole) {
            $userRoleNames[] = $userRole['name'];
        }

        $rolesToAssign = array_diff($_POST['roles'], $userRoleNames);
        foreach ($rolesToAssign as $role) {
            User::assignRole($id, Role::getByName($role)['id'] ?? '');
        }

        $rolesToDelete = array_diff($userRoleNames, $_POST['roles']);
        foreach ($rolesToDelete as $role) {
            User::deleteRole($id, Role::getByName($role)['id'] ?? '');
        }

        return redirect('admin.user.detail', ['id' => $id]);
    }

    public function ban($id)
    {
        User::ban($id);

        return redirect('admin.user.detail', ['id' => $id]);
    }

    public function unban($id)
    {
        User::unban($id);

        return redirect('admin.user.detail', ['id' => $id]);
    }

    public function delete($id)
    {
        User::delete($id);

        return redirect('admin.users');
    }
}
