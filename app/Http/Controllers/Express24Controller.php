<?php

namespace App\Http\Controllers;

use App\Traits\Express24;
use App\Models\Warehouse;
use App\Models\ProductVariation;
use App\Models\Category;
use App\Models\Product;
use App\Models\ExpressConfig;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
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
	protected $addAdditionalWarehouseId = 9125;

	public function __construct()
	{
		$this->token = $this->getToken();
		$this->baseUrl = $this->getBaeUrl();
	}

    public function categories()
    {
    	Log::channel('express24')->info('START-'.date('d-m-Y'));

    	$successSentCategoryIds = $this->getSuccessfullySentCategoryIds();

        $successSentSubCategoryIds = $this->getSubCategories($successSentCategoryIds);

        $this->getProducts($successSentSubCategoryIds);
        // $this->getProducts();
		
    	return ['result' => 'success'];
    }

    /**
    sozdat v express24 vse glavnie kategorii kotorie net na danniy moment
    inache obnovit dannie kategorii
    (poka sdelat vse novie kategorii status=false)

    dlya sub kategoriev ta je sistema
    **/
    public function getSuccessfullySentCategoryIds(): array // 100% done
    {
    	$allMainCategories = Category::where([
    		['is_active', 1],
    		['parent_id', null]
		])
    		->get();

		foreach ($allMainCategories as $category) {

            $res = Http::withToken($this->token)
                    ->post($this->baseUrl.'/categories', [
                        'externalID' => $category->integration_id,
                        'name' => $category->title['ru'],
                        'isActive' => true,
                        'sort' => 9998
                    ]);

            if ($res->status() != 200) Log::channel('express24')->info($res->json());

            if (isset($res->json()['message']) && $res->json()['message'] == 'A category with the provided name already exists.') {
                $res = Http::withToken($this->token)
                    ->patch($this->baseUrl.'/categories/'.$category->express24_id, [
                        'externalID' => $category->integration_id,
                        'name' => $category->title['ru'],
                        'isActive' => true,
                        'sort' => 9997
                    ]);

                if ($res->status() != 200) Log::channel('express24')->info($res->json());
            }

            if (isset($res->json()['id'])) $category->update([
                'express24_id' => $res->json()['id']
            ]);
		}

        return $allMainCategories->pluck('integration_id')->toArray();
    }

    public function getSubCategories(array $mainCategoryIds): array // 100% done
    {
    	$categories = Category::where(function ($query) use ($mainCategoryIds) {
    		$query->where('is_active', 1)
    			->whereIn('parent_id', $mainCategoryIds);
    	})
    		->get();

        // Log::channel('express24')->info('------------- SYNC SUB CATEGORIES AT '.date('Y-m-d H:i').'--------------');
        foreach ($categories as $category) {
            $res = Http::withToken($this->token)
                    ->post($this->baseUrl.'/categories/'.$category->parent->express24_id.'/subs', [
                        'externalID' => $category->integration_id,
                        'name' => $category->title['ru'],
                        'isActive' => true,
                        'sort' => 9998
                    ]);

            // if ($res->json()['message'] == 'To create a subcategory, please convert other parent categories to subcategories first.') dd($this->baseUrl.'/categories/'.$category->parent->express24_id.'/subs', [
            //             'externalID' => $category->integration_id,
            //             'name' => $category->title['ru'],
            //             'isActive' => true,
            //             'sort' => 9998
            //         ]);

            if ($res->status() != 200) Log::channel('express24')->info($res->json());

            if (isset($res->json()['message']) && $res->json()['message'] == 'A sub category with the provided name already exists.') {
                $res = Http::withToken($this->token)
                    ->patch($this->baseUrl.'/categories/'.$category->parent->express24_id.'/subs/'.$category->express24_id, [
                        'externalID' => $category->integration_id,
                        'name' => $category->title['ru'],
                        'isActive' => true,
                        'sort' => 9997
                    ]);
                // dd($res->json(), $category->title['ru']);
                if ($res->status() != 200) Log::channel('express24')->info($res->json());
            }

            if (isset($res->json()['id'])) $category->update([
                'express24_id' => $res->json()['id']
            ]);
        }

        return $categories->pluck('integration_id')->toArray();
    }

    public function getProducts(array $categoriesIds)
    {
        $expressConfig = ExpressConfig::query()
            ->latest()
            ->first();
    	// dd($categoriesIds);

		$productsIds = DB::table('category_product')
			->whereIn('category_id', $categoriesIds)
			->get()
			->pluck('product_id')
			->toArray();



		// dd($productsIds);

		$productVariations = ProductVariation::where(function ($query) use ($productsIds, $expressConfig) {
			$query->whereNotNull('spic_id')
				->whereNotNull('package_code')
				->where('package_code', '!=', 0)
				->whereIn('product_id', $productsIds)
				->where('remainder', '>', $expressConfig->products_min_count)
				->where('is_active', 1);
				// ->whereHas('product', function ($productQuery) {
				// 	$productQuery->whereHas('categories', function ($categoriesQuery) {
				// 		$categoriesQuery->whereNotNull('parent_id');
				// 	});
				// });
		})
			->get();

        $inActiveProductVariations = ProductVariation::where(function ($query) use ($productsIds) {
            $query->whereNotNull('spic_id')
                ->whereNotNull('package_code')
                ->where('package_code', '!=', 0)
                ->whereIn('product_id', $productsIds)
                ->where('is_active', 0);
                // ->whereHas('product', function ($productQuery) {
                //  $productQuery->whereHas('categories', function ($categoriesQuery) {
                //      $categoriesQuery->whereNotNull('parent_id');
                //  });
                // });
        })
            ->get();

        // dd($inActiveProductVariations);

        foreach ($productVariations as $productVariation) {
            $this->sendProduct2Express($productVariation, true);
        }

        foreach ($inActiveProductVariations as $inActiveProductVariation) {
            $this->sendProduct2Express($inActiveProductVariation, false);
        }
    }

    function sendProduct2Express(ProductVariation $productVariation, $isActive): void
    {
    	$expressConfig = ExpressConfig::query()
    		->latest()
    		->first();

    	// dd($productVariation->product->categories->where('express24_id', '!=', null)->last()->express24_id);
    	$data = [
    		'externalID' => $productVariation->integration_id,
    		'name' => $productVariation->product->title['ru'],
    		'description' => $productVariation->product->desc['ru'] ?? '-',
    		'price' => $productVariation->price + (($expressConfig->price_up/100)*$productVariation->price),
    		'categoryID' => $productVariation->product->categories->whereNotNull('express24_id')->last()->express24_id,
    		'fiscalization' => [
    			'spicID' => $productVariation->spic_id,
    			'packageCode' => preg_replace("/[^0-9]/", "", $productVariation->package_code),
    		],
    		'vat' => $expressConfig->vat,
		];

        // dd($productVariation->product->categories->whereNotNull('express24_id'));

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
				'id' => intval($warehouse->express24_id),
				'externalID' => $warehouse->integration_id,
				'isActive' => $isActive,
				'isAvailable' => $isActive,
				'qty' => $productVariation->remainder
			];

			$counter ++;
		}
		$data = $this->addAdditionalWarehouse($data);
		// dd($data);

		unset($counter);

        // dd($data);

		$res = Http::withToken($this->token)
            ->post($this->baseUrl.'/products', $data);

        // dd($res->json());

        if (isset($res->json()['message']) && $res->json()['message'] == 'Product with this external ID is already exists') {
            $res = Http::withToken($this->token)
                ->patch($this->baseUrl.'/products/'.$productVariation->express24_id, $data);


            // dd($res->json());
            // dd(json_encode($res->json()), json_encode($data));
        }

        if (isset($res->json()['id'])) {
        	$productVariation->update([
            	'express24_id' => $res->json()['id']
            ]);
        }

        // dd(json_encode($res->json()), json_encode($data));
        
        if ($res->status() != 200) {
            Log::channel('express24')->info('-------------begin debug-------------------');
            Log::channel('express24')->info($res);
            Log::channel('express24')->info($data);
            Log::channel('express24')->info('-------------begin end-------------------');
        }
    }

    function addAdditionalWarehouse(array $data): array
    {
    	$data['attachedBranches'][] = [
			'id' => $this->addAdditionalWarehouseId,
			'externalID' => '-',
			'isActive' => false,
			'isAvailable' => false,
			'qty' => 0
		];

		return $data;
    }

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

    public function toConfigPage()
    {
    	$config = ExpressConfig::latest()
    		->first();

    	return view('app.integrations.express24.config.index', compact('config'));
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

    public function updateConfig(Request $request)
    {
    	$request->validate([
    		'vat' => 'required|integer|max:12',
    		'price_up' => 'required|integer',
            'products_min_count' => 'required|integer|min:1',
    	]);

    	$config = ExpressConfig::latest()
    		->first();

		$config->update([
			'vat' => $request->input('vat'),
			'price_up' => $request->input('price_up'),
            'products_min_count' => $request->input('products_min_count'),
		]);

		return back()->with([
			'success' => 1
		]);
    }

    public function updateTest()
    {
        // $categories = $this->getAllCategories(0,0,1);

        // foreach ($categories as $category) {
        //     $fullUrl = $this->getBaeUrl().'/categories/'.$category['id'].'/subs';

        //     $subs = Http::withToken($this->getToken())
        //         ->get($fullUrl); // , ['externalID' => $externalID]
        //     $subs = $subs->json();

        //     foreach ($subs as $sub) {
        //         $externalID = '1-'.Str::uuid()->toString();

        //         $res = Http::withToken($this->getToken())
        //             ->patch($this->getBaeUrl().'/categories/'.$category['id'].'/subs/'.$sub['id'], ['externalID' => $externalID]);

        //         // if (!$res->ok()) dd($res->json());
        //     }

        // }
    }
}
