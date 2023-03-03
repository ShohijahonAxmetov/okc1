<?php

namespace App\Http\Controllers;

use App\Models\SpecialOfferClient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Traits\Playmobile;

class MailingController extends Controller
{
    use Playmobile;

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

        if(!isset($request->users) && !isset($request->all_users)) {
            return back()->with([
                'success' => false,
                'message' => 'Users not selected'
            ]);
        }

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
                    $message->to($user)
                        ->subject($request->subject);
                });
            }
        }

        return back()->with([
            'success' => true
        ]);
    }

    public function sms_index()
    {
        $users = User::select('id', 'phone_number', 'name')
            ->get();

        return view('app.mailing.sms.index', compact(
            'users'
        ));
    }

    public function sms_send(Request $request)
    {
        $request->validate([
            'message' => 'required'
        ]);

        if(!isset($request->users) && !isset($request->type)) {
            return back()->withInput()->with([
                'success' => false,
                'message' => 'Users not selected'
            ]);
        }

        if (isset($request->type)) {
            switch ($request->type) {
                case 'all':
                    $phone_numbers = User::pluck('phone_number')
                        ->toArray();
                    break;

                case 'female':
                    $phone_numbers = User::where('sex', 'female')
                        ->pluck('phone_number')
                        ->toArray();
                    break;

                case 'male':
                    $phone_numbers = User::where('sex', 'male')
                        ->pluck('phone_number')
                        ->toArray();
                    break;
            }

        } else {
            $phone_numbers = $request->users;
        }

        $this->mailing($phone_numbers, $request->message);

        return back()->with([
            'success' => true
        ]);
    }
}
