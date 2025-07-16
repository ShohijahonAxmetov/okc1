<?php

namespace App\Http\Controllers\Yandex;

use App\Services\Yandex\Classes\{ClaimRoutePoint, ClaimItem, Address, Contact, Size};
use App\Models\LoadingPoint;
use App\Models\Yandex\Delivery\Order;
use App\Services\Yandex\Delivery;
use App\Http\Controllers\Controller;
use Illuminate\Support\{Str, Arr};
use Illuminate\Http\Request;

class YandexDeliveryController extends Controller
{
    protected $baseUrl;
    protected $token;
    protected $deliveryService;

    protected $sourceName = 'OKC - Original Korean Cosmetics';
    protected $sourcePhoneNumber = '998997639696';

    public function __construct(Delivery $delivery)
    {
        $this->baseUrl = config('yandex_delivery.base_url');
        $this->token = config('yandex_delivery.token');
        $this->deliveryService = $delivery;
    }

    public function index()
    {
        return view('app.integrations.yandex_delivery.index');
    }

    public function orders()
    {
        $orders = Order::latest()
            ->get();

        $orders->map(function ($order) {
            return $order->order_in_str = $this->deliveryService->status2Str($order->status);
        });

        return view('app.integrations.yandex_delivery.orders.index', compact('orders'));
    }

    public function orderCreate(Request $request)
    {
        $orders = \App\Models\Order::latest()
            ->get();
        $loadingPoints = LoadingPoint::orderBy('name')
            ->get();

        $currentOrder = null;
        if ($request->input('order_id') !== null) $currentOrder = \App\Models\Order::findOrFail($request->input('order_id'));

        return view('app.integrations.yandex_delivery.orders.create', compact('orders', 'loadingPoints', 'currentOrder'));
    }

    public function order(Request $request)
    {
        $validated = $request->validate([
            'a_lat' => 'required',
            'a_lon' => 'required',
            'b_lat' => 'required',
            'b_lon' => 'required',
            'preliminary_amount' => 'required|numeric',
            'order_id' => 'nullable|exists:orders,id',
            'start_point' => 'nullable|exists:loading_points,id'
        ]);

        $deliveryOrder = Order::where(['order_id' => $validated['order_id'], 'loading_point_id' => $validated['start_point'], 'is_actual' => 1])->first();

        $res = $this->deliveryService->claimsAccept($deliveryOrder->claim_id);

        // dd($res->json());

        if (!$res->ok()) {
            if ($res->json()['code'] == 'offer_expired') {
                $deliveryOrder->update(['is_actual' => false]);
            }

            return back()->withInput()->with(['success' => 0, 'message' => $res->json()['message']]);
        }

        // Сохранить сумму заказа, установить статус
        $deliveryOrder->update([
            'preliminary_amount' => $validated['preliminary_amount'],
            'status' => 4
        ]);

        return redirect()->route('integrations.yandex_delivery.orders')->with(['success' => 1, 'message' => 'Заказ создан']);

        // dd($res->json(), $res->status());
    }

