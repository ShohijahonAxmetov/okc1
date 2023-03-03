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
        $users = User::with('orders', 'comments')
                        ->paginate(12);

        return view('app.users.index', compact('users'));
        // return response(['data' => $users], 200);
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
        $user = User::with('orders')
                        ->find($id);

        return view('app.users.show', compact('user'));
        // return response(['data' => $user], 200);
    }

    public function show_orders($id)
    {
        $user = User::with('orders')
                        ->find($id);

        return view('app.users.show_orders', compact('user'));
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
            foreach($user->orders as $order) {
                $order->delete();
            }
            $user->delete();

            DB::commit();

            return back()->with(['success' => true,'message' => 'Successfully deleted']);
            // return response(['message' => 'Успешно удален'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            throw($e);
            return back()->with(['success' => false,'message' => 'Error']);
            // return response(['message' => 'Ошибка'], 400);
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
