<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function login_form()
    {
        return view('app.auth.login');
    }
    public function login(Request $request)
    {
        // $credentials = request(['username', 'password']);

        // if (! $token = auth()->guard('admin')->attempt($credentials)) {
        //     return response()->json(['message' => 'Неправильные данные'], 400);
        // }

        // return $this->respondWithToken($token);

        $request->validate([
            'username' => ['required'],
            'password' => ['required']
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];
 
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
 
            return redirect()->intended('dashboard');
        }
 
        return back()->with(['message' => 'User not found!', 'success' => false]);
    }

    public function me()
    {
        $user = auth()->guard('admin')->user();
        return response($user);
    }

    public function logout()
    {
        // auth()->guard('admin')->logout();
        // return response()->json(['message' => 'Successfully logged out'], 200);

        auth()->guard('web')->logout();
        return redirect()->route('login');
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->guard('admin')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token
        ]);
    }
}
