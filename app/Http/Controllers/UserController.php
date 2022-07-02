<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('orders', 'comments')->paginate(24);
        return response(['data' => $users], 200);
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
        $user = User::with('orders')->find($id);
        return response(['data' => $user], 200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $user = User::find($id);
            $user->orders()->delete();
            $user->delete();

            DB::commit();

            return response(['message' => 'Успешно удален'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            throw($e);
            return response(['message' => 'Ошибка'], 400);
        }
    }

    public function delete_img($id) {
        $user = User::find($id);
        $data = $user->toArray();
        $data['img'] = null;
        $user->update($data);
        return response(['message' => 'Картинка успешна удалена'], 200);
    }
}
