<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Document;
use Core\Controller;

class ArticleController extends Controller
{
    public function showArticle($id)
    {
        $this->data['article'] = Article::get($id);
        $this->data['article']['documents'] = Document::forArticle($id);
        $this->data['prev_article'] = Article::previous($id);
        $this->data['next_article'] = Article::next($id);

        return view('article_detail.twig', $this->data);
    }
}
