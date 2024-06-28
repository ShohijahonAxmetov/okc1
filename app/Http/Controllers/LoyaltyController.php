<?php

namespace App\Http\Controllers;

use App\Models\bots\loyalty_card\OrderLog;
use App\Http\Requests\LoyaltyOrderRequest;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    public function order(LoyaltyOrderRequest $request)
    {
        OrderLog::create($request->validated());

        return response([
            'success' => 1
        ]);
    }
}
