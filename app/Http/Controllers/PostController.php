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
        $posts = Post::latest()
                        ->paginate(12);
        $languages = ['ru', 'uz'];

        return view('app.posts.index', compact(
            'posts',
            'languages'
        ));
        // return response(['data' => $posts], 200);
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
            'title_ru' => 'required',
            'img' => 'image|nullable|max:2048'
        ]);
        if($validator->fails()) {
            return back()->with([
                'success' => false,
                'message' => $validator->errors()
            ]);
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
        // $data['title'] = json_decode($data['title']);
        $data['title'] = json_decode(json_encode([
            'ru' => $data['title_ru'],
            'uz' => $data['title_uz']
        ]));
        $data['subtitle'] = json_decode(json_encode([
            'ru' => $data['subtitle_ru'],
            'uz' => $data['subtitle_uz']
        ]));
        $data['desc'] = json_decode(json_encode([
            'ru' => $data['desc_ru'],
            'uz' => $data['desc_uz']
        ]));
        $data['slug'] = Str::slug($data['title_ru']);
        Post::create($data);

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
            'title_ru' => 'required|max:255',
            'img' => 'image|nullable|max:2048'
        ]);
        if($validator->fails()) {
            return back()->with([
                'success' => false,
                'message' => $validator->errors()
            ]);
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

        // $data['title'] = json_decode($data['title']);
        $data['title'] = json_decode(json_encode([
            'ru' => $data['title_ru'],
            'uz' => $data['title_uz']
        ]));
        $data['subtitle'] = json_decode(json_encode([
            'ru' => $data['subtitle_ru'],
            'uz' => $data['subtitle_uz']
        ]));
        $data['desc'] = json_decode(json_encode([
            'ru' => $data['desc_ru'],
            'uz' => $data['desc_uz']
        ]));
        $data['slug'] = Str::slug($data['title_ru']);
        Post::find($id)->update($data);

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
        $post = Post::find($id);
        if(!$post) {
            return back()->with(['message' => 'Post not found', 'success' => false]);
            // return response(['message' => 'Net takogo kommentariya'], 400);
        }
        $post->delete();
        return back()->with(['message' => 'Successfully deleted', 'success' => true]);
        // return response(['message' => 'Успешно удален'], 200);
    }
}
