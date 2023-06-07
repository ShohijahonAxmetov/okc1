<?php

namespace App\Http\Controllers;

use App\Models\AddressInfo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AddressInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $addresses = AddressInfo::latest()
                        ->paginate(12);
        $languages = ['ru', 'uz'];

        return view('app.addresses.index', compact(
            'addresses',
            'languages'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'address_ru' => 'required',
            'iframe' => 'required',
        ]);
        if($validator->fails()) {
            return back()->with([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }

        $data['address'] = [
            'ru' => $data['address_ru'],
            'uz' => $data['address_uz']
        ];
        AddressInfo::create($data);

        return back()->with([
            'message' => 'Успешно добавлен',
            'success' => true
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AddressInfo  $addressInfo
     * @return \Illuminate\Http\Response
     */
    public function show(AddressInfo $addressInfo)
    {
        return response([
            'data' => $addressInfo
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AddressInfo  $addressInfo
     * @return \Illuminate\Http\Response
     */
    public function edit(AddressInfo $addressInfo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AddressInfo  $addressInfo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $addressInfo = AddressInfo::find($id);

        $data = $request->all();

        $validator = Validator::make($data, [
            'address_ru' => 'required',
            'iframe' => 'required',
        ]);
        if($validator->fails()) {
            return back()->with([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }

        $data['address'] = [
            'ru' => $data['address_ru'],
            'uz' => $data['address_uz']
        ];
        $addressInfo->update($data);

        return back()->with([
            'message' => 'Успешно редактирован',
            'success' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AddressInfo  $addressInfo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $addressInfo = AddressInfo::find($id);
        if(!$addressInfo) {
            return back()->with([
                'message' => 'Address not found',
                'success' => false
            ]);
        }
        $addressInfo->delete();
        return back()->with([
            'message' => 'Successfully deleted',
            'success' => true
        ]);
    }
}
