<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Brand;
use App\Models\ProductVariation;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function remainders()
    {
        $variations = ProductVariation::with('warehouses', 'product')
            ->whereHas('product', function ($q) {
                $q->where('is_active', 1);
            })
            ->latest();
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $variations = $variations->whereHas('product', function($q) {
                $q->where('id', 'like', '%' . trim($_GET['search']) . '%');
            })
                ->orWhereHas('product', function($q) {
                    $q->where('title', 'like', '%' . $_GET['search'] . '%');
                })
                ->orWhereHas('product', function($q) {
                    $q->where(DB::raw('JSON_EXTRACT(LOWER(title), "$.ru")'), 'like', '%' . trim($_GET['search']) . '%');
                });
        }
        if (isset($_GET['brand']) && $_GET['brand'] != '') {
            $variations = $variations->whereHas('product.brand', function ($q) {
                $q->where('integration_id', $_GET['brand']);
            });
        }
        $variations = $variations->paginate(24);

        $warehouses = Warehouse::latest()
            ->where('is_active', 1)
            ->has('productVariations')
            ->get();
        $brands = Brand::latest()
            ->where('is_active', 1)
            ->has('products', '>', 0)
            ->get();

        $brand = isset($_GET['brand']) ? $_GET['brand'] : '';
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        return view('app.warehouses.remainder', compact(
            'variations',
            'warehouses',
            'brands',
            'brand',
            'search'
        ));
    }

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
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            $id = 0;
        }
        $is_empty = true ? $id == 0 : false;
        $warehouse = Warehouse::orderBy('id', 'desc')->first();
        if (!$is_empty) {
            if ($warehouse && $warehouse->productVariations()->exists()) {
                $products = Warehouse::where('integration_id', $id)
                    ->first()
                    ->productVariations()
                    ->orderBy('product_variation_warehouse.remainder', 'desc')
                    ->paginate(12);
            } else {
                $products = [];
            }
        } else {
            if ($warehouse && $warehouse->productVariations()->exists()) {
                $products = Warehouse::orderBy('id', 'desc')
                    ->first()
                    ->productVariations()
                    ->orderBy('product_variation_warehouse.remainder', 'desc')
                    ->paginate(12);
            } else {
                $products = [];
            }
        }

        $warehouses = Warehouse::where('is_active', 1)
            ->orderBy('id', 'desc')
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
        $warehouses = Warehouse::orderBy('id', 'desc')
            ->get();
        $products = Warehouse::where('integration_id', $id)
            ->first()
            ->productVariations()
            ->orderBy('product_variation_warehouse.remainder', 'desc')
            ->paginate(24);

        return view('app.warehouses.show', compact(
            'warehouses',
            'products',
            'id'
        ));
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
    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::find($id);

        $warehouse->update([
            'is_store' => $request->is_store
        ]);

        return back()->with([
            'success' => true,
            'message' => 'Успешно сохранен'
        ]);
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

    public function select_for_fargo($id)
    {
        $warehouse = Warehouse::find($id);

        $other_warehouses = Warehouse::where('id', '!=', $id)
            ->get();

        DB::beginTransaction();
        try {

            $warehouse->update([
                'for_fargo' => true
            ]);

            foreach($other_warehouses as $item) {
                $item->update([
                    'for_fargo' => false
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

        return back()->with([
            'success' => true,
            'message' => 'Успешно обновлено'
        ]);
    }
}
