<?php

namespace App\Http\Controllers;

use Core\Controller;

class AboutController extends Controller
{
    public function about() {
        return view('about.twig');
    }
}
