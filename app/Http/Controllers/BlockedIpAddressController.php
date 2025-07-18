<?php

namespace App\Http\Controllers;

use App\Models\BlockedIpAddress;
use Illuminate\Http\Request;

class BlockedIpAddressController extends Controller
{
    public function index()
    {
        $data = BlockedIpAddress::query()
            ->latest()
            ->paginate(24);

        return view('app.blocked_ip_addresses.index', compact('data'));
    }

    public function destroy(Request $request, int $id)
    {
        BlockedIpAddress::findOrFail($id)->delete();

        return back()->with(['success' => 1, 'message' => 'Успешно удалено']);
    }
}
