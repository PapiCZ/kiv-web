<?php

namespace App\Http\Controllers\Authors;

use App\Models\Article;
use App\Models\Document;
use App\Models\User;
use App\Validators\ArticleValidator;
use Core\Controller;
use Core\Database\Database;

class ArticlesController extends Controller
{
    public function showMyArticles()
    {
        $this->data['articles'] = User::getArticles($_SESSION['user']['id']);

        return view('authors/my_articles.twig', $this->data);
    }

    public function showNewArticleForm()
    {
        return view('authors/new_article.twig');
    }

    public function createNewArticle()
    {
        $validator = new ArticleValidator(array_merge($_POST, $_FILES));
        if ($validator->validate()) {
            Article::create($_SESSION['user']['id'], $_POST['title'], $_POST['perex'], $_FILES['image']['name'], $_POST['content']);
            $articleId = Database::lastInsertId();

            $files = $_FILES['document_files'];
            for ($i = 0; $i < count($files['name']); $i++) {
                if (!empty($files['name'][$i])) {
                    storage('documents')->moveToStorage($files['tmp_name'][$i], $articleId . '/' . $files['name'][$i]);
                    Document::create($articleId, $files['name'][$i]);
                }
            }

            storage('public/article_images')->moveToStorage($_FILES['image']['tmp_name'], $articleId . '/' . $_FILES['image']['name']);

            return redirect('authors.articles.my');
        } else {
            return redirect('authors.article.new')->withValidatorReports($validator->getReports());
        }
    }

    public function showEditArticleForm($id)
    {
        $article = Article::get($id);

        $this->data = ['article' => $article];
        $this->data['article']['documents'] = Document::byArticleId($id);
        $this->data['edit'] = true;

        return view('authors/new_article.twig', $this->data);
    }

    public function editArticle($articleId)
    {
        $validator = new ArticleValidator(array_merge($_POST, $_FILES));
        if ($validator->validate()) {
            if (!Article::get($articleId)['published']) {
                Article::update($articleId, $_POST['title'], $_POST['perex'], $_POST['content'], false, false);

                $files = $_FILES['document_files'];
                for ($i = 0; $i < count($files['name']); $i++) {
                    if (!empty($files['name'][$i])) {
                        storage('documents')->moveToStorage($files['tmp_name'][$i], $articleId . '/' . $files['name'][$i]);
                        Document::create($articleId, $files['name'][$i]);
                    }
                }

                foreach ($_POST['remove_documents'] ?? [] as $removeDocumentId) {
                    Document::remove($removeDocumentId);
                }

                return redirect('authors.articles.my');
            }
        } else {
            return redirect('authors.article.edit', ['id' => $articleId])->withValidatorReports($validator->getReports());
        }
    }
}
