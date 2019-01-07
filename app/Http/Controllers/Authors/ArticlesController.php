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
    public function showMyArticles($page = 0)
    {
        $this->data['articles'] = User::getPaginateArticles($_SESSION['user']['id'], getenv('TABLE_ENTRIES_PER_PAGE'), $page);

        $this->data['currentPage'] = $page;
        $this->data['lastPage'] = ceil(max(0, Article::countForUser($_SESSION['user']['id']) / getenv('TABLE_ENTRIES_PER_PAGE') - 1));

        return view('authors/my_articles.twig', $this->data);
    }

    public function showNewArticleForm()
    {
        return view('authors/new_article.twig');
    }

    public function createNewArticle()
    {
        // Copy old document files to $_FILES
        foreach ($_POST['old_document_files'] ?? [] as $documentFile) {
            $_FILES['document_files']['name'][] = $documentFile;
        }

        $validator = new ArticleValidator(array_merge($_POST, $_FILES));
        if ($validator->validate()) {
            Article::create($_SESSION['user']['id'], $_POST['title'], $_POST['perex'], $_FILES['image']['name'], $_POST['content']);
            $articleId = Database::lastInsertId();

            $documentFiles = $_FILES['document_files'];
            for ($i = 0; $i < count($documentFiles['tmp_name']); $i++) {
                if (!empty($documentFiles['name'][$i])) {
                    storage('public/documents')->moveToStorage($documentFiles['tmp_name'][$i], $articleId . '/' . $documentFiles['name'][$i]);
                    Document::create($articleId, $documentFiles['name'][$i]);
                }
            }

            for (; $i < count($documentFiles['name']); $i++) {
                if (!empty($documentFiles['name'][$i])) {
                    storage('public/documents')->moveToStorage(storage("temp/user/{$_SESSION['user']['id']}/article/new/documents")->getAbsolutePath() . '/' . $documentFiles['name'][$i],
                        $articleId . '/' . $documentFiles['name'][$i]);
                    Document::create($articleId, $documentFiles['name'][$i]);
                }
            }

            if ($_FILES['image']['tmp_name'] ?? false) {
                storage('public/article_images')->moveToStorage($_FILES['image']['tmp_name'], $articleId . '/' . $_FILES['image']['name']);
            } else {
                $imageName = scandir(storage("temp/user/{$_SESSION['user']['id']}/article/new/image")->getAbsolutePath())[2] ?? false;

                if ($imageName) {
                    $imagePath = storage("temp/user/{$_SESSION['user']['id']}/article/new/image")->getAbsolutePath() . '/' . $imageName;

                    storage('public/article_images')->moveToStorage($imagePath, $articleId . '/' . $imageName);

                    Article::updateImage($articleId, $imageName);
                }
            }

            return redirect('author.articles.my')->with(['__SUCCESS__' => 'Článek byl úspěšně podán ke schválení']);
        } else {
            // Move image to temporary storage
            if ($_FILES['image']['tmp_name']) {
                storage("temp/user/{$_SESSION['user']['id']}/article/new/image")->moveToStorage($_FILES['image']['tmp_name'], $_FILES['image']['name']);
            }

            // Move documents to temporary storage
            $documentFiles = $_FILES['document_files'];
            for ($i = 0; $i < count($documentFiles['tmp_name']); $i++) {
                if (!empty($documentFiles['name'][$i])) {
                    storage("temp/user/{$_SESSION['user']['id']}/article/new/documents")->moveToStorage($documentFiles['tmp_name'][$i], $documentFiles['name'][$i]);
                }
            }

            return redirect('author.article.new')->withValidatorReports($validator->getReports());
        }
    }

    public function showEditArticleForm($id)
    {
        $article = Article::get($id);

        $this->data = ['article' => $article];
        $this->data['article']['documents'] = Document::forArticle($id);
        $this->data['edit'] = true;

        return view('authors/new_article.twig', $this->data);
    }

    public function editArticle($articleId)
    {
        // Copy old document files to $_FILES
        foreach ($_POST['old_document_files'] ?? [] as $documentFile) {
            $_FILES['document_files']['name'][] = $documentFile;
        }

        $validator = new ArticleValidator(array_merge($_POST, $_FILES));
        $article = Article::get($articleId);

        if (!$article['returned']) {
            return redirect('author.article.edit', ['id' => $articleId]);
        }

        if ($validator->validate()) {
            if (!$article['published']) {
                Article::update($articleId, $_POST['title'], $_POST['perex'], $_POST['content'], false, false);

                $files = $_FILES['document_files'];
                for ($i = 0; $i < count($files['tmp_name']); $i++) {
                    if (!empty($files['name'][$i])) {
                        storage('public/documents')->moveToStorage($files['tmp_name'][$i], $articleId . '/' . $files['name'][$i]);
                        Document::create($articleId, $files['name'][$i]);
                    }
                }

                for (; $i < count($files['name']); $i++) {
                    if (!empty($files['name'][$i])) {
                        storage('public/documents')->moveToStorage(storage("temp/user/{$_SESSION['user']['id']}/article/new/documents")->getAbsolutePath() . '/' . $files['name'][$i],
                            $articleId . '/' . $files['name'][$i]);
                        Document::create($articleId, $files['name'][$i]);
                    }
                }

                foreach ($_POST['remove_documents'] ?? [] as $removeDocumentId) {
                    Document::remove($removeDocumentId);
                }

                if ($_FILES['image']['tmp_name'] ?? false) {
                    storage('public/article_images')->moveToStorage($_FILES['image']['tmp_name'], $articleId . '/' . $_FILES['image']['name']);
                } else {
                    $imageName = scandir(storage("temp/user/{$_SESSION['user']['id']}/article/new/image")->getAbsolutePath())[2] ?? false;

                    if ($imageName) {
                        $imagePath = storage("temp/user/{$_SESSION['user']['id']}/article/new/image")->getAbsolutePath() . '/' . $imageName;

                        storage('public/article_images')->moveToStorage($imagePath, $articleId . '/' . $imageName);

                        Article::updateImage($articleId, $imageName);
                    }
                }

                return redirect('author.articles.my')->with(['__SUCCESS__' => 'Článek byl úspěšně upraven']);
            }
        } else {
            // Move image to temporary storage
            if ($_FILES['image']['tmp_name']) {
                storage("temp/user/{$_SESSION['user']['id']}/article/new/image")->moveToStorage($_FILES['image']['tmp_name'], $_FILES['image']['name']);
            }

            // Move documents to temporary storage
            $documentFiles = $_FILES['document_files'];
            for ($i = 0; $i < count($documentFiles['tmp_name']); $i++) {
                if (!empty($documentFiles['name'][$i])) {
                    storage("temp/user/{$_SESSION['user']['id']}/article/new/documents")->moveToStorage($documentFiles['tmp_name'][$i], $documentFiles['name'][$i]);
                }
            }

            return redirect('author.article.edit', ['id' => $articleId])->withValidatorReports($validator->getReports());
        }
    }
}
