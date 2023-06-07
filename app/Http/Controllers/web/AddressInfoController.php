<?php

namespace App\Http\Controllers\web;

use App\Models\AddressInfo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddressInfoController extends Controller
{
    public function get()
    {
        return response([
            'addresses' => AddressInfo::all()
        ]);
    }
}
