<?php

namespace App\Http\Controllers\Yandex;

use App\Models\Yandex\MarketCategory;
use App\Models\{Category, Product, ProductVariation};

use Illuminate\Http\Request;
use Http, DB, Log, DateTimeZone, DateTime;
use App\Http\Controllers\Controller;

class YandexMarketController extends Controller
{
    protected $baseUrl;
    protected $token;
    protected $mainCategoryId;

    protected $businessId = 195958885;
    protected $campaignId = 142064197;
    protected $minProductCount = 10;
    protected $percent4price = 25; // {0-100}

    public function __construct()
    {
        $this->baseUrl = config('yandex_market.base_url');
        $this->token = config('yandex_market.token');
        $this->mainCategoryId = config('yandex_market.main_category_id');
    }

    function formatProducts4Market($language): array
    {
        $variationIds = DB::table('product_variation_characteristic')
            ->select('product_variation_id')
            ->pluck('product_variation_id')
            ->unique()
            ->toArray();
        $products = ProductVariation::whereIn('id', $variationIds)
            ->where(function($q) {
                $q->where('remainder', '>', $this->minProductCount)
                    ->where('price', '>', 0)
                    ->where('height', '>', 0)
                    ->where('length', '>', 0)
                    ->where('weight', '>', 0)
                    ->where('width', '>', 0);
            })
            ->get();
        foreach($products as $product) {
            $product->market_category_id = DB::table('category_market_category')
                ->whereIn('category_id', $product->product->categories->where('is_active', 1)->pluck('id')->toArray())
                ->first()
                ->market_category_id;
            $product->characteristics = DB::table('product_variation_characteristic')
                ->where('product_variation_id', $product->id)
                ->get();
        }

        $formatted = [];
        foreach($products as $key => $product) {
            $formatted[$key] = [
                'offer' => [
                    'offerId' => $product->id,
                    'basicPrice' => [
                        'currencyId' => 'UZS',
                        'value' => round($product->price*(1+$this->percent4price/100))
                    ],
                    'commodityCodes' => [
                        ['code' => $product->spic_id, 'type' => 'IKPU_CODE']
                    ],
                    'description' => $product->product->desc[$language] ?? '',
                    'marketCategoryId' => $product->market_category_id,
                    'name' => $product->product->title[$language] ?? '',
                    'parameterValues' => $this->formatChars4Market($product),
                    'pictures' => $product->productVariationImages->pluck('real_img')->toArray(),
                    'vendor' => $product->product->brand->title,
                    'weightDimensions' => [
                        'height' => $product->height ?? 0,
                        'length' => $product->length ?? 0,
                        'weight' => $product->weight ? $product->weight/1000 : 0,
                        'width' => $product->width ?? 0,
                    ]
                ]
            ];

            if ($product->warranty_period) $formatted[$key]['offer']['guaranteePeriod'] = [
                'timePeriod' => $product->warranty_period,
                'timeUnit' => 'DAY',
                'comment' => $product->warranty_period_comment,
            ];

            if ($product->service_life) $formatted[$key]['offer']['lifeTime'] = [
                'timePeriod' => $product->service_life,
                'timeUnit' => 'DAY',
                'comment' => $product->service_life_comment,
            ];

            if ($product->expiration_date) $formatted[$key]['offer']['shelfLife'] = [
                'timePeriod' => $product->expiration_date,
                'timeUnit' => 'DAY',
                'comment' => $product->expiration_date_comment,
            ];
        }

        return $formatted;
    }

    function formatChars4Market($product): array
    {
        $chars = [];
        foreach($product->characteristics as $char) {
            $characteristic = DB::table('product_characteristics')
                ->where('market_characteristic_id', $char->characteristic_id)
                ->first();
            if ($characteristic->type == 'NUMERIC' && $char->value) $char->value = preg_replace('/\D/', '', $char->value);
            $add = true;
            $temp = ['parameterId' => $char->characteristic_id];
            if ($char->value) $temp['value'] = $char->value;
            else if ($char->select_value) $temp['valueId'] = $char->select_value;
            else $add = false;
            if ($add) $chars[] = $temp;
            unset($temp, $add);
        }

        return $chars;
    }

