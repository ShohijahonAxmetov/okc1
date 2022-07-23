<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();

        return view('app.profile', compact(
            'user'
        ));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        dd($user);
    }
}
