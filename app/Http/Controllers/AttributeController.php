<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Attribute;
use App\Models\AttributeOption;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attributes = Attribute::where('is_active', 1)->orderBy('id', 'desc')->with('attributeOptions')->paginate(24);
        return response(['data' => $attributes], 200);
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
            'title' => 'required|max:255',
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        DB::beginTransaction();

        try {
            $data['title'] = json_decode($data['title']);

            $attribute = Attribute::create($data);

            if($attribute && isset($data['options'])) {
                $data['options'] = json_decode($data['options'], true);
                foreach ($data['options'] as $item) {
                    $item['attribute_id'] = $attribute->id;

                    AttributeOption::create($item);
                }
            }

            DB::commit();

            return response(['message' => 'Успешно добавлен'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            throw($e);
            return response(['message' => 'Ошибка'], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attribute = Attribute::with('attributeOptions')->find($id);
        return response(['data' => $attribute], 200);
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
        // dd($data);
        $validator = Validator::make($data, [
            'title' => 'required|max:255',
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        DB::beginTransaction();

        try {
            $data['title'] = json_decode($data['title']);
            
            $attribute = Attribute::find($id)->update($data);
            $after_update = Attribute::find($id);

            if($attribute && isset($data['options'])) {
                $data['options'] = json_decode($data['options'], true);
                
                $item_ids = [];
                foreach ($data['options'] as $item) {
                    $item['attribute_id'] = $after_update->id;
                    if(isset($item['id'])) {
                        AttributeOption::find($item['id'])->update($item);
                    	$item_ids[] = $item['id']; 
                    } else {
                        $new_option = AttributeOption::create($item);
                        $item_ids[] = $new_option->id;
                    }
                }
                // delete option from attribute
                $after_update->attributeOptions()->whereNotIn('id', $item_ids)->delete();
            }

            DB::commit();

            return response(['message' => 'Успешно редактирован'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            throw($e);
            return response(['message' => 'Ошибка'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function all() {
        $attributes = Attribute::where('is_active', 1)->orderBy('id', 'desc')->with('attributeOptions')->get();
        return response(['data' => $attributes], 200);
    }

    public function disable($id) {
        $data['is_active'] = 0;
        $attribute = Attribute::find($id)->update($data);
        return response(['message' => 'Успешно редактирован'], 200);
    }
}
