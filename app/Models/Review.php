<?php

namespace App\Models;

use Core\Database\Database;

class Review
{
    public static function forUser($userId)
    {
        return Database::query(
            'SELECT *, ROUND((r.score_topic + r.score_content + r.score_readability) / 3, 2) AS score FROM articles a 
            INNER JOIN reviews r ON a.id = r.article_id
            WHERE r.user_id = :user_id',
            [
                'user_id' => $userId,
            ]
        )->fetchAll();
    }

    public static function countForUser($userId)
    {
        return Database::query(
            'SELECT COUNT(*) as count FROM reviews
            WHERE user_id = :user_id',
            [
                'user_id' => $userId,
            ]
        )->fetch()['count'];
    }

    public static function forUserPaginate($userId, int $entriesPerPage, int $page = 0)
    {
        return Database::query(
            'SELECT *, ROUND((r.score_topic + r.score_content + r.score_readability) / 3, 2) AS score FROM articles a 
            INNER JOIN reviews r ON a.id = r.article_id
            WHERE r.user_id = :user_id
            LIMIT :limit OFFSET :offset',
            [
                'user_id' => $userId,
                'limit'   => $entriesPerPage,
                'offset'  => $page * $entriesPerPage,
            ]
        )->fetchAll();
    }

    public static function save($reviewId, $scoreTopic, $scoreContent, $scoreReadability)
    {
        return Database::query(
            'UPDATE reviews SET score_topic = :score_topic, score_content = :score_content, score_readability = :score_readability
            WHERE id = :review_id',
            [
                'review_id'         => $reviewId,
                'score_topic'       => $scoreTopic,
                'score_content'     => $scoreContent,
                'score_readability' => $scoreReadability,
            ]
        )->execute();
    }

    public static function delete($id)
    {
        return Database::query(
            'DELETE FROM reviews WHERE id = :review_id',
            [
                'review_id' => $id,
            ]
        )->execute();
    }
}
