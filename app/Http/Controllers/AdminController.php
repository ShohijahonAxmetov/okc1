<?php

namespace App\Http\Controllers;

use Image;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Admin;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = Admin::latest()
                        ->where('role', '!=', 'admin')
                        ->paginate(12);
        
        return view('app.admins.index', compact(
            'admins'
        ));
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
        $data = $request->all();

        $validator = Validator::make($data, [
            'username' => 'unique:App\Models\Admin,username|required|max:255',
            'password' => 'required|max:255|min:8|confirmed',
            // 'role' => 'in:admin,content,operator|required',
            'img' => 'image|max:2048|nullable'
        ]);
        if($validator->fails()) {
            return back()->with([
                'success' => false,
                'message' => 'Passwords do not match'
            ]);
            // return response(['message' => $validator->errors()], 400);
        }
        if($request->hasFile('img')) {
            $img = $request->file('img');
            $img_name = Str::random(12).'.'.$img->extension();
            $saved_img = $img->move(public_path('/upload/admins'), $img_name);
            Image::make($saved_img)->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path().'/upload/admins/200/'.$img_name, 60);
            $data['img'] = $img_name;
        }
        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'content';
        Admin::create($data);

        return back()->with(['success' => true]);
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
        $user = Admin::with('logs')
            ->find($id);

        return view('app.admins.show', compact(
            'user'
        ));
        // return response(['data' => $user], 200);
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
            'username' => 'unique:App\Models\Admin,username,'.$id.'|required|max:255',
            // 'role' => 'in:admin,content,operator|required'
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        $admin = Admin::find($id);

        if(isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            $data['password'] = $admin->password;
        }
        if($request->hasFile('img')) {
            $img = $request->file('img');
            $img_name = Str::random(12).'.'.$img->extension();
            $saved_img = $img->move(public_path('/upload/admins'), $img_name);
            Image::make($saved_img)->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path().'/upload/admins/200/'.$img_name, 60);
            $data['img'] = $img_name;
        }
        $data['role'] = 'content';
        $admin->update($data);

        return back()->with(['success' => true]);
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
        Admin::find($id)->delete();

        return back()->with(['success' => true]);
        // return response(['message' => 'Успешно удален'], 200);
    }

    public function delete_img($id)
    {
        $user = Admin::find($id);
        $data = $user->toArray();
        $data['img'] = null;
        $user->update($data);
        return response(['message' => 'Картинка успешна удалена'], 200);
    }
}
