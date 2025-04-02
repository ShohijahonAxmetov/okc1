<?php

namespace App\Http\Controllers;

use Image;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Banner;


class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::latest()
                            ->paginate(12);

        return view('app.banners.index', compact('banners'));
        // return response(['data' => $banners], 200);
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
            'link' => 'required|max:255',
            'img' => 'image|required|max:2048|mimes:jpeg,jpg,png',
            'is_active' => 'boolean|required'
        ]);
        if($validator->fails()) {
            return back()->with(['message' => $validator->errors()->first(), 'success' => false]);
        }
        if($request->hasFile('img')) {
            $img = $request->file('img');
            // $img_name = Str::random(12).'.'.$img->extension();
            $img_name = Str::random(12).'.webp';
            $saved_img = $img->move(public_path('/upload/banners'), $img_name);

            Image::make($saved_img)
                ->encode('webp')
                ->save(public_path() . '/upload/banners/' . $img_name, 60);

            $data['img'] = $img_name;
        }
        Banner::create($data);

        return back()->with(['message' => 'Успешно добавлен', 'success' => true], 200);
        // return response(['message' => 'Успешно добавлен'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $banner = Banner::find($id);
        return response(['data' => $banner], 200);
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
            'link' => 'required|max:255',
            'img' => 'image|nullable|max:2048|mimes:jpeg,jpg,png',
            'is_active' => 'boolean|required'
        ]);
        if($validator->fails()) {
            return back()->with(['message' => $validator->errors()->first(), 'success' => false]);
            // return response(['message' => $validator->errors()], 400);
        }
        if($request->hasFile('img')) {
            $img = $request->file('img');
            $img_name = Str::random(12).'.'.$img->extension();
            $saved_img = $img->move(public_path('/upload/banners'), $img_name);
            $data['img'] = $img_name;
        }
        Banner::find($id)->update($data);

        return back()->with(['message' => 'Успешно редактирован', 'success' => true]);
        // return response(['message' => 'Успешно редактирован'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $banner = Banner::find($id);
        if(!$banner) {
            return back()->with(['message' => 'Net takogo bannera', 'success' => false]);
            // return response(['message' => 'Net takogo kommentariya'], 400);
        }

        $imgName = DB::table('banners')->where('id', $id)->first()->img;
        unlink(public_path().'/upload/banners/'.$imgName);
        $banner->delete();
        return back()->with(['message' => 'Successfully deleted', 'success' => true]);
        // return response(['message' => 'Успешно удален'], 200);
    }
}
