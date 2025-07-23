<?php

namespace App\Http\Controllers\Yandex;

use App\Models\Yandex\Eats\{Order, OrderStatus};
use App\Models\{OAuthClient, Warehouse, ProductVariation, Category};
use App\Services\Yandex\{
    Eats,
    Classes\Eats\OrderUpdateItem,
    Classes\Eats\YandexOrderPaymentInfo,
    Classes\Eats\OrderCreateItem,
    Classes\Eats\YandexDeliveryInfo,
    Classes\Eats\YandexOrderCreate,
    Classes\Eats\ItemInArrayAvailability,
    Classes\Eats\Measure,
    Classes\Eats\BrandImage,
    Classes\Eats\ItemDescription,
    Classes\Eats\Barcode,
    Classes\Eats\BrandItem,
    Classes\Eats\NomenclatureCategory,
    Classes\Eats\PriceItem,
    Classes\Eats\ErrorItem,
};
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\{DB, Http};
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
            return response([
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

        if (!$warehouse) {
            $error = new ErrorItem(404, 'Ресурс не найден');
            return response([$error->toArray()], $error->getCode());
        }

        $products = $warehouse->productVariations()->wherePivot('remainder', '>', 9)->get();

        $items = [];
        foreach($products as $product) {
            $priceItem = new PriceItem($product->integration_id, (float)$product->price, -1, $product->old_price);
            $items[] = $priceItem->toArray();
        }

        return response(['items' => $items]);
    }

    public function composition()
    {
        $categories = Category::query()
            ->where([['integration_id', '!=', null], ['is_active', '=', 1]])
            ->get();

        $categoryForYandex = [];
        foreach($categories as $category) {
            $nomenclatureCategory = new NomenclatureCategory($category->integration_id, $category->title['ru'], $category->parent_id ?? '');
            $categoryForYandex[] = $nomenclatureCategory->toArray();
        }

        $products = ProductVariation::query()
            ->where([['integration_id', '!=', null], ['is_active', '=', 1], ['is_available', '=', 1], ['remainder', '>', 9]])
            ->whereHas('product', function($query) {
            	$query->whereHas('categories', function($categoriesQuery) {
            		$categoriesQuery->where('categories.is_active', 1);
            	});
            })
            ->get();

        $items = [];
        foreach($products as $product) {
        	// if ($product->integration_id == '00-00000711') dd(strip_tags($product->product->desc['ru']));
            $barcode = new Barcode('ean13', $product->integration_id, 'none');
            $itemDescription = new ItemDescription($product->expiration_date ?? '', strip_tags($product->product->desc['ru']), $product->product->brand->title);
            $brandImages = [];
            foreach($product->productVariationImages as $key => $img) {
                $brandImg = new BrandImage($img->img, $key);
                $brandImages[] = $brandImg->toArray();
            }
            $measure = new Measure('GRM', $product->weight ?? 300);
            $brandItem = new BrandItem(
                $barcode,
                $itemDescription,
                $product->integration_id,
                $brandImages,
                false,
                $measure,
                $product->product->title['ru'],
                $product->integration_id,
                $product->product->categories[0]->integration_id
            );
            $items[] = $brandItem->toArray();
        }

        return response([
            'categories' => $categoryForYandex,
            'items' => $items
        ]);
    }

    public function availability(string $placeId)
    {
        $placeId = '00000000011';

        $warehouse = Warehouse::query()
            ->where('integration_id', $placeId)
            ->first();

        if (!$warehouse) {
            $error = new ErrorItem(404, 'Ресурс не найден');
            return response([$error->toArray()], $error->getCode());
        }

        $products = $warehouse->productVariations()->wherePivot('remainder', '>', 9)->get();

        $items = [];
        foreach($products as $product) {
            $priceItem = new ItemInArrayAvailability($product->integration_id, $product->pivot->remainder);
            $items[] = $priceItem->toArray();
        }

        return response(['items' => $items]);
    }

    public function order(Request $request)
    {
        $data = $request->all();

        if (
            !isset($data['comment']) ||
            !isset($data['deliveryInfo']) || !isset($data['deliveryInfo']['courierArrivementDate']) ||
            !isset($data['discriminator']) ||
            !isset($data['eatsId']) ||
            !isset($data['items']) ||
            !isset($data['paymentInfo'])
        ) {
            $error = new ErrorItem(400, 'Проверьте переданные данные!');
            return response([$error->toArray()], $error->getCode());
        }

        $deliveryInfo = new YandexDeliveryInfo($data['deliveryInfo']['courierArrivementDate'], $data['deliveryInfo']['clientName'] ?? null, $data['deliveryInfo']['isSlotDelivery'] ?? null, $data['deliveryInfo']['phoneNumber'] ?? null, $data['deliveryInfo']['realPhoneNumber'] ?? null);
        $items = [];
        foreach($data['items'] as $item) {
            if (
                !isset($item['id']) ||
                !isset($item['price']) ||
                !isset($item['quantity'])
            ) {
                $error = new ErrorItem(400, 'Проверьте переданные данные!');
                return response([$error->toArray()], $error->getCode());
            }

            $orderCreateItem = new OrderCreateItem($item['id'], $item['price'], $item['quantity'], $item['name'] ?? null, $item['originPrice'] ?? null);
            $items[] = $orderCreateItem->toArray();
        }
        $paymentInfo = new YandexOrderPaymentInfo($data['paymentInfo']['itemsCost'], $data['paymentInfo']['paymentType']);
        $yandexOrderCreate = new YandexOrderCreate($data['comment'], $deliveryInfo, $data['discriminator'], $data['eatsId'], $items, $paymentInfo, $data['brand'] ?? '', $data['platform'], $data['restaurantId']);

        DB::beginTransaction();
        try {
            $order = Order::create($yandexOrderCreate->toArray());

            $order->statusChanges()->create([
                'status' => 'NEW'
            ]);

            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();

            $this->telegramNotification('Система несмог получить заказ. Ошибка: '.$e->getMessage());

            $error = new ErrorItem(500, $e->getMessage());
            return response([$error->toArray()], $error->getCode());
        }

        $this->telegramNotification('Поступил заказ через ЯндексЕда - '.'https://admin.okc.uz/dashboard/integrations/yandex_eats/orders/'.$order->id);

        return response([
            'result' =>'OK',
            'orderId' => $order->id
        ]);
    }

    public function getOrder(string $orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            $error = new ErrorItem(404, 'Заказ с таким ID не существует!');
            return response([$error->toArray()], $error->getCode());
        }

        $items = [];
        foreach($order->items as $item) {
            $orderUpdateItem = new OrderUpdateItem($item['id'], $item['price'], $item['quantity'], $item['name'] ?? '', $item['origin_price'] ?? null);
            $items[] = $orderUpdateItem->toArray();
        }

        return response([
            'discriminator' => $order->discriminator,
            'eatsId' => $order->eats_id,
            'items' => $items
        ]);
    }

    public function getOrderStatus(string $orderId)
    {
        $order = Order::find($orderId);
        $status = $order->statusChanges()->latest()->first();
        if (!$order || !$status) {
            $error = new ErrorItem(404, 'Заказ с таким ID не существует или не присвоен статус!');
            return response([$error->toArray()], $error->getCode());
        }

        return response($status->packages_count ? [
            'comment' => $status->comment,
            'packagesCount' => $status->packages_count,
            'reason' => $status->reason ?? '',
            'status' => $status->status,
            'updatedAt' => date('Y-m-d\TH:i:s.uP', strtotime($status->updated_at)),
        ] : [
            'comment' => $status->comment,
            'reason' => $status->reason ?? '',
            'status' => $status->status,
            'updatedAt' => date('Y-m-d\TH:i:s.uP', strtotime($status->updated_at)),
        ]);
    }

    public function updateOrderStatus(Request $request, string $orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            $error = new ErrorItem(404, 'Заказ с таким ID не существует!');
            return response([$error->toArray()], $error->getCode());
        }

        $data = $request->all();
        if (!in_array($data['status'], ['TAKEN_BY_COURIER', 'DELIVERED', 'CANCELLED'])) {
            $error = new ErrorItem(404, 'Такого статуса не имеется в системе!');
            return response([$error->toArray()], $error->getCode());
        }

        DB::beginTransaction();
        try {
            OrderStatus::create([
                'order_id' => $orderId,
                'status' => $data['status'],
                'comment' => $data['comment'],
                'platform' => $data['platform'],
                'attributes' => isset($data['attributes']) ? $data['attributes'] : [],
                'reason' => isset($data['reason)']) ? $data['reason)'] : null,
                'updated_at' => isset($data['updatedAt']) ? date('Y-m-d H:i', strtotime($data['updatedAt'])) : null
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            $this->telegramNotification('Система не смог обновить статус заказа. Ошибка: '.$e->getMessage());

            $error = new ErrorItem(500, $e->getMessage());
            return response([$error->toArray()], $error->getCode());
        }

        return response()->noContent();
    }





    // Интерфейсы админки

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

    public function orders()
    {
        $orders = Order::query()
            ->latest()
            ->paginate(24);

        return view('app.integrations.yandex_eats.orders.index', compact('orders'));
    }

    public function orderEdit(Order $order)
    {
        return view('app.integrations.yandex_eats.orders.edit', compact('order'));
    }

    public function orderUpdate(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:next,cancelled'
        ]);

        if ($request->input('status') == 'next') {
            switch ($order->currentStatus) {
                case 'NEW':
                    $order->statusChanges()->create([
                        'status' => 'ACCEPTED_BY_RESTAURANT'
                    ]);
                    break;

                case 'ACCEPTED_BY_RESTAURANT':
                    $order->statusChanges()->create([
                        'status' => 'COOKING'
                    ]);
                    break;

                case 'COOKING':
                    $order->statusChanges()->create([
                        'status' => 'READY'
                    ]);
                    break;
            }
        } else if ($request->input('status') == 'cancelled') {
            $order->statusChanges()->create([
                'status' => 'CANCELLED'
            ]);
        }

        return back()->with(['success' => 1]);
    }

    // Дополнительные функции
    private function telegramNotification(string $text): void
    {
        Http::get('https://api.telegram.org/bot'.config('telegram_bot.dev_bot_token').'/sendMessage?parse_mode=HTML&chat_id=-1002617372536&text='.$text);
    }
}
