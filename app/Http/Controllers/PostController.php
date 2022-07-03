<?php

namespace App\Http\Controllers;

use Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::latest()->paginate(24);
        return response(['data' => $posts], 200);
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
            'title' => 'required',
            'img' => 'image|nullable|max:2048'
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }
        if($request->hasFile('img')) {
            $img = $request->file('img');
            $img_name = Str::random(12).'.'.$img->extension();
            $saved_img = $img->move(public_path('/upload/posts'), $img_name);
            Image::make($saved_img)->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path().'/upload/posts/200/'.$img_name, 60);
            Image::make($saved_img)->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path().'/upload/posts/600/'.$img_name, 80);
            $data['img'] = $img_name;
        }
        $data['title'] = json_decode($data['title']);
        $data['subtitle'] = json_decode($data['subtitle']);
        $data['desc'] = json_decode($data['desc']);
        Post::create($data);

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
        $post = Post::find($id);
        return response(['data' => $post], 200);
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
            'img' => 'image|nullable|max:2048'
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }
        if($request->hasFile('img')) {
            $img = $request->file('img');
            $img_name = Str::random(12).'.'.$img->extension();
            $saved_img = $img->move(public_path('/upload/posts'), $img_name);
            Image::make($saved_img)->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path().'/upload/posts/200/'.$img_name, 60);
            Image::make($saved_img)->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path().'/upload/posts/600/'.$img_name, 80);
            $data['img'] = $img_name;
        }
        $data['title'] = json_decode($data['title']);
        isset($data['subtitle']) ? $data['subtitle'] = json_decode($data['subtitle']) : null;
        isset($data['desc']) ? $data['desc'] = json_decode($data['desc']) : null;
        Post::find($id)->update($data);

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
        Post::find($id)->delete();
        return response(['message' => 'Успешно удален'], 200);
    }
}