    public function sendProducts2Market(string $language = 'ru') //['ru', 'uz']
    {
        $formattedProducts = $this->formatProducts4Market($language);

        $url = $this->baseUrl.'/businesses/'.$this->businessId.'/offer-mappings/update?language='.strtoupper($language);
        $body = [
            'offerMappings' => $formattedProducts
        ];

        $res = Http::withHeaders(['Api-Key' => $this->token])->post($url, $body);

        if (isset($res->json()['status']) && $res->json()['status'] == 'ERROR') {
            dd($res->json(), $res->status());
            foreach($res->json()['results'] as $product) {
                foreach($product['errors'] as $err) {
                    if ($err['type'] == 'UNKNOWN_PARAMETER') {
                        $tes = DB::table('product_variation_characteristic')
                            ->where(function ($query) use ($product, $err) {
                                $query->where('product_variation_id', $product['offerId'])
                                    ->where('characteristic_id', $err['parameterId']);
                            })
                            ->first();
                        Log::channel('market')
                            ->info('Deleted object: '.json_encode($tes));
                        DB::table('product_variation_characteristic')
                            ->where(function ($query) use ($product, $err) {
                                $query->where('product_variation_id', $product['offerId'])
                                    ->where('characteristic_id', $err['parameterId']);
                            })
                            ->delete();
                    }
                }
            }
        }

        Log::channel('market')
            ->info('Response: '.json_encode($res->json()));

        if (!$res->ok()) Http::get('https://api.telegram.org/bot'.config('telegram_bot.dev_bot_token').'/sendMessage?parse_mode=HTML&chat_id=-1002617372536&text='.json_encode($res->json()));
        else Http::get('https://api.telegram.org/bot'.config('telegram_bot.dev_bot_token').'/sendMessage?parse_mode=HTML&chat_id=-1002617372536&text='.'Успешно обновлено - '.__FUNCTION__);
    }

    public function sendUzProducts2Market()
    {
        $language = 'uz';

        $formattedProducts = $this->formatProducts4Market($language);

        $url = $this->baseUrl.'/businesses/'.$this->businessId.'/offer-mappings/update?language='.strtoupper($language);
        $body = [
            'offerMappings' => $formattedProducts
        ];

        $res = Http::withHeaders(['Api-Key' => $this->token])->post($url, $body);

        if (isset($res->json()['status']) && $res->json()['status'] == 'ERROR') {
            foreach($res->json()['results'] as $product) {
                foreach($product['errors'] as $err) {
                    if ($err['type'] == 'UNKNOWN_PARAMETER') {
                        $tes = DB::table('product_variation_characteristic')
                            ->where(function ($query) use ($product, $err) {
                                $query->where('product_variation_id', $product['offerId'])
                                    ->where('characteristic_id', $err['parameterId']);
                            })
                            ->first();
                        Log::channel('market')
                            ->info('Deleted object: '.json_encode($tes));
                        DB::table('product_variation_characteristic')
                            ->where(function ($query) use ($product, $err) {
                                $query->where('product_variation_id', $product['offerId'])
                                    ->where('characteristic_id', $err['parameterId']);
                            })
                            ->delete();
                    }
                }
            }
        }

        Log::channel('market')
            ->info('Response: '.json_encode($res->json()));

        if (!$res->ok()) Http::get('https://api.telegram.org/bot'.config('telegram_bot.dev_bot_token').'/sendMessage?parse_mode=HTML&chat_id=-1002617372536&text='.json_encode($res->json()));
        else Http::get('https://api.telegram.org/bot'.config('telegram_bot.dev_bot_token').'/sendMessage?parse_mode=HTML&chat_id=-1002617372536&text='.'Успешно обновлено - '.__FUNCTION__);
    }

    public function sendRemainds2Market()
    {
        $url = $this->baseUrl.'/campaigns/'.$this->campaignId.'/offers/stocks';
        $body = [
            'skus' => $this->formatRemainders2Market()
        ];

        $res = Http::withHeaders(['Api-Key' => $this->token])->put($url, $body);

        Log::channel('market')
            ->info('Response from send remainders: '.json_encode($res->json()));

        // Удалить неактивных товаров, если такие имеется
        $this->deleteFromYaMarket();

        if (!$res->ok()) Http::get('https://api.telegram.org/bot'.config('telegram_bot.dev_bot_token').'/sendMessage?parse_mode=HTML&chat_id=-1002617372536&text='.json_encode($res->json()));
        else Http::get('https://api.telegram.org/bot'.config('telegram_bot.dev_bot_token').'/sendMessage?parse_mode=HTML&chat_id=-1002617372536&text='.'Успешно обновлено - '.__FUNCTION__);
    }

