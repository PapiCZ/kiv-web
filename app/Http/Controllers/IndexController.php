<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\View;

class IndexController extends Controller
{
    public function index()
    {
        return new View('index.twig', ['name' => 'jop']);
    }
}
