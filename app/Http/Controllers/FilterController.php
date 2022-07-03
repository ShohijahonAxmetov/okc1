<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Filter;

class FilterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filters = Filter::orderBy('id', 'desc')->with('attribute')->paginate(24);
        return response(['data' => $filters], 200);
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

        $data['title'] = json_decode($data['title']);

        Filter::create($data);

        return response(['message' => 'Успешно добавлен'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $filter = Filter::with('attribute')->find($id);
        return response(['data' => $filter], 200);
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
            'title' => 'required|max:255',
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        $data['title'] = json_decode($data['title']);

        Filter::find($id)->update($data);

        return response(['message' => 'Успешно редактирован'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Filter::find($id)->delete();
        return response(['message' => 'Успешно удален'], 200);
    }

    public function all() {
    	$filters = Filter::orderBy('id', 'desc')->get();
    	return response(['data' => $filters], 200);
    }
}
