<?php

namespace App\Http\Controllers\Reviewers;

use App\Models\Article;
use App\Models\Review;
use Core\Controller;

class ArticlesController extends Controller
{
    public function showArticles()
    {
        $this->data['articles'] = Review::forUser($_SESSION['user']['id']);

        return view('reviewers/articles.twig', $this->data);
    }

    public function showArticleReviewForm($id)
    {
        $this->data['article'] = Article::getByReviewId($id);

        return view('reviewers/article_review.twig', $this->data);
    }

    public function saveArticleReview($id)
    {
        // TODO: Implement validation
        if (true) {
            Review::save($id, $_POST['score_topic'], $_POST['score_content'], $_POST['score_readability']);
            
            return redirect('reviewers.articles');
        }
    }
}