    public function orderStore(Request $request)
    {
        $validated = $request->validate([
            'a_lat' => 'required',
            'a_lon' => 'required',
            'b_lat' => 'required',
            'b_lon' => 'required',
            // 'preliminary_amount' => 'required|numeric',
            'order_id' => 'nullable|exists:orders,id',
            'start_point' => 'nullable|exists:loading_points,id'
        ]);
        $validated['status'] = 1;
        $validated['loading_point_id'] = $validated['start_point'];
        $validated['is_actual'] = true;

        $deliveryOrder = Order::where(['order_id' => $validated['order_id'], 'loading_point_id' => $validated['loading_point_id'], 'is_actual' => true])->first();
        if ($deliveryOrder) {

            $deliveryOrder->update($validated);
        } else {
            $requestId = (string) Str::uuid();
            $validated['request_id'] = $requestId;

            $deliveryOrder = Order::create($validated);
        }

        // claimItems
        $claimItems = [];
        foreach($deliveryOrder->order->productVariations as $variation) {
            $claimItemObj = new ClaimItem(
                $variation->id,
                1, // Идентификатор точки (int64), откуда нужно забрать товар
                2, // Идентификатор точки (int64), куда нужно доставить товар
                $variation->product->title['ru'],
                new Size($variation->length ?? 0.79, $variation->width ?? 0.49, $variation->height ?? 0.49),
                $variation->weight ?? 9,
                $variation->price,
                $variation->pivot->count
            );

            $claimItems[] = [
                'extra_id' => $claimItemObj->extraId,
                'pickup_point' => $claimItemObj->pickupPoint,
                'dropoff_point' => $claimItemObj->dropoffPoint,
                'title' => $claimItemObj->title,
                'size' => [
                    'length' => $claimItemObj->size->length/100,
                    'width' => $claimItemObj->size->width/100,
                    'height' => $claimItemObj->size->height/100,
                ],
                'weight' => $claimItemObj->weight/1000,
                'cost_currency' => $claimItemObj->costCurrency,
                'cost_value' => $claimItemObj->costValue,
                'quantity' => $claimItemObj->quantity,
            ];

            unset($claimItemObj);
        }

        // claimeRoutePoints
        $claimeRoutePoints = [];
        //  source
        $source = new ClaimRoutePoint(
            1,
            1,
            new Contact($this->sourceName, $this->sourcePhoneNumber),
            new Address(
                $deliveryOrder->loadingPoint->address,
                $deliveryOrder->loadingPoint->address,
                [(float)$deliveryOrder->loadingPoint->lon, (float)$deliveryOrder->loadingPoint->lat],
                // [(float)$deliveryOrder->loadingPoint->lon, (float)$deliveryOrder->loadingPoint->lat],
                $deliveryOrder->loadingPoint->address->comments ?? ''
            ),
            'source',
            null
        );
        $claimeRoutePoints[] = [
            'point_id' => $source->pointId,
            'visit_order' => $source->visitOrder,
            'contact' => [
                'name' => $source->contact->name,
                'phone' => $source->contact->phone
            ],
            'address' => [
                'fullname' => $source->address->fullname,
                'shortname' => $source->address->shortname,
                'coordinates' => $source->address->coordinates,
                'country' => $source->address->country,
                'city' => $source->address->city,
                'comment' => $source->address->comment
            ],
            'skip_confirmation' => $source->skipConfirmation,
            'leave_under_door' => $source->leaveUnderDoor,
            'meet_outside' => $source->meetOutside,
            'no_door_call' => $source->noDoorCall,
            'type' => $source->type,
            'externalOrderId' => $source->externalOrderId,
            'should_notify_on_order_readiness' => $source->shouldNotifyOnOrderReadiness
        ];

        // dd($claimeRoutePoints);

        // destination
        $destination = new ClaimRoutePoint(
            2,
            2,
            new Contact($deliveryOrder->order->name, $deliveryOrder->order->phone_number),
            new Address(
                $deliveryOrder->order->address,
                $deliveryOrder->order->address,
                [(float)$deliveryOrder->b_lon, (float)$deliveryOrder->b_lat],
                $deliveryOrder->order->comments ?? ''
            ),
            'destination',
            $deliveryOrder->id
        );
        $claimeRoutePoints[] = [
            'point_id' => $destination->pointId,
            'visit_order' => $destination->visitOrder,
            'contact' => [
                'name' => $destination->contact->name,
                'phone' => $destination->contact->phone
            ],
            'address' => [
                'fullname' => $destination->address->fullname,
                'shortname' => $destination->address->shortname,
                'coordinates' => $destination->address->coordinates,
                'country' => $destination->address->country,
                'city' => $destination->address->city,
                'comment' => $destination->address->comment
            ],
            'skip_confirmation' => $destination->skipConfirmation,
            'leave_under_door' => $destination->leaveUnderDoor,
            'meet_outside' => $destination->meetOutside,
            'no_door_call' => $destination->noDoorCall,
            'type' => $destination->type,
            'externalOrderId' => $destination->externalOrderId,
            'should_notify_on_order_readiness' => $destination->shouldNotifyOnOrderReadiness
        ];

        // Создание заявки
        $res = $this->deliveryService->claimsCreate($claimeRoutePoints, $claimItems, $deliveryOrder->request_id);

        if (!$res->ok()) {
            Log::info($res->json());
            return redirect()->route('integrations.yandex_delivery.orders')->with(['success' => 0, 'message' => 'Что-то пошло не так!']);
        }

        $deliveryOrder->update([
            'claim_res' => $res->json(),
            'claim_status' => $res->status(),
            'claim_id' => $res->json()['id']
        ]);
        unset($res);

        // Получать инфорамация о заявке
        $res = $this->deliveryService->claimsInfo($deliveryOrder->claim_id);

        $attempts = 0;
        while($attempts < 4) {
            sleep(2);

            if ($res->json()['status'] == 'ready_for_approval') break;

            // Получать инфорамация о заявке
            $res = $this->deliveryService->claimsInfo($deliveryOrder->claim_id);

            $attempts ++;
        }

        return $res;

        return redirect()->route('integrations.yandex_delivery.orders')->with(['success' => 1, 'message' => 'Заказ создан']);
    }

