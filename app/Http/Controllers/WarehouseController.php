<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function all()
    {
        $warehouses = Warehouse::all();

        return response(['success' => true, 'warehouses' => $warehouses], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = 0)
    {
        $is_empty = true ? $id == 0 : false;

        if (!$is_empty) {
            $products = Warehouse::find('venkon_id', $id)
                ->productVariations()
                ->paginate(12);
        } else {
            $products = Warehouse::orderBy('id', 'desc')
                ->get()
                ->first()
                ->productVariations()
                ->orderBy('product_variation_warehouse.remainder', 'desc')
                ->paginate(12);
        }

        $warehouses = Warehouse::orderBy('id', 'desc')
            ->get();

        return view('app.warehouses.index', compact(
            'products',
            'warehouses'
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $warehouse = Warehouse::with('productVariations')
            ->find($id);

        return view('app.warehouses.show', [
            'success' => true,
            'warehouse' => $warehouse
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function edit(Warehouse $warehouse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warehouse $warehouse)
    {
        //
    }
}
