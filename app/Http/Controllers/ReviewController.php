<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews = Review::latest()
                        ->paginate(12);
        $languages = ['ru', 'uz'];

        return view('app.reviews.index', compact(
            'reviews',
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
            'feedback_ru' => 'required',
            'name' => 'required|max:255',
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
            $saved_img = $img->move(public_path('/upload/reviews'), $img_name);
            Image::make($saved_img)->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path().'/upload/reviews/200/'.$img_name, 60);
            Image::make($saved_img)->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path().'/upload/reviews/600/'.$img_name, 80);
            $data['img'] = $img_name;
        }

        $data['feedback'] = [
            'ru' => $data['feedback_ru'],
            'uz' => $data['feedback_uz']
        ];
        $data['position'] = json_decode(json_encode([
            'ru' => $data['position_ru'],
            'uz' => $data['position_uz']
        ]));
        $data['for_search'] = $data['feedback_ru'];
        Review::create($data);

        return back()->with([
            'message' => 'Успешно добавлен',
            'success' => true
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        // $post = Post::find($id);
        return response([
            'data' => $review
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'feedback_ru' => 'required',
            'name' => 'required|max:255',
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
            $saved_img = $img->move(public_path('/upload/reviews'), $img_name);
            Image::make($saved_img)->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path().'/upload/reviews/200/'.$img_name, 60);
            Image::make($saved_img)->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path().'/upload/reviews/600/'.$img_name, 80);
            $data['img'] = $img_name;
        }

        $data['feedback'] = [
            'ru' => $data['feedback_ru'],
            'uz' => $data['feedback_uz']
        ];
        $data['position'] = json_decode(json_encode([
            'ru' => $data['position_ru'],
            'uz' => $data['position_uz']
        ]));
        $data['for_search'] = $data['feedback_ru'];
        $review->update($data);

        return back()->with([
            'message' => 'Успешно редактирован',
            'success' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        if(!$review) {
            return back()->with([
                'message' => 'Review not found',
                'success' => false
            ]);
        }
        $review->delete();
        return back()->with([
            'message' => 'Successfully deleted',
            'success' => true
        ]);
    }
}
