<?php

namespace App\Http\Controllers\Yandex;

use App\Models\Yandex\MarketCategory;
use App\Models\Category;

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

    // code => message
    function errorList()
    {
        return [
            'INVALID_CATEGORY' => 'Category is not a leaf.',
        ];
    }
}
