<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Admin;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $logs = Log::latest()
            ->where('admin_id', '!=', 1);

        if(isset($_GET['user_id']) && $_GET['user_id'] != '') {
            $logs = $logs->where('admin_id', $_GET['user_id']);
        }

        $logs = $logs->paginate(24);
        $users = Admin::where('role', 'content')
            ->latest()    
            ->get();

        $user_id = $_GET['user_id'] ?? '';

        return view('app.logs.index', compact(
            'logs',
            'users',
            'user_id'
        ));
    }

    public function destroy($id)
    {
        Log::find($id)
            ->delete();

        return back()->with([
            'success' => true,
            'message' => 'Успешно удален'
        ]);
    }
}
