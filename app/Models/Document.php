<?php

namespace App\Models;

use Core\Database\Database;

class Document
{
    public static function create(int $articleId, string $documentPath)
    {
        Database::query(
            'INSERT INTO documents (article_id, path) VALUES(:article_id, :path)',
            [
                'article_id' => $articleId,
                'path'       => $documentPath,
            ]
        )->execute();
    }

    public static function byArticleId($articleId)
    {
        return Database::query(
            'SELECT * from documents WHERE article_id = :article_id',
            [
                'article_id' => $articleId,
            ]
        )->fetchAll();
    }

    public static function remove($id)
    {
        return Database::query(
            'DELETE FROM documents WHERE id = :id',
            [
                'id' => $id,
            ]
        )->execute();
    }
}
