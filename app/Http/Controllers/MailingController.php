<?php

namespace App\Http\Controllers;

use App\Models\SpecialOfferClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailingController extends Controller
{
    public function index()
    {
        $users = SpecialOfferClient::latest()
            ->get();

        return view('app.mailing.index', compact(
            'users'
        ));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required',
            'users' => 'required',
            'users.*' => 'integer'
        ]);

        foreach ($request->users as $user) {
            Mail::raw($request->message, function ($message) use ($request, $user) {
                $message->to($user->email)
                  ->subject($request->subject);
              });
        }

        return back()->with([
            'success' => true
        ]);
    }
}
