<?php

namespace App\Models;

use Core\Database\Database;

class Article
{
    public static function create($userId, $title, $perex, $image, $content)
    {
        return Database::query(
            'INSERT INTO articles (user_id, title, perex, image, content) VALUES(:user_id, :title, :perex, :image, :content)',
            [
                'user_id' => $userId,
                'title'   => $title,
                'perex'   => $perex,
                'image'   => $image,
                'content' => $content,
            ]
        )->execute();
    }

    public static function update($id, $title, $perex, $content, $published, $returned)
    {
        return Database::query(
            'UPDATE articles SET title = :title, perex = :perex, content = :content, published = :published, returned = :returned WHERE id = :id',
            [
                'id'        => $id,
                'title'     => $title,
                'perex'     => $perex,
                'content'   => $content,
                'published' => $published,
                'returned'  => $returned,
            ]
        )->execute();
    }

    public static function all()
    {
        return Database::query(
            'SELECT a.*, u.username, u.name, u.surname FROM articles a
            INNER JOIN users u ON u.id = a.user_id')
            ->fetchAll();
    }

    public static function get(int $id)
    {
        return Database::query(
            'SELECT a.*, u.username, u.name, u.surname FROM articles a
            INNER JOIN users u ON u.id = a.user_id
            WHERE a.id = :id
            LIMIT 1',
            [
                'id' => $id,
            ])->fetch();
    }

    public static function getByReviewId(int $id)
    {
        return Database::query(
            'SELECT a.*, u.username, u.name, u.surname, r.score_topic, r.score_content, r.score_readability FROM articles a
            INNER JOIN users u ON u.id = a.user_id
            INNER JOIN reviews r on a.id = r.article_id
            WHERE r.id = :id
            LIMIT 1',
            [
                'id' => $id,
            ])->fetch();
    }

    public static function completeReviewsForArticle($articleId)
    {
        return Database::query(
            'SELECT * FROM reviews r 
            INNER JOIN articles a on r.article_id = a.id
            WHERE a.id = :article_id
              AND r.score_topic IS NOT NULL
              AND r.score_content IS NOT NULL
              AND r.score_readability IS NOT NULL',
            [
                'article_id' => $articleId,
            ]
        )->fetchAll();
    }

    public static function getRatingForArticle($articleId)
    {
        return Database::query(
            'SELECT ROUND(AVG(score), 2) AS total_score FROM
                       (
                         SELECT ROUND((r.score_topic + r.score_content + r.score_readability) / 3, 2) AS score FROM reviews r
                         WHERE r.article_id = :article_id
                         ) AS score_per_review',
            [
                'article_id' => $articleId,
            ]
        )->fetch();
    }

    public static function previous(int $id)
    {
        return Database::query(
            'SELECT * FROM articles WHERE id = (SELECT MAX(id) FROM articles WHERE id < :id)',
            [
                'id' => $id,
            ]
        )->fetch();
    }

    public static function next(int $id)
    {
        return Database::query(
            'SELECT * FROM articles WHERE id = (SELECT MIN(id) FROM articles WHERE id > :id)',
            [
                'id' => $id,
            ]
        )->fetch();
    }

    public static function reviewers($id)
    {
        return Database::query(
            'SELECT u.username, u.name, u.surname, r.* from users u
            INNER JOIN reviews r ON r.user_id = u.id
            WHERE r.article_id = :article_id',
            [
                'article_id' => $id,
            ]
        )->fetchAll();
    }

    public static function updateState($id, $returned, $published, $note)
    {
        return Database::query(
            'UPDATE articles SET returned = :returned, published = :published, note = :note WHERE id = :id',
            [
                'id'        => $id,
                'returned'  => $returned,
                'published' => $published,
                'note'      => $note,
            ]
        )->execute();
    }

    public static function delete($id)
    {
        return Database::query(
            'DELETE FROM articles WHERE id = :id',
            [
                'id' => $id,
            ]
        )->execute();
    }

    public static function assignReviewer($articleId, $userId)
    {
        return Database::query(
            'INSERT INTO reviews (user_id, article_id) VALUES(:user_id, :article_id)',
            [
                'article_id' => $articleId,
                'user_id'    => $userId,
            ]
        )->execute();
    }
}
