<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\Document;
use App\Models\User;
use Core\Controller;
use Core\Database\Database;

class ArticlesController extends Controller
{
    public function showArticles($page = 0)
    {
        $this->data['articles'] = Article::paginate(getenv('TABLE_ENTRIES_PER_PAGE'), $page);

        foreach ($this->data['articles'] as $key => $article) {
            $this->data['articles'][$key]['score'] = Article::getRatingForArticle($article['id'])['total_score'];
        }

        $this->data['currentPage'] = $page;
        $this->data['lastPage'] = ceil(max(0, Article::count() / getenv('TABLE_ENTRIES_PER_PAGE') - 1));

        return view('admin/articles.twig', $this->data);
    }

    public function showArticleDetail($id)
    {
        $this->data['article'] = Article::get($id);
        $this->data['article']['documents'] = Document::forArticle($id);
        $this->data['users'] = User::reviewersNotAssignedToArticle($id);
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

        $successMessage = null;
        if ($_POST['action'] === 'delete') {
            $successMessage = 'Příspěvek byl úspěšně smazán';
        } elseif ($_POST['action'] === 'publish') {
            $successMessage = 'Příspěvek byl úspěšně publikován';
        } elseif ($_POST['action'] === 'return') {
            $successMessage = 'Příspěvek byl úspěšně vrácen k přepracování';
        }

        return redirect('admin.articles')->with(['__SUCCESS__' => $successMessage]);
    }

    public function assignReviewer()
    {
        Article::assignReviewer($_POST['article_id'], $_POST['user_id']);

        return response(['id' => Database::lastInsertId(), 'status' => 'success'], 'json');
    }
}
