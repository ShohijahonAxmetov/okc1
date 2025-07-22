@extends('layouts.app')

@section('title', 'ЗАКАЗЫ')

@section('links')

<style>
    .alert {
        background-color: #f1416c;
        position: fixed;
        top: 24px;
        right: 24px;
        color: #fff;
        z-index: 100;
        font-weight: 500;
        padding: 0;
        display: flex;
        align-items: center;
        height: 52px;
        padding-left: 16px;
        padding-right: 16px;
    }

    .alert .icon:hover {
        cursor: pointer;
    }

    .alert .icon {
        padding-left: 12px;
    }

    .alert .text {
        padding-right: 12px;
        border-right: 1px solid rgba(255, 255, 255, .5);
    }
</style>

@endsection

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'Главная',
'route' => 'dashboard'
],
[
'title' => 'Заказы',
'route' => 'integrations.yandex_eats.orders'
],
[
'title' => 'Изменить'
]
]
])

@endsection

@section('content')


<form action="{{ route('integrations.yandex_eats.orders.update', ['order' => $order]) }}" method="post">
    @csrf
    @method('put')
    <div class="row">
        <div class="d-flex w-100 justify-content-end">
            <button type="submit" id="success_button" class="btn btn-success">Сохранить</button>
            <a href="{{ route('integrations.yandex_eats.products') }}" class="btn btn-danger ms-5">Отмена</a>
        </div>

        <div class="col-12 mt-5">
            <div class="card">
                <!--begin::Header-->
                <div class="card-header pt-5">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start w-100 mb-10">
                        <span class="card-label fw-bolder text-dark">Товыры в заказе</span>
                    </h3>
                </div>
                <!--end::Header-->

                <!--begin::Body-->
                <div class="card-body py-3">
                    <!--begin::Table container-->
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle gs-0 gy-4">
                            <!--begin::Table head-->
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="ps-4 min-w-125px rounded-start">ID</th>
                                    <th class="min-w-125px">Наименование товара</th>
                                    <th class="min-w-125px">Цена одной единицы товара</th>
                                    <th class="min-w-150px">Количество товара в заказе </th>
                                    <th class="min-w-150px text-end rounded-end pe-2">Исходная цена товара</th>
                                </tr>
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <a class="text-dark fw-bolder text-hover-primary d-block mb-1 fs-6">{{$item['id'] }}</a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-50px me-5"></div>
                                            <div class="d-flex justify-content-start flex-column">
                                                <a class="text-dark fw-bolder mb-1 fs-6">{{ $item['name'] ?? '-' }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fs-6 fw-bold">{{ $item['price'] }}</span>
                                    </td>
                                    <td>
                                        <span class="fs-6 fw-bold">{{ $item['quantity'] }}</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fs-6 fw-bold">{{ $item['originPrice'] ?? '-' }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Table container-->
                </div>
                <!--begin::Body-->
            </div>
        </div>

        <div class="col-6 mt-5">
            <div class="card mb-xl-8">
                <!--begin::Header-->
                <div class="card-header pt-5">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start w-100 mb-10">
                        <span class="card-label fw-bolder text-dark">Основные данные</span>
                    </h3>
                </div>
                <!--end::Header-->

                <!--begin::Body-->
                <div class="card-body py-3">
                    <!--begin::Body-->
                    <div class="card-body pt-0 px-0">
                        <div class="w-100">

                            <div class="mb-6 w-100">
                                <!--begin::Label-->
                                <label class="form-label">ID заказа в нашей системе</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" disabled class="form-control mb-2" placeholder="ID заказа в нашей системе" value="{{ $order->id }}">
                                <!--end::Input-->
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>

                            <div class="mb-6 w-100">
                                <!--begin::Label-->
                                <label class="form-label">ID заказа в Яндекс</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" disabled class="form-control mb-2" placeholder="ID заказа в Яндекс" value="{{ $order->eats_id }}">
                                <!--end::Input-->
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>

                            <div class="mb-6 w-100">
                                <!--begin::Label-->
                                <label class="form-label">Бренд</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" disabled class="form-control mb-2" placeholder="Бренд" value="{{ $order->brand }}">
                                <!--end::Input-->
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>

                            <div class="mb-6 w-100">
                                <!--begin::Label-->
                                <label class="form-label">Комментарий</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" disabled class="form-control mb-2" placeholder="Комментарий" value="{{ $order->comment }}">
                                <!--end::Input-->
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>

                            <div class="mb-6 w-100">
                                <!--begin::Label-->
                                <label class="form-label">Дискриминатор</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" disabled class="form-control mb-2" placeholder="Дискриминатор" value="{{ $order->discriminator }}">
                                <!--end::Input-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">Дискриминатор схемы. Для заказов с доставкой курьерами Яндекс Еды равен "yandex".</div>
                                <!--end::Description-->
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>

                            <div class="mb-6 w-100">
                                <!--begin::Label-->
                                <label class="form-label">Платформа</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" disabled class="form-control mb-2" placeholder="Платформа" value="{{ $order->platform }}">
                                <!--end::Input-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">Идентификатор платформы, DC - Delivery Club, YE - Yandex Eda, LAVKA - Lavka, VSEAPTEKI - Vseapteki.</div>
                                <!--end::Description-->
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>

                        </div>
                    </div>
                    <!--end::Body-->
                </div>
                <!--begin::Body-->
            </div>
        </div>

        <div class="col-6 mt-5">
            <div class="card mb-xl-8">
                <!--begin::Header-->
                <div class="card-header pt-5">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start w-100 mb-10">
                        <span class="card-label fw-bolder text-dark">Информация о доставке</span>
                    </h3>
                </div>
                <!--end::Header-->

                <!--begin::Body-->
                <div class="card-body py-3">
                    <!--begin::Body-->
                    <div class="card-body pt-0 px-0">
                        <div class="w-100">

                            <div class="mb-6 w-100">
                                <!--begin::Label-->
                                <label class="form-label">Дата, когда придет клиент в в торговую точку</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" disabled class="form-control mb-2" placeholder="ID заказа в нашей системе" value="{{ $order->delivery_info['courierArrivementDate'] }}">
                                <!--end::Input-->
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>

                            <div class="mb-6 w-100">
                                <!--begin::Label-->
                                <label class="form-label">ФИО клиента</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" disabled class="form-control mb-2" placeholder="ФИО клиента" value="{{ $order->delivery_info['clientName'] ?? '-' }}">
                                <!--end::Input-->
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>

                            <div class="mb-6 w-100">
                                <!--begin::Label-->
                                <label class="form-label">Слотовая доставка?</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" disabled class="form-control mb-2" placeholder="Слотовая доставка?" value="{{ $order->delivery_info['isSlotDelivery'] ? 'Да' : 'Нет' }}">
                                <!--end::Input-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">Заказ со слотовой доставкой (доставка ко времени).</div>
                                <!--end::Description-->
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>

                            <div class="mb-6 w-100">
                                <!--begin::Label-->
                                <label class="form-label">Номер телефона клиента</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" disabled class="form-control mb-2" placeholder="Номер телефона клиента" value="{{ $order->delivery_info['realPhoneNumber'] ?? '-' }}">
                                <!--end::Input-->
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>

                        </div>
                    </div>
                    <!--end::Body-->
                </div>
                <!--begin::Body-->
            </div>

            <div class="card mb-xl-8">
                <!--begin::Header-->
                <div class="card-header pt-5">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start w-100 mb-10">
                        <span class="card-label fw-bolder text-dark">Информация о платеже</span>
                    </h3>
                </div>
                <!--end::Header-->

                <!--begin::Body-->
                <div class="card-body py-3">
                    <!--begin::Body-->
                    <div class="card-body pt-0 px-0">
                        <div class="w-100">

                            <div class="mb-6 w-100">
                                <!--begin::Label-->
                                <label class="form-label">Полная стоимость товаров в заказе</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" disabled class="form-control mb-2" placeholder="Полная стоимость товаров в заказе" value="{{ $order->payment_info['itemsCost'] }}">
                                <!--end::Input-->
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>

                            <div class="mb-6 w-100">
                                <!--begin::Label-->
                                <label class="form-label">Информация о типе оплаты</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" disabled class="form-control mb-2" placeholder="Информация о типе оплаты" value="{{ $order->payment_info['paymentType'] }}">
                                <!--end::Input-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">CARD — это оплаченный заказ клиентом на стороне Яндекс Еды, CASH — это неоплаченный заказ (используется в основном при самовывозе алкоголя).</div>
                                <!--end::Description-->
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>

                        </div>
                    </div>
                    <!--end::Body-->
                </div>
                <!--begin::Body-->
            </div>
        </div>
    </div>
</form>

@endsection

@section('scripts')

<script src="/assets/js/custom/utilities/modals/new-target.js" type="text/javascript"></script>

@endsection
