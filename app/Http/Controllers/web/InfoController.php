<?php

namespace App\Http\Controllers\web;

use App\Models\Info;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function get()
    {
        return response([
            'info' => Info::find(1)
        ]);
    }
}
