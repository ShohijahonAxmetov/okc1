<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\ProductVariationImage;
use App\Services\TelegramBot;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Storage;
use Image;
use File;
use DB;

class TestController extends Controller
{
    protected TelegramBot $bot;

    function __construct(TelegramBot $bot)
    {
        $this->bot = $bot;
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
