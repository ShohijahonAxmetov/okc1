<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::latest()
                                ->with('products', 'filters', 'children', 'parent')
                                ->paginate(12);

        $languages = ['ru', 'uz'];
        $all_categories = Category::all();

        return view('app.categories.index', compact('categories', 'languages', 'all_categories'));
        // return response(['data' => $categories], 200);
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
            'img' => 'max:1024|image|nullable',
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        DB::beginTransaction();

        try {
            if($request->hasFile('img')) {
                $img = $request->file('img');
                $img_name = Str::random(12).'.'.$img->extension();
                $saved_img = $img->move(public_path('/upload/categories'), $img_name);
                $data['img'] = $img_name;
            }
            $data['title'] = json_decode($data['title']);
            $data['desc'] = json_decode($data['desc']);
            $data['meta_keywords'] = json_decode($data['meta_keywords']);
            $data['meta_desc'] = json_decode($data['meta_desc']);
            $data['filters'] = json_decode($data['filters']);

            $category = Category::create($data);

            if ($category && isset($data['filters'])) {
                $category->filters()->sync($data['filters']);
            }

            DB::commit();

            return response(['message' => 'Успешно добавлен'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            throw($e);
            return response(['message' => $e], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::with('filters', 'products', 'children')->find($id);
        return response(['data' => $category], 200);
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
            // 'title' => 'required|max:255',
            'title_ru' => 'required|max:255',
            'img' => 'max:1024|image|nullable',
        ]);
        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        DB::beginTransaction();

        try {
            if($request->hasFile('img')) {
                $img = $request->file('img');
                $img_name = Str::random(12).'.'.$img->extension();
                $saved_img = $img->move(public_path('/upload/categories'), $img_name);
                $data['img'] = $img_name;
            }
            // $data['title'] = json_decode($data['title']);
            $data['title'] = json_decode(json_encode([
                'ru' => $data['title_ru'],
                'uz' => $data['title_uz']
            ]));
            // $data['desc'] = json_decode($data['desc']);
            $data['desc'] = json_decode(json_encode([
                'ru' => $data['desc_ru'],
                'uz' => $data['desc_uz']
            ]));
            // $data['meta_keywords'] = json_decode($data['meta_keywords']);
            $data['meta_keywords'] = json_decode(json_encode([
                'ru' => $data['meta_keywords_ru'],
                'uz' => $data['meta_keywords_uz']
            ]));
            // $data['meta_desc'] = json_decode($data['meta_desc']);
            $data['meta_desc'] = json_decode(json_encode([
                'ru' => $data['meta_desc_ru'],
                'uz' => $data['meta_desc_uz']
            ]));
            // $data['filters'] = json_decode($data['filters']);

            $category = Category::find($id)->update($data);

            // if ($category && isset($data['filters'])) {
            //     Category::find($id)->filters()->sync($data['filters']);
            // }

            DB::commit();

            return back()->with(['message' => 'Успешно редактирован', 'success' => true]);
            // return response(['message' => 'Успешно редактирован'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            throw($e);
            return response(['message' => $e], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //faqat wu categoryga tegishli productlar ochishi kerak
        // product bowqa kategoriyaga ham tegishli b6lsa ochmidi
        $category = Category::find($id);
        if(!$category) {
            return response([
                'message' => 'Net takoy kategorii'
            ], 400);
        }
        $category->update([
            'is_active' => 0
        ]);

        return response([
            'message' => 'Успешна удалена'
        ], 200);
    }

    public function delete_img($id) {
        $category = Category::find($id);
        $data = $category->toArray();
        $data['img'] = null;
        $category->update($data);

        return response(['message' => 'Картинка успешна удалена'], 200);
    }

    public function all() {
        $categories = Category::latest()
                                ->with('parent', 'filters')
                                ->get();

        return response([
            'data' => $categories
        ], 200);
    }
    
    public function upload_from()
    {
    	$url = 'http://213.230.65.189/Invema_Test/hs/invema_API/categories';
    	$url_auth = ['Venkon', 'overlord'];
    	
    	$client = new \GuzzleHttp\Client();
    	$res = $client->get($url, ['auth' =>  $url_auth]);
    	$resp = (string) $res->getBody();
    	$resp_toArray = json_decode($resp, true);
    	
    	if($resp_toArray['success']) {
    	    $categories = $resp_toArray['categories'];
    	    
    	    foreach($categories as $item) {
    	    	Category::updateOrCreate([
    	    	    'venkon_id' => $item['id']
    	    	], [
    		    'title' => json_decode($this->withLang($item['title'])),
    		    'parent_category_id' => $item['parent_category_id'],
    		    'slug' => Str::slug($item['title'], '-')
    	    	]);
    	    }
    	} else {
	    return response(['message' => 'Ошибка со стороны сервера'], 400);    
    	}
    	
    	
    	return response(['message' => 'Успешна загружен'], 200);
    }
    
    public function withLang($data)
    {
        return json_encode([
            'ru' => $data,
            'uz' => ''
        ]);
    }
}
