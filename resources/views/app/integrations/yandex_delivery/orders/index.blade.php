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
'title' => 'Заказы'
]
]
])

@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5 d-flex">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">Заказы</span>
            <span class="text-muted mt-1 fw-bold fs-7">Всего {{ count($orders) }}</span>
        </h3>
        <!-- <div>
            <a href="{{route('integrations.yandex_delivery.orders.create')}}" class="btn btn-success">Создать</a>
        </div> -->
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
                        <th class="ps-4 min-w-75px rounded-start">#</th>
                        <th class="min-w-150px">Заказ</th>
<!--                         <th class="min-w-125px">Точка отправления</th>
                        <th class="min-w-150px">Точка назначения</th> -->
                        <th class="min-w-125px">Статус</th>
                        <th class="min-w-150px">Предварительная сумма</th>
                        <th class="min-w-150px">Время создания</th>
                        <th class="min-w-75px text-end rounded-end pe-3"></th>
                    </tr>
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td class="ps-3 fw-bold">
                            <p>#{{ $loop->iteration }}</p>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="d-flex justify-content-start flex-column">
                                    
                                    <a class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{ 'ID:'.$order->order->id.', '.$order->order->name.', '.$order->order->phone_number }}</a>                                    
                                </div>
                            </div>
                        </td>
                        <!-- <td>
                            <a class="text-dark text-hover-primary d-block mb-1 fs-6">{{ $order->a_lat }}, {{ $order->a_lon }}</a>
                        </td>
                        <td>
                            <a class="text-dark text-hover-primary d-block mb-1 fs-6">{{ $order->b_lat }}, {{ $order->b_lon }}</a>
                        </td> -->
                        <!-- <td>
                            <a href="https://delivery.yandex.uz/account/cargo?order={{$order->claim_id}}" target="_blank" class="btn btn-warning d-block mb-1 fs-6">Отслеживать заказ</a>
                        </td> -->
                        <td>
                            <a class="text-dark d-block mb-1 fs-6">{{ $order->order_in_str['desc'] }}</a>
                        </td>
                        <td>
                            <a class="text-dark text-hover-primary d-block mb-1 fs-6">{{ $order->preliminary_amount ?? '--' }}</a>
                        </td>
                        <td>
                            <a class="text-dark text-hover-primary d-block mb-1 fs-6">{{ date('H:i d-m-Y', strtotime($order->created_at)) }}</a>
                        </td>
                        <td class="text-end">
                            <a href="https://delivery.yandex.uz/account/cargo?order={{$order->claim_id}}" target="_blank" class="btn btn-warning d-block mb-1 fs-6">Отслеживать заказ</a>
                            <!-- <a class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#edit_category{{ $order->id }}">
                                <span class="svg-icon svg-icon-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" height="800px" width="800px" version="1.1" id="Capa_1" viewBox="0 0 487.3 487.3" xml:space="preserve"><g><g><path d="M487.2,69.7c0,12.9-10.5,23.4-23.4,23.4h-322c-12.9,0-23.4-10.5-23.4-23.4s10.5-23.4,23.4-23.4h322.1    C476.8,46.4,487.2,56.8,487.2,69.7z M463.9,162.3H141.8c-12.9,0-23.4,10.5-23.4,23.4s10.5,23.4,23.4,23.4h322.1    c12.9,0,23.4-10.5,23.4-23.4C487.2,172.8,476.8,162.3,463.9,162.3z M463.9,278.3H141.8c-12.9,0-23.4,10.5-23.4,23.4    s10.5,23.4,23.4,23.4h322.1c12.9,0,23.4-10.5,23.4-23.4C487.2,288.8,476.8,278.3,463.9,278.3z M463.9,394.3H141.8    c-12.9,0-23.4,10.5-23.4,23.4s10.5,23.4,23.4,23.4h322.1c12.9,0,23.4-10.5,23.4-23.4C487.2,404.8,476.8,394.3,463.9,394.3z     M38.9,30.8C17.4,30.8,0,48.2,0,69.7s17.4,39,38.9,39s38.9-17.5,38.9-39S60.4,30.8,38.9,30.8z M38.9,146.8    C17.4,146.8,0,164.2,0,185.7s17.4,38.9,38.9,38.9s38.9-17.4,38.9-38.9S60.4,146.8,38.9,146.8z M38.9,262.8    C17.4,262.8,0,280.2,0,301.7s17.4,38.9,38.9,38.9s38.9-17.4,38.9-38.9S60.4,262.8,38.9,262.8z M38.9,378.7    C17.4,378.7,0,396.1,0,417.6s17.4,38.9,38.9,38.9s38.9-17.4,38.9-38.9C77.8,396.2,60.4,378.7,38.9,378.7z"/></g></g></svg>
                                </span>
                            </a> -->
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


@foreach($orders as $order)
<!--begin::Modal - New Product-->
<div class="modal fade" id="edit_category{{ $order['id'] }}" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <form class="form" action="{{ route('integrations.yandex_market.pin_category') }}" id="kt_modal_add_event_form" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{$order['integration_id']}}">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bolder" data-kt-calendar="title">Прикрепление категории к "{{ $order['name'] }}"</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->

                <!--begin::Modal footer-->
                <div class="modal-footer flex-center">
                    <!--begin::Button-->
                    <button type="reset" id="kt_modal_add_event_cancel" class="btn btn-light me-3" data-bs-dismiss="modal">Отмена</button>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">Сохранить</span>
                    </button>
                    <!--end::Button-->
                </div>
                <!--end::Modal footer-->
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>
<!--end::Modal - New Product-->
@endforeach

@endsection

@section('scripts')

<script>
    document.getElementById('clear_btn').addEventListener('click', function() {
        document.querySelector("[name='search']").value = '';
    });
</script>

@endsection