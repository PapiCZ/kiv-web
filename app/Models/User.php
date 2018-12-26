<?php

namespace App\Models;

use Core\Database\Database;

class User
{
    public static function all()
    {
        return Database::query('SELECT * FROM users')
            ->fetchAll();
    }

    public static function get(int $id)
    {
        return Database::query(
            'SELECT * FROM users WHERE id = :id LIMIT 1',
            [
                'id' => $id,
            ])->fetch();
    }

    public static function getWithRoles(int $id)
    {
        return Database::query(
            'SELECT u.*, r.name AS role_name, r.display_name AS role_display_name FROM users u 
            INNER JOIN user_role ur on u.id = ur.user_id
            INNER JOIN roles r on ur.role_id = r.id
            WHERE u.id = :id LIMIT 1',
            [
                'id' => $id,
            ])->fetch();
    }

    public static function create($username, $name, $surname, $email, $password)
    {
        return Database::query(
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

    public static function getArticles(int $id)
    {
        return Database::query(
            'SELECT * FROM articles WHERE user_id = :user_id',
            [
                'user_id' => $id,
            ]
        )->fetchAll();
    }

    public static function getUserByUsername($username)
    {
        return Database::query(
            'SELECT * FROM users WHERE username = :username LIMIT 1',
            [
                'username' => $username,
            ])->fetch();
    }

    public static function getRoles($userId)
    {
        return Database::query(
            'SELECT r.* FROM users u
            INNER JOIN user_role ur ON ur.user_id = u.id
            INNER JOIN roles r ON r.id = ur.role_id 
            WHERE u.id = :id',
            [
                'id' => $userId,
            ])->fetchAll();
    }

    public static function notAssignedToArticle($id)
    {
        return Database::query(
            'SELECT u.* FROM users u
            LEFT OUTER JOIN reviews r ON u.id = r.user_id
            WHERE r.article_id IS NULL OR r.article_id != :id',
            [
                'id' => $id,
            ]
        )->fetchAll();
    }

    public static function assignRole($userId, $roleId)
    {
        Database::query(
            'INSERT INTO user_role (user_id, role_id) VALUES(:user_id, :role_id)',
            [
                'user_id' => $userId,
                'role_id' => $roleId,
            ]
        )->execute();
    }
}
