<?php

namespace App\Http\Controllers\Admin;

use App\Models\Review;
use Core\Controller;

class ReviewsController extends Controller
{
    public function delete()
    {
        Review::delete($_POST['id']);

        return response(['status' => 'success'], 'json');
    }
}
