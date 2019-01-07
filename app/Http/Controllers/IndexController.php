<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Core\Controller;
use Core\View;

class IndexController extends Controller
{
    public function indexZeroPage($query = '')
    {
        return $this->index(0, $query);
    }

    public function index(int $page = 0, $query = '')
    {
        if (!empty($query)) {
            $this->data['query'] = $query;
            $this->data['articles'] = Article::paginateSearchPublished('%' . $query . '%', getenv('ARTICLES_PER_PAGE'), $page);
        } else {
            $this->data['articles'] = Article::paginatePublished(getenv('ARTICLES_PER_PAGE'), $page);
        }

        $this->data['currentPage'] = $page;
        $this->data['count'] = Article::countPublished();
        $this->data['lastPage'] = ceil(max(0, $this->data['count'] / getenv('ARTICLES_PER_PAGE') - 1));

        return new View('index.twig', $this->data);
    }
}
