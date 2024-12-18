<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function loyalty(Request $request)
    {
    	$request->validate([
    		'phone_number' => 'required|min:12|max:12',
    		'birthdate' => 'required|date:Y-m-d',
    		'full_name' => 'required',
    		'sex' => 'required|in:male,female',
    	]);

    	// $url = 'http://'.env('C_IP').'/OKC/api_telegram/clients';
        $url = 'http://'.env('C_IP').'/UT_NewClean/hs/api_telegram/clients';
    	$data = [
    		'phone_number' => $request->input('phone_number'),
    		'birthdate' => $request->input('birthdate'),
    		'full_name' => $request->input('full_name'),
    		'sex' => $request->input('sex'),
    	];

    	// dd($url, json_encode($data));

    	$res = Http::withBasicAuth('Venkon', 'overlord')->post($url, $data);
    	return $res->body();
    }
}
