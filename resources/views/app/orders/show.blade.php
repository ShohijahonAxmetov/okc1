@extends('layouts.app')

@section('title', 'ЗАКАЗЫ')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'Главная',
'route' => 'dashboard'
],
[
'title' => 'Заказы',
'route' => 'orders.index'
],
[
'title' => '#'.$order->id,
]
]
])

@endsection

@section('content')

<!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Order details page-->
        <div class="d-flex flex-column gap-7 gap-lg-10">
            <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
                <!--begin:::Tabs-->
                <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-lg-n2 me-auto">
                    <!--begin:::Tab item-->
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_sales_order_summary">Итоговая сумма</a>
                    </li>
                    <!--end:::Tab item-->
                    <!--begin:::Tab item-->
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_sales_order_history">История заказа</a>
                    </li>
                    <!--end:::Tab item-->
                </ul>
                <!--end:::Tabs-->
                <!--begin::Button-->
                <!-- <a class="btn btn-light-primary btn-sm me-lg-n7" data-bs-toggle="modal" data-bs-target="#delete_product">Delete product from Order</a> -->
                <!-- <a class="btn btn-light-primary btn-sm me-lg-n7" data-bs-toggle="modal" data-bs-target="#add_product">Добавить товар в заказ</a> -->
                @if($order->status == 'new')
                    <form action="{{ route('orders.update', ['id' =>$order->id]) }}" method="post">
                        @csrf
                        <input type="hidden" name="status" value="accepted">
                        <input type="hidden" name="payment_method" value="{{ $order->payment_method }}">
                        <input type="hidden" name="delivery_method" value="{{ $order->delivery_method }}">
                        <input type="hidden" name="with_delivery" value="{{ $order->with_delivery }}">
                        <button type="submit" class="btn btn-light-primary btn-sm me-lg-n7 bg-success text-white">Принять заказ</button>
                    </form>
                    <form action="{{ route('orders.update', ['id' =>$order->id]) }}" method="post">
                        @csrf
                        <input type="hidden" name="status" value="cancelled">
                        <input type="hidden" name="payment_method" value="{{ $order->payment_method }}">
                        <input type="hidden" name="delivery_method" value="{{ $order->delivery_method }}">
                        <input type="hidden" name="with_delivery" value="{{ $order->with_delivery }}">
                        <button type="submit" class="btn btn-light-primary btn-sm me-lg-n7 bg-danger text-white">Отменить заказ</button>
                    </form>
                @else
                <a href="{{ route('orders.edit', ['id' => $order->id]) }}" class="btn btn-light-primary btn-sm me-lg-n7 bg-warning text-white">Изменить заказ</a>
                @endif
                <!--end::Button-->
            </div>
            <!--begin::Order summary-->
            <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
                <!--begin::Order details-->
                <div class="card card-flush py-4 flex-row-fluid">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Информация заказа (#{{ $order->id }})</h2>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                <!--begin::Table body-->
                                <tbody class="fw-bold text-gray-600">
                                    <!--begin::Date-->
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                                <!--begin::Svg Icon | path: icons/duotune/files/fil002.svg-->
                                                <span class="svg-icon svg-icon-2 me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21" fill="none">
                                                        <path opacity="0.3" d="M19 3.40002C18.4 3.40002 18 3.80002 18 4.40002V8.40002H14V4.40002C14 3.80002 13.6 3.40002 13 3.40002C12.4 3.40002 12 3.80002 12 4.40002V8.40002H8V4.40002C8 3.80002 7.6 3.40002 7 3.40002C6.4 3.40002 6 3.80002 6 4.40002V8.40002H2V4.40002C2 3.80002 1.6 3.40002 1 3.40002C0.4 3.40002 0 3.80002 0 4.40002V19.4C0 20 0.4 20.4 1 20.4H19C19.6 20.4 20 20 20 19.4V4.40002C20 3.80002 19.6 3.40002 19 3.40002ZM18 10.4V13.4H14V10.4H18ZM12 10.4V13.4H8V10.4H12ZM12 15.4V18.4H8V15.4H12ZM6 10.4V13.4H2V10.4H6ZM2 15.4H6V18.4H2V15.4ZM14 18.4V15.4H18V18.4H14Z" fill="currentColor" />
                                                        <path d="M19 0.400024H1C0.4 0.400024 0 0.800024 0 1.40002V4.40002C0 5.00002 0.4 5.40002 1 5.40002H19C19.6 5.40002 20 5.00002 20 4.40002V1.40002C20 0.800024 19.6 0.400024 19 0.400024Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->Дата добавления
                                            </div>
                                        </td>
                                        <td class="fw-bolder text-end">{{ date('H:i d-m-Y', strtotime($order->created_at)) }}</td>
                                    </tr>
                                    <!--end::Date-->
                                    <!--begin::Payment method-->
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                                <!--begin::Svg Icon | path: icons/duotune/finance/fin008.svg-->
                                                <span class="svg-icon svg-icon-2 me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path opacity="0.3" d="M3.20001 5.91897L16.9 3.01895C17.4 2.91895 18 3.219 18.1 3.819L19.2 9.01895L3.20001 5.91897Z" fill="currentColor" />
                                                        <path opacity="0.3" d="M13 13.9189C13 12.2189 14.3 10.9189 16 10.9189H21C21.6 10.9189 22 11.3189 22 11.9189V15.9189C22 16.5189 21.6 16.9189 21 16.9189H16C14.3 16.9189 13 15.6189 13 13.9189ZM16 12.4189C15.2 12.4189 14.5 13.1189 14.5 13.9189C14.5 14.7189 15.2 15.4189 16 15.4189C16.8 15.4189 17.5 14.7189 17.5 13.9189C17.5 13.1189 16.8 12.4189 16 12.4189Z" fill="currentColor" />
                                                        <path d="M13 13.9189C13 12.2189 14.3 10.9189 16 10.9189H21V7.91895C21 6.81895 20.1 5.91895 19 5.91895H3C2.4 5.91895 2 6.31895 2 6.91895V20.9189C2 21.5189 2.4 21.9189 3 21.9189H19C20.1 21.9189 21 21.0189 21 19.9189V16.9189H16C14.3 16.9189 13 15.6189 13 13.9189Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->Метод оплаты
                                            </div>
                                        </td>
                                        <td class="fw-bolder text-end text-capitalize">
                                            @if($order->payment_method == 'cash')
                                            Наличкой
                                            @else
                                            Картой
                                            @if($order->payment_card == 'payme')
                                            (payme)
                                            @elseif($order->payment_card == 'click')
                                            (click)
                                            @endif
                                            @endif
                                        </td>
                                    </tr>
                                    <!--end::Payment method-->
                                    <!--begin::Date-->
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                                <!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm006.svg-->
                                                <span class="svg-icon svg-icon-2 me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path d="M20 8H16C15.4 8 15 8.4 15 9V16H10V17C10 17.6 10.4 18 11 18H16C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18H21C21.6 18 22 17.6 22 17V13L20 8Z" fill="currentColor" />
                                                        <path opacity="0.3" d="M20 18C20 19.1 19.1 20 18 20C16.9 20 16 19.1 16 18C16 16.9 16.9 16 18 16C19.1 16 20 16.9 20 18ZM15 4C15 3.4 14.6 3 14 3H3C2.4 3 2 3.4 2 4V13C2 13.6 2.4 14 3 14H15V4ZM6 16C4.9 16 4 16.9 4 18C4 19.1 4.9 20 6 20C7.1 20 8 19.1 8 18C8 16.9 7.1 16 6 16Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->Способ доставки
                                            </div>
                                        </td>
                                        <td class="fw-bolder text-end text-capitalize">
                                            @if($order->with_delivery == 0)
                                            Самовывоз
                                            @else
                                            С доставкой
                                            @endif
                                        </td>
                                    </tr>
                                    <!--end::Date-->
                                </tbody>
                                <!--end::Table body-->
                            </table>
                            <!--end::Table-->
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Order details-->
                <!--begin::Customer details-->
                <div class="card card-flush py-4 flex-row-fluid">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Сведения о клиенте</h2>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                <!--begin::Table body-->
                                <tbody class="fw-bold text-gray-600">
                                    <!--begin::Customer name-->
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                                <!--begin::Svg Icon | path: icons/duotune/communication/com006.svg-->
                                                <span class="svg-icon svg-icon-2 me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path opacity="0.3" d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z" fill="currentColor" />
                                                        <path d="M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->Клиент
                                            </div>
                                        </td>
                                        <td class="fw-bolder text-end">
                                            <div class="d-flex align-items-center justify-content-end">
                                                <!--begin:: Avatar -->
                                                <div class="symbol symbol-circle symbol-25px overflow-hidden me-3">
                                                    <a>
                                                        <div class="symbol-label">
                                                            <img src="{{ isset($order->user->img) ? $order->user->img_url : '/assets/media/default-user.jpg' }}" alt="Dan Wilson" class="w-100" />
                                                        </div>
                                                    </a>
                                                </div>
                                                <!--end::Avatar-->
                                                <!--begin::Name-->
                                                <a class="text-gray-600 text-hover-primary">{{ $order->name ? $order->name : (isset($order->user) ? $order->user->name : 'Клиент удален') }}</a>
                                                <!--end::Name-->
                                            </div>
                                        </td>
                                    </tr>
                                    <!--end::Customer name-->
                                    <!--begin::Customer email-->
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                                <!--begin::Svg Icon | path: icons/duotune/communication/com011.svg-->
                                                <span class="svg-icon svg-icon-2 me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path opacity="0.3" d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z" fill="currentColor" />
                                                        <path d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->Email
                                            </div>
                                        </td>
                                        <td class="fw-bolder text-end">
                                            <a class="text-gray-600 text-hover-primary">{{ $order->email ?? '--' }}</a>
                                        </td>
                                    </tr>
                                    <!--end::Payment method-->
                                    <!--begin::Date-->
                                    <tr>
                                        <td class="text-muted">
                                            <div class="d-flex align-items-center">
                                                <!--begin::Svg Icon | path: icons/duotune/electronics/elc003.svg-->
                                                <span class="svg-icon svg-icon-2 me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path d="M5 20H19V21C19 21.6 18.6 22 18 22H6C5.4 22 5 21.6 5 21V20ZM19 3C19 2.4 18.6 2 18 2H6C5.4 2 5 2.4 5 3V4H19V3Z" fill="currentColor" />
                                                        <path opacity="0.3" d="M19 4H5V20H19V4Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->Телефон
                                            </div>
                                        </td>
                                        <td class="fw-bolder text-end">+{{ $order->phone_number }}</td>
                                    </tr>
                                    <!--end::Date-->
                                </tbody>
                                <!--end::Table body-->
                            </table>
                            <!--end::Table-->
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Customer details-->
            </div>
            <!--end::Order summary-->
            <!--begin::Tab content-->
            <div class="tab-content">
                <!--begin::Tab pane-->
                <div class="tab-pane fade show active" id="kt_ecommerce_sales_order_summary" role="tab-panel">
                    <!--begin::Orders-->
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
                            <!--begin::Shipping address-->
                            <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                                <!--begin::Background-->
                                <div class="position-absolute top-0 end-0 opacity-10 pe-none text-end">
                                    <img src="/assets/media/icons/duotune/ecommerce/ecm006.svg" class="w-175px" />
                                </div>
                                <!--end::Background-->
                                <!--begin::Card header-->
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Адрес доставки</h2>
                                    </div>
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body pt-0">Город: {{ $order->region ? config('app.REGIONS')[$order->region - 1]['uz'] : '--' }},
                                    <br />Область: {{ $order->district ? config('app.DISTRICTS')[$order->district - 1]['title'] : '--' }},
                                    <br />Адрес: {{ $order->address ?? '--' }},
                                    <br />Почтовый индекс: {{ $order->postal_code ?? '--' }}.
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Shipping address-->
                        </div>
                        <!--begin::Product List-->
                        <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Заказ #{{ $order->id }}</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                        <!--begin::Table head-->
                                        <thead>
                                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                                <th class="min-w-175px">Продукт</th>
                                                @foreach($warehouses as $warehouse)
                                                <th class="min-w-50px border-start {{ $loop->last ? 'border-end' : '' }}" style="vertical-align: bottom;text-align: center;"><span style="-ms-writing-mode: tb-rl;-webkit-writing-mode: vertical-rl;writing-mode: vertical-rl;transform: rotate(180deg);white-space: nowrap;">{{ $warehouse->title }}</span></th>
                                                @endforeach
                                                <th class="min-w-70px text-end">Количество</th>
                                                <th class="min-w-100px text-end">Цена за единицу</th>
                                                <th class="min-w-100px text-end">Итоговая</th>
                                                <!-- <th class="min-w-100px text-end">Действия</th> -->
                                            </tr>
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody class="fw-bold text-gray-600">
                                            <!--begin::Products-->
                                            @foreach($order->productVariations as $product)
                                            <tr>
                                                <!--begin::Product-->
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <!--begin::Thumbnail-->
                                                        <a href="{{ route('products.edit', ['product' => $product->product]) }}" class="symbol symbol-50px">
                                                            <span class="symbol-label" style="background-image:url({{ isset($product->productVariationImages[0]) ? $product->productVariationImages[0]->min_img : '/assets/media/default.png' }});background-size:contain"></span>
                                                        </a>
                                                        <!--end::Thumbnail-->
                                                        <!--begin::Title-->
                                                        <div class="ms-5">
                                                            <a href="{{ route('products.edit', ['product' => $product->product]) }}" class="fw-bolder text-gray-600 text-hover-primary">{{ isset($product->product) ? $product->product->title['ru'] : 'Deleted product' }}</a>
                                                            <div class="fs-7 text-muted">ID: {{ $product->integration_id }}</div>
                                                        </div>
                                                        <!--end::Title-->
                                                    </div>
                                                </td>
                                                <!--end::Product-->
                                                @foreach($warehouses as $warehouse)
                                                <td class="text-end border-start text-center {{ $loop->last ? 'border-end' : '' }}">
                                                    <span class="fs-6 fw-bold">{{ $product->warehouses()->where('integration_id', $warehouse->integration_id)->first() ? $product->warehouses()->where('integration_id', $warehouse->integration_id)->first()->pivot->remainder : 0 }}</span>
                                                </td>
                                                @endforeach
                                                <!--begin::Quantity-->
                                                <td class="text-end">{{ isset($product->pivot) ? $product->pivot->count : '--' }}</td>
                                                <!--end::Quantity-->
                                                <!--begin::Price-->
                                                <td class="text-end">{{ isset($product->pivot) ? $product->pivot->price : '--' }}</td>
                                                <!--end::Price-->
                                                <!--begin::Total-->
                                                <td class="text-end">{{ isset($product->pivot) ? $product->pivot->count * $product->pivot->price : '--' }}</td>
                                                <!--end::Total-->
                                                <!--begin::Total-->
                                                <!-- <td class="text-end">
                                                    <form action="{{ route('orders.delete_product', ['id' => $product->id]) }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                        <button type="button" onclick="confirmation(this)" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" type="button">
                                                            <span class="svg-icon svg-icon-3">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                    <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
                                                                    <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
                                                                    <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
                                                                </svg>
                                                            </span>
                                                        </button>
                                                    </form>
                                                </td> -->
                                                <!--end::Total-->
                                            </tr>
                                            @endforeach
                                            @if($order->with_delivery)
                                            <tr>
                                                <td colspan="6" class="fs-5 text-dark text-end">Cумма доставки</td>
                                                <td class="text-dark fs-5 fw-boldest text-end">
                                                    @if($order->region == 1)
                                                        20000 UZS
                                                    @else
                                                        30000 UZS
                                                    @endif
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td colspan="6" class="fs-3 text-dark text-end">Итоговая сумма</td>
                                                <td class="text-dark fs-3 fw-boldest text-end">
                                                        @if($order->payment_method == 'cash' || $order->payment_method == 'cach')
                                                        {{ $order->amount / 100 }} UZS
                                                        @elseif($order->payment_method == 'online')
                                                        @if($order->payment_card == 'payme')
                                                        {{ $order->amount / 100 }} UZS
                                                        @elseif($order->payment_card == 'click')
                                                        {{ $order->amount }} UZS
                                                        @elseif($order->payment_card == 'zoodpay')
                                                        {{ $order->amount }} UZS
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                        <!--end::Table head-->
                                    </table>
                                    <!--end::Table-->
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Product List-->
                        @if(isset($order->zoodpayHistories[0]))
                        <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Транзакции Zoodpay</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                        <!--begin::Table head-->
                                        <thead>
                                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                                <th class="min-w-300px">ID тракзакции</th>
                                                <th class="min-w-100px ">Сумма</th>
                                                <th class="min-w-100px">Статус</th>
                                                <th class="min-w-100px text-end">Дата</th>
                                            </tr>
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody class="fw-bold text-gray-600">
                                            <!--begin::Products-->
                                            @foreach($order->zoodpayHistories as $item)
                                            <tr>
                                                <!--begin::Quantity-->
                                                <td class="text-start">{{ $item->transaction_id }}</td>
                                                <!--end::Quantity-->
                                                <!--begin::Price-->
                                                <td class="text-start">{{ $item->amount }}</td>
                                                <!--end::Price-->
                                                <td class="text-start">{{ $item->status }}</td>
                                                <td class="text-end">{{ $item->created_at }}</td>
                                                <!--begin::Total-->
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <!--end::Table head-->
                                    </table>
                                    <!--end::Table-->
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        @endif

                        @if(isset($order->fargoHistories[0]))
                        <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Истории FARGO</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                        <!--begin::Table head-->
                                        <thead>
                                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                                <th class="min-w-300px">Номер заказа Fargo</th>
                                                <th class="min-w-100px ">Номер клиента Fargo</th>
                                                <th class="min-w-100px">Статус</th>
                                                <th class="min-w-100px text-end">Дата</th>
                                            </tr>
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody class="fw-bold text-gray-600">
                                            <!--begin::Products-->
                                            @foreach($order->fargoHistories as $item)
                                            <tr>
                                                <!--begin::Quantity-->
                                                <td class="text-start">{{ $item->order_number }}</td>
                                                <!--end::Quantity-->
                                                <!--begin::Price-->
                                                <td class="text-start">{{ $item->customer_id }}</td>
                                                <!--end::Price-->
                                                <td class="text-start">{{ $item->ru_status }}</td>
                                                <td class="text-end">{{ $item->created_at }}</td>
                                                <!--begin::Total-->
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <!--end::Table head-->
                                    </table>
                                    <!--end::Table-->
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        @endif
                    </div>
                    <!--end::Orders-->
                </div>
                <!--end::Tab pane-->
                <!--begin::Tab pane-->
                <div class="tab-pane fade" id="kt_ecommerce_sales_order_history" role="tab-panel">
                    <!--begin::Orders-->
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <!--begin::Order history-->
                        <div class="card card-flush py-4 flex-row-fluid">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>История заказа</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                        <!--begin::Table head-->
                                        <thead>
                                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                                <th class="min-w-100px">Дата добавления</th>
                                                <th class="min-w-175px">Комментарии</th>
                                                <th class="min-w-70px">Статус заказа</th>
                                                <th class="min-w-175px">Товары</th>
                                            </tr>
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody class="fw-bold text-gray-600">
                                            @foreach($orders_history as $order_item)
                                            <tr>
                                                <!--begin::Date-->
                                                <td>{{ date('d/m/Y', strtotime($order_item->created_at)) }}</td>
                                                <!--end::Date-->
                                                <!--begin::Comment-->
                                                <td>{{ $order_item->comments ?? '--' }}</td>
                                                <!--end::Comment-->
                                                <!--begin::Status-->
                                                <td>
                                                    <!--begin::Badges-->
                                                    @switch($order_item->status)
                                                    @case('new')
                                                    <span class="badge fs-7 fw-bold text-uppercase" style="background-color: rgb(13,202,240);">{{ $order_item->status }}</span>
                                                    @break
                                                    @case('collected')
                                                    <span class="badge fs-7 fw-bold text-uppercase" style="background-color: rgb(13,110,253);">{{ $order_item->status }}</span>
                                                    @break
                                                    @case('accepted')
                                                    <span class="badge fs-7 fw-bold text-uppercase bg-dark">{{ $order_item->status }}</span>
                                                    @break
                                                    @case('on_the_way')
                                                    <span class="badge fs-7 fw-bold text-uppercase" style="background-color: rgb(225,193,7);">{{ $order_item->status }}</span>
                                                    @break
                                                    @case('returned')
                                                    <span class="badge fs-7 fw-bold text-uppercase" style="background-color: #ff39f9;">{{ $order_item->status }}</span>
                                                    @break
                                                    @case('done')
                                                    <span class="badge fs-7 fw-bold text-uppercase" style="background-color: rgb(25,135,84);">{{ $order_item->status }}</span>
                                                    @break
                                                    @case('cancelled')
                                                    <span class="badge fs-7 fw-bold text-uppercase" style="background-color: rgb(220,53,69);">{{ $order_item->status }}</span>
                                                    @break
                                                    @endswitch
                                                    <!--end::Badges-->
                                                </td>
                                                <!--end::Status-->
                                                <!--begin::Customer Notified-->
                                                <td>
                                                    @if(isset($order_item->productVariations))
                                                    @foreach($order_item->productVariations as $product)
                                                    @if(isset($product->product->title['ru']))
                                                    <a href="{{ route('products.edit', ['product' => $product->product]) }}">{{ $product->product->title['ru'] }}</a>
                                                    @else
                                                    Продукт удален
                                                    @endif
                                                    <br>
                                                    @endforeach
                                                    @else
                                                    Продукт удален
                                                    @endif
                                                </td>
                                                <!--end::Customer Notified-->
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <!--end::Table head-->
                                    </table>
                                    <!--end::Table-->
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Order history-->
                    </div>
                    <!--end::Orders-->
                </div>
                <!--end::Tab pane-->
            </div>
            <!--end::Tab content-->
        </div>
        <!--end::Order details page-->
    </div>
    <!--end::Container-->
</div>
<!--end::Post-->


<!-- modal for add products to order -->
<!--begin::Modal - New Product-->
<!-- <div class="modal fade" tabindex="-1" id="add_product" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form class="form" action="{{ route('orders.add_product', ['id' => $order->id]) }}" method="post">
                @csrf
                <div class="modal-header">
                    <h2 class="fw-bolder" data-kt-calendar="title">Добавить товар в заказ #{{ $order->id }}</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="fv-row mb-9">
                        <label for="exampleFormControlInput2" class="fs-6 fw-bold mb-2 required">Выбрать продукт</label>
                        <select class="form-select" id="select2insidemodal" name="variation_id" data-control="select2" data-hide-search="false" required>
                            @foreach($products_variations as $product)
                            <option value="{{ $product->id }}">{{ $product->product->title['ru'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fv-row mb-9">
                        <label class="fs-6 fw-bold required mb-2">Количество продуктов</label>
                        <input type="text" class="form-control form-control-solid" required name="count" />
                    </div>

                </div>
                <div class="modal-footer flex-center">
                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">Сохранить</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> -->
<!--end::Modal - New Product-->

@endsection

@section('scripts')

<script>
    function confirmation(item) {
        Swal.fire({
            title: 'Вы уверены?',
            text: "Вы не сможете отменить это!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Да, удалить!'
        }).then((result) => {
            if (result.value) {
                item.parentNode.submit();
            }
        });
    }

    $(document).ready(function() {
        $("#select2insidemodal").select2({
            dropdownParent: $("#add_product")
        });
    });
</script>

@endsection