<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\User;
use Core\Controller;
use Core\Database\Database;

class ArticlesController extends Controller
{
    public function showArticles()
    {
        $this->data['articles'] = Article::all();

        foreach ($this->data['articles'] as $key => $article) {
            $this->data['articles'][$key]['score'] = Article::getRatingForArticle($article['id'])['total_score'];
        }

        return view('admin/articles.twig', $this->data);
    }

    public function showArticleDetail($id)
    {
        $this->data['article'] = Article::get($id);
        $this->data['users'] = User::notAssignedToArticle($id);
        $this->data['reviewers'] = Article::reviewers($id);
        $this->data['completeReviews'] = Article::completeReviewsForArticle($id);

        return view('admin/article_detail.twig', $this->data);
    }

    public function submitDetail($id)
    {
        if ($_POST['action'] === 'delete') {
            Article::delete($id);
        } else {
            if ($_POST['action'] !== 'publish' || ($_POST['action'] === 'publish' && count(Article::completeReviewsForArticle($id)) >= 3)) {
                Article::updateState($id, $_POST['action'] === 'return', $_POST['action'] === 'publish', $_POST['note']);
            }
        }

        return redirect('admin.articles');
    }

    public function assignReviewer()
    {
        Article::assignReviewer($_POST['article_id'], $_POST['user_id']);

        return response(['id' => Database::lastInsertId(), 'status' => 'success'], 'json');
    }
}
