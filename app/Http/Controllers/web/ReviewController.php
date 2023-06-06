<?php

namespace App\Http\Controllers\web;

use App\Models\Review;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function get()
    {
        return response([
            'pages' => Review::all()
        ]);
    }
}
