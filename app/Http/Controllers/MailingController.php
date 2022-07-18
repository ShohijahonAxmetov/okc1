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
            'subject' => 'required',
            'message' => 'required'
        ]);

        // if(!isset($request->users) && !isset($request->all_users)) {
        //     return back()->with([
        //         'success' => false
        //     ]);
        // }

        if (isset($request->all_users)) {
            $users = SpecialOfferClient::latest()
                ->get('email');

            foreach ($users as $user) {
                Mail::raw($request->message, function ($message) use ($request, $user) {
                    $message->to($user->email)
                        ->subject($request->subject);
                });
            }
        } else {
            foreach ($request->users as $user) {
                Mail::raw($request->message, function ($message) use ($request, $user) {
                    $message->to('licko37225021@gmail.com')
                        ->subject($request->subject);
                });
            }
        }

        return back()->with([
            'success' => true
        ]);
    }
}