    function formatRemainders2Market(): array
    {
        $variationIds = DB::table('product_variation_characteristic')
            ->select('product_variation_id')
            ->pluck('product_variation_id')
            ->unique()
            ->toArray();
        $products = ProductVariation::whereIn('id', $variationIds)
            ->where('remainder', '>', $this->minProductCount)
            ->get();

        $body = [];
        
        foreach($products as $product) {
            $body[] = [
                'sku' => $product->id,
                'items' => [
                    [
                        'count' => intval($product->remainder),
                        'updatedAt' => (new DateTime('now', new DateTimeZone('Asia/Tashkent')))->format('Y-m-d\TH:i:sP'),
                    ]
                ]
            ];
        }

        return $body;
    }

    function deleteFromYaMarket()
    {
        $currentYaMarketProducts = $this->getProductsInfo();
        $currentYaMarketProductsIds = $this->getOnlyProductIds($currentYaMarketProducts);

        $formattedProducts = $this->formatProducts4Market('ru');
        $formattedProductsIds = array_map(function($product) {
            return $product['offer']['offerId'];
        }, $formattedProducts);

        $deleteProductsIds = array_diff($currentYaMarketProductsIds, $formattedProductsIds);
        if (!empty($deleteProductsIds)) {
            $res = $this->deleteProducts(array_values($deleteProductsIds));
            if (!$res->ok()) Log::channel('market')->info('Не смогли удалить неактивных товаров: '.json_encode($res->json()));
        }
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

    public function getCategories(): void
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
    function saveCategories(array $categories, ?int $parentId = null): void
    {
        foreach ($categories as $category) {
            DB::table('market_categories')->updateOrInsert(
                ['integration_id' => $category['id']],
                ['name' => $category['name'], 'parent_integration_id' => $parentId, 'updated_at' => date('Y-m-d H:i:s')],
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

    // Информация о товарах в каталоге
    public function getProductsInfo()
    {
        $url = $this->baseUrl.'/businesses/'.$this->businessId.'/offer-mappings?lang=RU&limit=200';
        $body = [
            'archived' => false
        ];

        $res = Http::withHeaders(['Api-Key' => $this->token])->post($url, $body);

        return $res;
    }

    // Удаление товаров из каталога
    public function deleteProducts(array $productsIds)
    {
        $url = $this->baseUrl.'/businesses/'.$this->businessId.'/offer-mappings/delete';
        $body = [
            'offerIds' => $productsIds
        ];

        $res = Http::withHeaders(['Api-Key' => $this->token])->post($url, $body);

        return $res;
    }

    // Возвращает только id из результата функции getProductsInfo()
    function getOnlyProductIds($res)
    {
        if (!$res->ok()) return false;

        $products = $res->json()['result']['offerMappings'];
        $result = array_map(function ($product) {
            return $product['offer']['offerId'];
        }, $products);

        return $result;
    }

    public function notification(Request $request)
    {
        $res = Http::get('https://api.telegram.org/bot'.config('telegram_bot.dev_bot_token').'/sendMessage?parse_mode=HTML&chat_id=-1002617372536&text='.json_encode($request->all()));

        Log::info($res->json());

        Log::channel('market')
            ->info('Notification: '.json_encode($request->all()));

        return response([
            "version" => "1.0.0",
            "name" => "Уведомления для ТГ",
            "time" => (new DateTime('now', new DateTimeZone('Asia/Tashkent')))->format('Y-m-d\TH:i:sP')
        ]);
    }

    public function getCategoryChildren(int $categoryIntegrationId): ?\Illuminate\Database\Eloquent\Collection
    {
        $category = MarketCategory::query()
            ->where('integration_id', $categoryIntegrationId)
            ->firstOrFail();

        return isset($category->children[0]) ? $category->children : null;
    }
}