    public function loadingPoints()
    {
        $loadingPoints = LoadingPoint::orderBy('name')
            ->get();
        return view('app.integrations.yandex_delivery.loading_points.index', compact('loadingPoints'));
    }

    public function loadingPointsCreate()
    {
        return view('app.integrations.yandex_delivery.loading_points.create');
    }

    public function loadingPointsStore(Request $request)
    {
        $validated = $request->validate([
            'lat' => 'required',
            'lon' => 'required',
            'name' => 'required',
            'desc' => 'nullable',
            'address' => 'nullable',
            'comments' => 'nullable',
        ]);

        LoadingPoint::create($validated);

        return redirect()->route('integrations.yandex_delivery.loading_points')->with(['success' => 1, 'message' => ' Пункт погрузки создан']);
    }

    public function loadingPointsUpdate(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
            'desc' => 'nullable',
            'address' => 'nullable',
            'comments' => 'nullable',
        ]);

        LoadingPoint::findOrFail($id)->update($validated);
        
        return redirect()->route('integrations.yandex_delivery.loading_points')->with(['success' => 1, 'message' => ' Пункт погрузки редактирован']);
    }

    public function loadingPointsDelete(Request $request, int $id)
    {
        LoadingPoint::findOrFail($id)->delete();

        return back()->with(['success' => true, 'message' => 'Удалена']);
    }

    public function checkPrice(Request $request)
    {
        $request->merge(['preliminary_price' => 0]);
        $res = $this->orderStore($request);

        return response($res->json());
    }

    public function getClaimsInfo()
    {
        $orders = Order::whereNotIn('status', [15,20,21,22,23,24,25,26,27])->get();
        if (!$orders->first()) return;
        $res = $this->deliveryService->claimsBulkInfo($orders->pluck('claim_id')->toArray());
        $resData = $res->json();

        foreach($orders as $order) {
            $result = Arr::where($resData['claims'], function ($value, $key) use ($order) {
                return $value['id'] == $order->claim_id;
            });

            $order->update(['status' => $this->deliveryService->status2Number($result[0]['status'])]);
        }
    }

    // public function checkPrice(Request $request)
    // {
    //     $request->validate([
    //         'a_lat' => 'required',
    //         'a_lon' => 'required',
    //         'b_lat' => 'required',
    //         'b_lon' => 'required',
    //     ]);

    //     $res = $this->deliveryService->checkPrice(
    //         [
    //             [
    //                 'coordinates' => [
    //                     (float)$request->input('a_lon'),
    //                     (float)$request->input('a_lat')
    //                 ]
    //             ],
    //             [
    //                 'coordinates' => [
    //                     (float)$request->input('b_lon'),
    //                     (float)$request->input('b_lat')
    //                 ]
    //             ]
    //         ],
    //         [
    //             ['quantity' => 1]
    //         ]
    //     );

    //     return response($res->json());
    // }
}
