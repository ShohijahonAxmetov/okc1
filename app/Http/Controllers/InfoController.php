<?php

namespace App\Http\Controllers;

use App\Models\Info;
use DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    protected $languages = ['ru', 'uz'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Info::find(1);

        return view('app.infos.index', [
            'data' => $data,
            'languages' => $this->languages
        ]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Info  $info
     * @return \Illuminate\Http\Response
     */
    public function show(Info $info)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Info  $info
     * @return \Illuminate\Http\Response
     */
    public function edit(Info $info)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Info  $info
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Info $info)
    {
        $request->validate([
            'title' => 'required|max:255',
            'facebook' => 'nullable|max:255',
        ]);
        $data = $request->all();

        if(!$info) return back()->with([
            'message' => 'Info not found'
        ]);

        $info->update([
            'meta_title' => [
                'ru' => $data['meta_title_ru'],
                'uz' => $data['meta_title_uz']
            ],
            'meta_desc' => [
                'ru' => $data['meta_desc_ru'],
                'uz' => $data['meta_desc_uz']
            ],
            'meta_keywords' => [
                'ru' => $data['meta_keywords_ru'],
                'uz' => $data['meta_keywords_uz']
            ],
            'title' => $data['title'],
            'facebook' => $data['facebook'],
            'telegram' => $data['telegram'],
            'instagram' => $data['instagram'],
            'youtube' => $data['youtube'],
            'phone_number' => $data['phone_number'],
            'dop_phone_number' => $data['dop_phone_number'],
            'email' => $data['email'],
        ]);

        return back()->with([
            'success' => true,
            'message' => 'Успешно сохранен'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Info  $info
     * @return \Illuminate\Http\Response
     */
    public function destroy(Info $info)
    {
        //
    }
}
