<?php

namespace App\Http\Controllers\Yandex;

use App\Models\Yandex\MarketCategory;
use App\Models\Category;
use App\Models\Product;

use Illuminate\Http\Request;
use Http, DB;
use App\Http\Controllers\Controller;

class YandexMarketController extends Controller
{
    protected $baseUrl;
    protected $token;
    protected $mainCategoryId;

    public function __construct()
    {
        $this->baseUrl = config('yandex_market.base_url');
        $this->token = config('yandex_market.token');
        $this->mainCategoryId = config('yandex_market.main_category_id');
    }

    public function index()
    {
        return view('app.integrations.yandex_market.index');
    }

    public function categories(Request $request)
    {

        $categories = MarketCategory::where('parent_integration_id', $request->input('id'))
            ->get();
        
        // if (!$res->ok()) {
        //     foreach ($res->json()['errors'] as $error) {
        //         if (in_array('INVALID_CATEGORY', $error)) {
        //             $categories[] = ['id' => $error];
        //         }
        //     }
        // }


        return view('app.integrations.yandex_market.categories.index', [
            'categories' => $categories,
            'ourCategories' => Category::all()
        ]);
    }

    public function products(Request $request)
    {
        $products = Category::findOrFail($request->input('category'))
            ->products()
            ->where('is_active', 1)
            ->whereHas('productVariations', function($q) {
            	$q->where(function ($qI) {
            		$qI->where('is_active', 1)
            			->where('remainder', '>', 10);
            	});
            })
            ->with('productVariations', 'categories')
            ->get();

        $marketCategory = DB::table('category_market_category')
        	->where('category_id', $request->input('category'))
        	->first();
    	if (!isset($marketCategory)) return back()->with(['success' => false, 'message' => 'Категория Маркета не найдена!']);

        $characteristics = DB::table('product_characteristics')
    		->where('market_category_id', $marketCategory->market_category_id)
        	->get();

        $productVariationId = [];
        foreach($products as $product) {
            $productVariationId[] = $product->productVariations[0]->id;
        }
        $characteristicValues = DB::table('product_variation_characteristic')
            ->whereIn('product_variation_id', $productVariationId)
            ->get();

        foreach($products as $product) {
            $product->characteristics = $characteristicValues->where('product_variation_id', $product->productVariations[0]->id);
        }
        // dd($products);

        return view('app.integrations.yandex_market.products.index', [
            'products' => $products,
            'characteristics' => $characteristics,
            'characteristicValues' => $characteristicValues,
        ]);
    }

    public function productCharacteristics(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'id' => 'required|integer'
        ]);

        foreach($request->except('_token', 'id') as $requestKey => $requestValue) {
            DB::table('product_variation_characteristic')
                ->updateOrInsert(
                    ['product_variation_id' => $request->input('id'), 'characteristic_id' => $requestKey],
                    [
                        'select_value' => isset($requestValue['select']) ? $requestValue['select'] : null,
                        'value' => is_array($requestValue) ? $requestValue['input'] : $requestValue
                    ]
                );
        }

        return back()->withInput()->with([
            'success' => true,
            'message' => 'Успешно сохранено'
        ]);
    }

    public function pinCategory(Request $request)
    {
        DB::table('category_market_category')->updateOrInsert([
            'market_category_id' => $request->input('id'),
        ],[
            'category_id' => $request->input('our_category_id'),
        ]);

        return back()->with([
            'success' => true,
            'message' => '123'
        ]);
    }

    public function getCategories()
    {
        $res = Http::withHeaders(['Api-Key' => $this->token])->send('POST', $this->baseUrl.'/categories/tree');

        $category = $res->json()['result']['children'][1];
        $this->saveCategories([$category]);
    }

    /**
     * 
     * for getCategories
     * 
     **/
    function saveCategories(array $categories, ?int $parentId = null)
    {
        foreach ($categories as $category) {
            DB::table('market_categories')->updateOrInsert(
                ['integration_id' => $category['id']],
                ['name' => $category['name'], 'parent_integration_id' => $parentId]
            );

            if (isset($category['children']) && is_array($category['children'])) {
                $this->saveCategories($category['children'], $category['id']);
            }
        }
    }

    public function getParameters()
    {
        $categories = DB::table('category_market_category')
            ->select('market_category_id', 'category_id')
            ->get();

        foreach($categories as $parameter) {

	        $res = Http::withHeaders(['Api-Key' => $this->token])->send('POST', $this->baseUrl.'/category/'.$parameter->market_category_id.'/parameters');

	        foreach($res->json()['result']['parameters'] as $resParameter) {

	            DB::table('product_characteristics')
	                ->updateOrInsert(
	                    [
	                        'market_characteristic_id' => $resParameter['id'],
	                        'market_category_id' => $res->json()['result']['categoryId'],
	                    ],
	                    [
	                        'name' => $resParameter['name'],
	                        'type' => $resParameter['type'],
	                        'description' => $resParameter['description'],
	                        'required' => $resParameter['required'],
	                        'filtering' => $resParameter['filtering'],
	                        'distinctive' => $resParameter['distinctive'],
	                        'multivalue' => $resParameter['multivalue'],
	                        'allowCustomValues' => $resParameter['allowCustomValues'],
	                        'values' => isset($resParameter['values']) ? (strlen(json_encode($resParameter['values'])) > 65000 ? null : json_encode($resParameter['values'])) : null,
	                        'constraints' => isset($resParameter['constraints']) ? json_encode($resParameter['constraints']) : null,
	                    ]
	                );
	        }
	    }
    }

    // code => message
    function errorList()
    {
        return [
            'INVALID_CATEGORY' => 'Category is not a leaf.',
        ];
    }
}
