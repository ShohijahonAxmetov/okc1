<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Discount;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discounts = Discount::latest()->paginate(24);
        return response(['data' => $discounts], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'discount_type' => 'required|in:order,brand,product,category',
            'amount_type' => 'required|in:percent,fixed',
            'discount' => 'required|max:255'
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }
        
        Discount::create($data);

        return response(['message' => 'Успешно добавлен', 'success' => true], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $discount = Discount::find($id);
        if(!$discount) {
            return response(['message' => 'Takoy skidki ne sushestvuyet'], 400);
        }
        return response(['data' => $discount], 200);
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
        $data = $request->all();

        $validator = Validator::make($data, [
            'discount_type' => 'required|in:order,brand,product,category',
            'amount_type' => 'required|in:percent,fixed',
            'discount' => 'required|max:255'
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }
        
        $discount = Discount::find($id);
        if(!$discount) {
            return response(['message' => 'Takoy skidki ne sushestvuyet'], 400);
        }
        $discount->update($data);

        return response(['message' => 'Успешно редактирован', 'success' => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $discount = Discount::find($id);
        if(!$discount) {
            return response(['message' => 'Takoy skidki ne sushestvuyet'], 400);
        }
        $discount->delete();
        return response(['message' => 'Успешно удален', 'success' => true], 200);
    }

    public function all() {
        $discounts = Discount::latest()->get();
        return response(['data' => $discounts], 200);
    }
}
