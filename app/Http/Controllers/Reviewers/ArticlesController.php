<?php

namespace App\Http\Controllers\Reviewers;

use App\Models\Article;
use App\Models\Document;
use App\Models\Review;
use Core\Controller;

class ArticlesController extends Controller
{
    public function showArticles($page = 0)
    {
        $this->data['articles'] = Review::forUserPaginate($_SESSION['user']['id'], getenv('TABLE_ENTRIES_PER_PAGE'), $page);

        $this->data['currentPage'] = $page;
        $this->data['lastPage'] = ceil(max(0, Review::countForUser($_SESSION['user']['id']) / getenv('TABLE_ENTRIES_PER_PAGE') - 1));

        return view('reviewers/articles.twig', $this->data);
    }

    public function showArticleReviewForm($id)
    {
        $this->data['article'] = Article::getByReviewId($id);
        $this->data['article']['documents'] = Document::forArticle($id);

        if ($this->data['article']['author_id'] != $_SESSION['user']['id']) {
            return redirect('reviewer.articles');
        }

        return view('reviewers/article_review.twig', $this->data);
    }

    public function saveArticleReview($id)
    {
        // TODO: Implement validation
        if (true) {
            Review::save($id, $_POST['score_topic'], $_POST['score_content'], $_POST['score_readability']);

            return redirect('reviewer.articles');
        }
    }
}
