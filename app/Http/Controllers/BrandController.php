<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::latest()
                        ->with('products');
        if(isset($_GET['search'])) {
            $brands = $brands->where('id', $_GET['search'])
                ->orWhere('title', 'like', '%'.$_GET['search'].'%');
        }
        $brands = $brands->paginate(12);

        $show_count = $brands->count();
        $all_brands_count = Brand::count();
        $languages = ['ru', 'uz'];

        $search = $_GET['search'] ?? '';



        return view('app.brands.index', compact(
            'brands',
            'languages',
            'show_count',
            'all_brands_count',
            'search'
        )); 
        // return response(['data' => $brands], 200);
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
            'title' => 'required|max:255',
            'img' => 'image|nullable|max:2048',
            'position' => 'integer|nullable'
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        if($request->hasFile('img')) {
            $img = $request->file('img');
            $img_name = Str::random(12).'.'.$img->extension();
            $saved_img = $img->move(public_path('/upload/brands'), $img_name);
            $data['img'] = $img_name;
        }
        $data['desc'] = json_decode($data['desc']);
        
        Brand::create($data);

        return response(['message' => 'Успешно добавлен', 'success' => true], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand = Brand::with('products')
                        ->find($id);

        return response([
            'data' => $brand
        ], 200);
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
            'title' => 'required|max:255',
            'img' => 'image|nullable|max:2048',
            'position' => 'integer|nullable'
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        if($request->hasFile('img')) {
            $img = $request->file('img');
            $img_name = Str::random(12).'.'.$img->extension();
            $saved_img = $img->move(public_path('/upload/brands'), $img_name);
            $data['img'] = $img_name;
        }
        isset($data['desc']) ? $data['desc'] = json_decode($data['desc']) : $data['desc'] = null;
        $data['desc'] = json_decode(json_encode([
            'ru' => $data['desc_ru'],
            'uz' => $data['desc_uz']
        ]));
        Brand::find($id)->update($data);

        return back()->with(['message' => 'Успешно редактирован', 'success' => true]);
        // return response(['message' => 'Успешно редактирован', 'success' => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // DB::beginTransaction();

        // try {
        //     $brand = Brand::find($id);
        //     foreach ($brand->products as $product) {
        //         foreach($product->productVariations as $variation) {
        //             $variation->productVariationImages()->delete();
        //         }
        //         $product->productVariations()->delete();
        //     }
        //     $brand->products()->delete();
        //     $brand->delete();

        //     DB::commit();

        //     return response(['message' => 'Успешно удален'], 200);
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     throw($e);
        //     return response(['message' => 'Ошибка'], 400);
        // }

        $brand = Brand::find($id);

        if(is_null($brand)) {

            return response([
                'success' => false
            ], 400);

        }

        $brand->update([
            'is_active' => 0
        ]);

        
        return response(['message' => 'Успешно удален', 'success' => true], 200);
    }

    public function delete_img($id)
    {
        $brand = Brand::find($id);
        $data = $brand->toArray();
        $data['img'] = null;
        $brand->update($data);

        return response(['message' => 'Картинка успешна удалена'], 200);
    }

    public function all()
    {
        $brands = Brand::all();

        return response([
            'data' => $brands
        ], 200);
    }

    public function upload_from()
    {
    	// $url = 'http://213.230.65.189/Invema_Test/hs/invema_API/brands';
    	// $url_auth = ['Venkon', 'overlord'];
    	
    	// $client = new \GuzzleHttp\Client();
    	// $res = $client->get($url, ['auth' =>  $url_auth]);
    	// $resp = (string) $res->getBody();
    	// $resp_toArray = json_decode($resp, true);
    	
    	// if($resp_toArray['success']) {
    	//     $brands = $resp_toArray['brands'];
    	    
    	//     foreach($brands as $item) {
    	//     	Brand::updateOrCreate([
    	//     	    'venkon_id' => $item['id']
    	//     	], [
    	// 	    'title' => $item['name'],
    	// 	    'link' => $item['link'],
     //            'is_active' => 1
    	//     	]);
    	//     }
    	// } else {
	    //    return response(['message' => 'Ошибка со стороны сервера'], 400);    
    	// }
    	
    	
    	// return response(['message' => 'Успешна загружен'], 200);
    }
}
