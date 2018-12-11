<?php

namespace App\Http\Controllers;

use Core\View;

class IndexController
{
    public function index($ahoj, $cus)
    {
//        echo('tady je index' . $ahoj . $cus);

        return new View('index.twig', ['name' => 'jop']);
    }
}
