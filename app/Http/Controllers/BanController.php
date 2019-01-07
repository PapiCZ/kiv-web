<?php

namespace App\Http\Controllers;

use Core\Controller;

class BanController extends Controller
{
    public function showBanned()
    {
        return view('banned.twig');
    }
}
