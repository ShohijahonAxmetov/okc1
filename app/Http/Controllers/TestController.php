<?php

namespace App\Http\Controllers;

use App\Models\{Brand, ProductVariationImage, ProductVariation, Product};
use App\Services\{TelegramBot, Yandex\Delivery, Google\Translate};
use Illuminate\Http\Request;
use Storage, Image, File, DB, Http;

class TestController extends Controller
{
    protected TelegramBot $bot;
    protected Delivery $yandexDelivery;
    protected Translate $translate;

    function __construct(TelegramBot $bot, Delivery $yandexDelivery, Translate $translate)
    {
        $this->bot = $bot;
        $this->yandexDelivery = $yandexDelivery;
        $this->translate = $translate;
    }

    public function translate()
    {
        dd(1);
        $products = Product::where('is_active', 1)
            ->whereNotNull('title->ru')
            ->whereNotNull('desc->ru')
            ->get();
            // dd($products);

        foreach($products as $product) {
            $title = $product->title;
            $desc = $product->desc;
            
            
            $res = $this->translate->ru2uz($title['ru']);
            if (!$res['success']) return $res['res'];
            $title['uz'] = $res['res'];

            $res = $this->translate->ru2uz(strip_tags($desc['ru']));
            if (!$res['success']) return $res['res'];
            $desc['uz'] = $res['res'];

            $product->update([
                'title' => $title,
                'desc' => $desc
            ]);
        }
        
        return 'tugadi';
    }

    public function delivery()
    {
        $res = $this->yandexDelivery->checkPrice([
            ['coordinates' => [69.250201, 41.329821]],
            ['coordinates' => [69.251864, 41.332093]],
            ['coordinates' => [69.241749, 41.322430]],
        ], [
            ['quantity' => 2]
        ]);

        dd($res->json());
    }

    public function loyalty(Request $request)
    {
    	$request->validate([
    		'phone_number' => 'required|min:12|max:12',
    		'birthdate' => 'required|date:Y-m-d',
    		'full_name' => 'required',
    		'sex' => 'required|in:male,female',
    	]);

    	// $url = 'http://'.env('C_IP').'/OKC/api_telegram/clients';
        $url = 'http://'.env('C_IP').'/UT_NewClean/hs/api_telegram/clients';
    	$data = [
    		'phone_number' => $request->input('phone_number'),
    		'birthdate' => $request->input('birthdate'),
    		'full_name' => $request->input('full_name'),
    		'sex' => $request->input('sex'),
    	];

    	// dd($url, json_encode($data));

    	$res = Http::withBasicAuth('Venkon', 'overlord')->post($url, $data);
    	return $res->body();
    }

    public function sendMessage()
    {
        $this->bot->sendMessage();
    }

    public function img2webp()
    {
        $productImages = DB::table('brands')
            ->whereNotNull('img')
            ->select('img')
            ->get()
            // ->take(1)
            ->pluck('img');

        $filteredFiles = $productImages->filter(function ($file) {
            return in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'png']);
        });
        // ->map(function ($file) {
        //     return pathinfo($file, PATHINFO_FILENAME);
        // });

        // dd($productImages, $filteredFiles, Storage::disk('public')->allFiles('upload/brands'));

        foreach($filteredFiles as $filteredFile) {
            $prImg = Brand::where('img', $filteredFile)->first();
            $prImg->update([
                'img' => pathinfo($filteredFile, PATHINFO_FILENAME).'.webp'
            ]);



            // dd($filteredFile, pathinfo($filteredFile, PATHINFO_FILENAME), $prImg);



            // $img_name = $filteredFile . '.webp';
            // if (File::exists(public_path().'/upload/brands/'.$filteredFile.'.webp')) continue;
            // $saved_img = File::exists(public_path().'/upload/brands/'.$filteredFile.'.jpg') ? File::get(public_path().'/upload/brands/'.$filteredFile.'.jpg') : File::get(public_path().'/upload/brands/'.$filteredFile.'.png');

            // // $saved_img = $img->move(public_path('/upload/products'), $img_name);
            // Image::make($saved_img)
            //     ->encode('webp')
            //     ->save(public_path() . '/upload/brands/' . $img_name, 60);
        }

        // dd($productImages);
    }
}
