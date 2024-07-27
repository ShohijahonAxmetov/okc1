<?php

namespace App\Http\Controllers;

use App\Traits\Express24;
use App\Models\Warehouse;
use App\Models\ProductVariation;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class Express24Controller extends Controller
{
	use Express24 {
		updateCategory as traitUpdateCategory;
		updateBranch as traitUpdateBranch;
		getProducts as traitGetProducts;
		updateProduct as traitUpdateProduct;
	}

	protected $token = null;
	protected $baseUrl = null;

	public function __construct()
	{
		$this->token = env('EXPRESS24_TOKEN');
		$this->baseUrl = env('EXPRESS24_BASE_URL');
	}

    public function categories()
    {
    	// dd(array_column($this->getAllCategories(1, 1, 1), 'externalID'));
    	$successSentCategoryIds = $this->getSuccessfullySentCategoryIds();

        $successSentSubCategoryIds = $this->getSubCategories($successSentCategoryIds);
        
        dd($this->getProducts(array_column($this->getAllCategories(1, 1, 1), 'externalID')));
		
    	return $successSentSubCategoryIds;
    }

    public function getSuccessfullySentCategoryIds(): array
    {
    	$allMainCategories = Category::where([
    		['is_active', 1],
    		['parent_id', null]
		])
    		->get();
		$successSentCategoryIds = array();

		Log::channel('express24')->info('------------- SYNC MAIN CATEGORIES AT '.date('Y-m-d H:i').'--------------');
		foreach ($allMainCategories as $category) {

			if (!$category->express24_id) {
				$res = Http::withToken($this->token)
					->post($this->baseUrl.'/categories', [
						'externalID' => $category->integration_id,
						'name' => $category->title['ru'],
						'isActive' => 0,
						'sort' => 9998
					]);

				if (!$res->successful()) Log::channel('express24')->info($res.'. Category name: {category}', ['category' => $category->title['ru']]);
				else $successSentCategoryIds[] = $category->integration_id;

				if (isset($res->json()['id'])) $category->update([
					'express24_id' => $res->json()['id']
				]);
			} else {
				$res = Http::withToken($this->token)
					->patch($this->baseUrl.'/categories/'.$category->express24_id, [
						'externalID' => $category->integration_id,
						'name' => $category->title['ru'],
						'isActive' => 0,
						'sort' => 9997
					]);

				if (!$res->successful()) Log::channel('express24')->info($res.'. Category name: {category}', ['category' => $category->title['ru']]);
				else $successSentCategoryIds[] = $category->integration_id;
			}
		}

		return $successSentCategoryIds;
    }

    public function getSubCategories(array $mainCategoryIds): array
    {
    	$categories = Category::where(function ($query) use ($mainCategoryIds) {
    		$query->where('is_active', 1)
    			->whereIn('parent_id', $mainCategoryIds);
    	})
    		->get();

        $successSentCategoryIds = array();

        Log::channel('express24')->info('------------- SYNC SUB CATEGORIES AT '.date('Y-m-d H:i').'--------------');
        foreach ($categories as $category) {

            if (!$category->express24_id) {
                $res = Http::withToken($this->token)
                    ->post($this->baseUrl.'/categories/'.$category->parent->express24_id.'/subs', [
                        'externalID' => $category->integration_id,
                        'name' => $category->title['ru'],
                        'isActive' => 0,
                        'sort' => 9998
                    ]);

                if (!$res->successful()) Log::channel('express24')->info($res.'. Category name: {category}', ['category' => $category->title['ru']]);
                else $successSentCategoryIds[] = $category->integration_id;

                if (isset($res->json()['id'])) $category->update([
                    'express24_id' => $res->json()['id']
                ]);
            } else {
                $res = Http::withToken($this->token)
                    ->patch($this->baseUrl.'/categories/'.$category->express24_id, [
                        'externalID' => $category->integration_id,
                        'name' => $category->title['ru'],
                        'isActive' => 0,
                        'sort' => 9997
                    ]);

                if (!$res->successful()) Log::channel('express24')->info($res.'. Category name: {category}', ['category' => $category->title['ru']]);
                else $successSentCategoryIds[] = $category->integration_id;
            }
        }

        return $successSentCategoryIds;
    }

    public function getProducts(array $categoriesIds)
    {
    	// $subCategories = Category::where(function ($query) {
    	// 	$query->whereNotNull('parent_id')
    	// 		->whereNotNull('express24_id');
    	// })
    	// 	->get();
dd($categoriesIds);
		$productsIds = DB::table('category_product')
			->whereIn('category_id', $categoriesIds)
			->get();
			// ->pluck('product_id')
			// ->toArray();

			dd($productsIds);

		$productVariations = ProductVariation::where(function ($query) use ($productsIds) {
			$query->whereNotNull('spic_id')
				->whereNotNull('package_code')
				->where('package_code', '!=', 0)
				->whereIn('product_id', $productsIds)
				->where('remainder', '>', 10)
				->where('is_active', 1);
		})
			->get();

			dd($productVariations);

		foreach ($productVariations as $productVariation) {
			$res = $this->sendProduct2Express($productVariation);

			dd($res);
		}
    }

    function sendProduct2Express(ProductVariation $productVariation): array
    {
    	// $this->updateCategoryIfNotActive($productVariation);
    	$data = [
    		'externalID' => $productVariation->integration_id,
    		'name' => $productVariation->product->title['ru'],
    		'description' => $productVariation->product->desc['ru'] ?? '-',
    		'price' => $productVariation->price,
    		'categoryID' => $productVariation->product->categories[0]->express24_id,
    		'fiscalization' => [
    			'spicID' => $productVariation->spic_id,
    			'packageCode' => preg_replace("/[^0-9]/", "", $productVariation->package_code),
    		],
    		'vat' => 12,
		];

		// put images
		$counter = 0;
		foreach ($productVariation->productVariationImages as $img) {
			if ($counter === 0) $data['images'][$counter] = [
				'url' => $img->real_img,
				'isPreview' => true
			];
			else $data['images'][$counter] = [
				'url' => $img->real_img,
				'isPreview' => false
			];

			$counter ++;
		}
		unset($counter);

		// put branches
		$warehouses = Warehouse::where(function ($query) {
			$query->whereNotNull('integration_id')
				->where('is_store', 1)
				->where('is_active', 1);
		})
			->get();

		$counter = 0;
		foreach ($warehouses as $warehouse) {
			$data['attachedBranches'][$counter] = [
				'id' => intval($warehouse->integration_id),
				'externalID' => intval($warehouse->integration_id),
				'isActive' => true,
				'isAvailable' => true,
				'qty' => $productVariation->remainder
			];

			$counter ++;
		}
		unset($counter);

		$res = Http::withToken($this->token)
            ->post($this->baseUrl.'/products', $data);

        return $res->json();
    }

    // function updateCategoryIfNotActive(ProductVariation $productVariation)
    // {
    // 	Http::withToken($this->token)
    //         ->patch($this->baseUrl.'/categories/'.$productVariation->product->categories[0]->express24_id, [
    //         	'isActive' => true
    //         ]);
    // }

    public function index()
    {
    	return view('app.integrations.express24.index');
    }

    // GET
    public function toCategoriesPage(Request $request)
    {
    	if ($request->input('id') !== null) $categories = $this->getSubs($request->input('id'));
    	else $categories = $this->getAllCategories();

    	return view('app.integrations.express24.categories.index', compact('categories'));
    }

    public function toBranchesPage()
    {
    	$branches = $this->getBranches();

    	return view('app.integrations.express24.branches.index', compact('branches'));
    }

    public function toProductsPage(Request $request)
    {
    	$products = $this->traitGetProducts($request->input('id'));

    	return view('app.integrations.express24.products.index', compact('products'));
    }

    // OTHER
    public function updateCategory(Request $request)
    {
    	$res = $this->traitUpdateCategory($request);

    	if ($res) return redirect()->route('integrations.express24.categories')->with([
			'success' => $res
		]);
    }

    public function updateBranch(Request $request)
    {
    	$res = $this->traitUpdateBranch($request);

    	if ($res) return redirect()->route('integrations.express24.branches')->with([
			'success' => $res
		]);
    }

    public function updateProduct(Request $request)
    {
    	$res = $this->traitUpdateProduct($request);

    	return redirect()->route('integrations.express24.products', ['id' => json_decode($request->input('info'))->subCategoryID])->with([
			'success' => $res
		]);
    }
}
