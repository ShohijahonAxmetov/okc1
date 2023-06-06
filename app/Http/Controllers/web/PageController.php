<?php

namespace App\Http\Controllers\web;

use App\Models\Page;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function get()
    {
        return response([
            'pages' => Page::with('images')->get()
        ]);
    }
}
