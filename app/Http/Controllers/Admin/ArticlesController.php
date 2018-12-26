<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\User;
use Core\Controller;

class ArticlesController extends Controller
{
    public function showArticles()
    {
        $this->data['articles'] = Article::all();

        return view('admin/articles.twig', $this->data);
    }

    public function showArticleDetail($id)
    {
        $this->data['article'] = Article::get($id);
        $this->data['users'] = User::notAssignedToArticle($id);
        $this->data['reviewers'] = Article::reviewers($id);

        return view('admin/article_detail.twig', $this->data);
    }

    public function submitDetail($id)
    {
        if ($_POST['action'] === 'delete') {
            Article::delete($id);
        } else {
            Article::updateState($id, $_POST['action'] === 'return', $_POST['action'] === 'publish', $_POST['note']);
        }

        return redirect('admin.articles');
    }

    public function assignReviewer()
    {
        Article::assignReviewer($_POST['article_id'], $_POST['user_id']);

        return response(['status' => 'success'], 'json');
    }
}
