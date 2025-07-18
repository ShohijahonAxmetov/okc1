<?php

namespace App\Http\Controllers\Yandex;

use App\Models\{OAuthClient, Warehouse, ProductVariation};
use App\Services\Yandex\{
    Eats,
    Classes\Eats\PriceItem,
    Classes\Eats\ErrorItem,
};
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class YandexEatsController extends Controller
{
    protected Eats $eatsService;

    public function __construct(Eats $eatsService)
    {
        $this->eatsService = $eatsService;
    }

    public function getToken(Request $request)
    {
        $data = $request->all();

        $client = OAuthClient::where('client_id', $data['client_id'])->first();

        if (!$client || $client->client_secret !== $data['client_secret']) {
            $error = new ErrorItem(400, 'Переданные параметры неверны');
            return response([$error->toArray()], $error->getCode());
        }

        $existing = DB::table('oauth_access_tokens')
            ->where('client_id', $client->id)
            ->where('expires_at', '>', now())
            ->first();

        if ($existing) {
            return response()->json([
                'access_token' => $existing->access_token,
                'expires_in' => Carbon::parse($existing->expires_at)->diffInSeconds(now())
            ]);
        }

        // Генерация access token
        $token = Str::random(64);

        // Храним токен
        DB::table('oauth_access_tokens')->insert([
            'access_token' => hash('sha256', $token),
            'client_id' => $client->id,
            'expires_at' => now()->addHour(),
            'created_at' => now(),
        ]);

        return response([
            'access_token' => $token,
            'expires_in' => 3600
        ]);
    }

    public function prices(string $placeId)
    {
        $placeId = '00000000011';

        $warehouse = Warehouse::query()
            ->where('integration_id', $placeId)
            ->first();

        $products = $warehouse->productVariations()->wherePivot('remainder', '>', 9)->get();

        $items = [];
        foreach($products as $product) {
            $priceItem = new PriceItem($product->integration_id, (float)$product->price, -1, $product->old_price);
            $items[] = $priceItem->toArray();
        }

        return response(['items' => $items]);
    }

    public function index()
    {
        return view('app.integrations.yandex_eats.index');
    }

    public function products(Request $request)
    {
        $warehouses = Warehouse::query()
            ->where([['is_store', '=', 1], ['is_active', '=', 1], ['integration_id', '!=', null]])
            ->get();

        if ($request->input('warehouse') !== false && $request->input('warehouse') != '') {
            $place = Warehouse::query()
                ->where([['integration_id', '=', $request->input('warehouse')], ['is_store', '=', 1], ['is_active', '=', 1], ['integration_id', '!=', null]])
                ->first();
        } else {
            $place = Warehouse::query()
                ->where([['is_store', '=', 1], ['is_active', '=', 1], ['integration_id', '!=', null]])
                ->first();
        }

        $products = $place->productVariations()->wherePivot('remainder', '>', 9)->get();

        $warehouse = $_GET['warehouse'] ?? $place->integration_id;

        return view('app.integrations.yandex_eats.products.index', compact('products', 'warehouses', 'warehouse'));
    }

    public function productEdit(ProductVariation $product)
    {
        return view('app.integrations.yandex_eats.products.edit', compact('product'));
    }

    public function productUpdate(Request $request, ProductVariation $product)
    {
        $product->update([
            'old_price' => $request->input('old_price')
        ]);

        return redirect()->route('integrations.yandex_eats.products')->with(['success' => 1]);
    }
}
