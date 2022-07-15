<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::latest()
            ->with('product', 'user')
            ->get();

        return view('app.comments.index', compact(
            'comments'
        ));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $comment->update([
            'answer' => $request->answer
        ]);

        return back()->with([
            'success' => true,
            'message' => 'Saved'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return back()->with(['message' => 'Net takoy kommentarii', 'success' => false]);
            // return response(['message' => 'Net takogo kommentariya'], 400);
        }
        $comment->delete();
        return back()->with(['message' => 'Uspeshno udalen', 'success' => true]);
        // return response(['message' => 'Успешно удален'], 200);
    }
}
