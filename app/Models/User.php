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

    public static function paginate(int $entriesPerPage, int $page = 0)
    {
        return Database::query(
            'SELECT * FROM users
            LIMIT :limit OFFSET :offset',
            [
                'limit'  => $entriesPerPage,
                'offset' => $page * $entriesPerPage,
            ]
        )->fetchAll();
    }

    public static function count()
    {
        return Database::query(
            'SELECT COUNT(*) as count FROM users'
        )->fetch()['count'];
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

    public static function getPaginateArticles(int $id, int $entriesPerPage, int $page = 0)
    {
        return Database::query(
            'SELECT * FROM articles WHERE user_id = :user_id
            LIMIT :limit OFFSET :offset',
            [
                'user_id' => $id,
                'limit'   => $entriesPerPage,
                'offset'  => $page * $entriesPerPage,
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

    public static function reviewersNotAssignedToArticle($id)
    {
        return Database::query(
            'SELECT u.* FROM users u
            LEFT OUTER JOIN reviews r ON u.id = r.user_id
            INNER JOIN user_role ur on u.id = ur.user_id
            WHERE ur.role_id = 3 AND (r.article_id IS NULL OR r.article_id != :id)',
            [
                'id' => $id,
            ]
        )->fetchAll();
    }

    public static function assignRole($userId, $roleId)
    {
        return Database::query(
            'INSERT INTO user_role (user_id, role_id) VALUES(:user_id, :role_id)',
            [
                'user_id' => $userId,
                'role_id' => $roleId,
            ]
        )->execute();
    }

    public static function deleteRole($userId, $roleId)
    {
        return Database::query(
            'DELETE FROM user_role WHERE user_id = :user_id AND role_id = :role_id',
            [
                'user_id' => $userId,
                'role_id' => $roleId,
            ]
        )->execute();
    }

    public static function setResetPasswordToken($userId, $resetPasswordToken)
    {
        return Database::query(
            'UPDATE users SET password_reset_token = :password_reset_token WHERE id = :user_id',
            [
                'user_id'              => $userId,
                'password_reset_token' => $resetPasswordToken,
            ]
        )->execute();
    }

    public static function ban($userId)
    {
        return Database::query(
            'UPDATE users SET banned = 1 WHERE id = :user_id',
            [
                'user_id' => $userId,
            ]
        )->execute();
    }

    public static function unban($userId)
    {
        return Database::query(
            'UPDATE users SET banned = 0 WHERE id = :user_id',
            [
                'user_id' => $userId,
            ]
        )->execute();
    }

    public static function delete($userId)
    {
        return Database::query(
            'DELETE FROM users WHERE id = :user_id',
            [
                'user_id' => $userId,
            ]
        )->execute();
    }
}
