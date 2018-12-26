<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Core\Controller;
use Core\View;

class IndexController extends Controller
{
    public function index()
    {
        $this->data['articles'] = Article::all();

        return new View('index.twig', $this->data);
    }
}
