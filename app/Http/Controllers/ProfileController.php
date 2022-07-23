<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

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
        if ($request->password != $request->password_confirmation) {
            return back()->with([
                'success' => false,
                'message' => 'Пароли не совпадают'
            ]);
        }

        $request->validate([
            'name' => 'required|max:255',
            'password' => 'required|confirmed|min:8|max:16',
            'avatar' => 'image|max:2048'
        ]);

        $user = auth()->user();
        $avatar = $user->img;

        if ($request->hasFile('avatar')) {
            $img = $request->file('avatar');
            $img_name = Str::random(12) . '.' . $img->extension();
            $saved_img = $img->move(public_path('/upload/admins'), $img_name);
            Image::make($saved_img)->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path() . '/upload/admins/200/' . $img_name, 60);

            $avatar = $img_name;
        }

        $user->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'img' => $avatar
        ]);

        return back()->with([
            'success' => true,
            'message' => 'Успешно редактирован'
        ]);;
    }
}
