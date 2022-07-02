<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Mail;
use App\Models\SpecialOfferClient;
use Illuminate\Http\Request;

class SpecialOfferClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = SpecialOfferClient::find($id);
        if(!$item) {
            return response(['message' => 'Net takoy email']);
        }

        $item->delete();
        return response(['message' => 'Uspeshno udalen'], 200);
    }

    public function all() {
        $users = SpecialOfferClient::latest()->get();
        return response(['data' => $users], 200);
    }

    public function send(Request $request) {
        $emails = SpecialOfferClient::latest()->pluck('email')->toArray();
        if(!isset($emails[0])) {
            return response(['message' => 'Net podpisannix'], 400);
        }

        $data = $request->all();
        if(!isset($data['emails'])) {
            foreach($emails as $email) {
                Mail::send([], [], function($message) use ($data, $email) {
                    $message->to($email)
                        ->subject($data['theme'])
                        ->setBody($data['message']);
                    $message->from('info@okc.uz','OKC.uz');
                });
            }
        }

        $validator = Validator::make($data, [
            'theme' => 'required',
            'message' => 'required'
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        $emails = SpecialOfferClient::whereIn('id', [$data['emails']])->pluck('email')->toArray();

        foreach($emails as $email) {
            Mail::send([], [], function($message) use ($data, $email) {
                $message->to($email)
                    ->subject($data['theme'])
                    ->setBody($data['message']);
                $message->from('info@okc.uz','OKC.uz');
            });
        }

        return response(['success' => true], 200);
    }
}
