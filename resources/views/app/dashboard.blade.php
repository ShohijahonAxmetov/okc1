@extends('layouts.app')

@section('title', 'ГЛАВНАЯ')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'Главная',
'route' => 'dashboard'
]
]
])

@endsection
@if(auth()->user()->role == 'admin')
@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Post-->
    <div class="post flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="">
            <!--begin::Row-->
            <div class="row g-5 g-xl-10 mb-xl-10" style="height: 240px;">
                <!--begin::Col-->
                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-6 mb-5 mb-xl-0 h-100">
                    <!--begin::Chart widget 3-->
                    <div class="card card-flush overflow-hidden h-md-100" style="height: 210px!important;">
                        <!--begin::Header-->
                        <div class="card-header py-5">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-dark">Продажи в этом месяце</span>
                                <span class="text-gray-400 mt-1 fw-bold fs-6">Пользователи со всех каналов</span>
                            </h3>
                            <!--end::Title-->
                            <!--begin::Toolbar-->
                            <div class="card-toolbar">
                            </div>
                            <!--end::Toolbar-->
                        </div>
                        <!--end::Header-->
                        <!--begin::Card body-->
                        <div class="card-body d-flex justify-content-between flex-column pb-1 px-0">
                            <!--begin::Statistics-->
                            <div class="px-9 mb-5">
                                <!--begin::Statistics-->
                                <div class="d-flex mb-2">
                                    <span class="fs-2hx fw-bolder text-gray-800 me-2 lh-1 ls-n2 money_format">{{ $this_month_amount }}</span>
                                    <span class="fs-4 fw-bold text-gray-400 me-1">сум</span>
                                </div>
                                <!--end::Statistics-->
                            </div>
                            <!--end::Statistics-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Chart widget 3-->
                </div>
                <!--end::Col-->
                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-6 mb-md-5 mb-xl-10">
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-md-5 mb-xl-10 h-100">
                            <!--begin::Card widget 5-->
                            <div class="card card-flush h-md-100 mb-xl-10" style="height: 210px!important;">
                                <!--begin::Header-->
                                <div class="card-header pt-5">
                                    <!--begin::Title-->
                                    <div class="card-title d-flex flex-column">
                                        <!--begin::Info-->
                                        <div class="d-flex align-items-center">
                                            <!--begin::Amount-->
                                            <span class="fs-2hx fw-bolder text-dark me-2 lh-1 ls-n2">{{ $this_month_orders_count }}</span>
                                            <!--end::Amount-->
                                        </div>
                                        <!--end::Info-->
                                        <!--begin::Subtitle-->
                                        <span class="text-gray-400 pt-1 fw-bold fs-6">Заказы в этом месяце</span>
                                        <!--end::Subtitle-->
                                    </div>
                                    <!--end::Title-->
                                </div>
                                <!--end::Header-->
                            </div>
                            <!--end::Card widget 5-->
                        </div>
                        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-md-5 mb-xl-10 h-100">
                            <!--begin::Card widget 7-->
                            <div class="card card-flush h-md-100 mb-xl-10" style="height: 210px!important;">
                                <!--begin::Header-->
                                <div class="card-header pt-5">
                                    <!--begin::Title-->
                                    <div class="card-title d-flex flex-column">
                                        <!--begin::Amount-->
                                        <span class="fs-2hx fw-bolder text-dark me-2 lh-1 ls-n2">{{ $this_month_new_clients_count }}</span>
                                        <!--end::Amount-->
                                        <!--begin::Subtitle-->
                                        <span class="text-gray-400 pt-1 fw-bold fs-6">Новые клиенты в этом месяце</span>
                                        <!--end::Subtitle-->
                                    </div>
                                    <!--end::Title-->
                                </div>
                                <!--end::Header-->
                                <!--begin::Card body-->
                                <div class="card-body d-flex flex-column justify-content-end pe-0">
                                    <!--begin::Title-->
                                    <!-- <span class="fs-6 fw-boldest text-gray-800 d-block mb-2">Today’s Heroes</span> -->
                                    <!--end::Title-->
                                    <!--begin::Users group-->
                                    <div class="symbol-group symbol-hover flex-nowrap">
                                        @foreach($this_month_new_clients as $client)
                                        @if($client->img)
                                        <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="{{$client->name}}">
                                            <img alt="Pic" src="/upload/users/{{$client->img}}" />
                                        </div>
                                        @else
                                        <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="{{ $client->name }}">
                                            <span class="symbol-label bg-warning text-inverse-warning fw-bolder">{{ $client->name[0] ?? '-' }}</span>
                                        </div>
                                        @endif
                                        @endforeach
                                        @if($this_month_new_clients_count > 8)
                                        <a class="symbol symbol-35px symbol-circle" data-bs-toggle="modal" data-bs-target="#kt_modal_view_users">
                                            <span class="symbol-label bg-light text-gray-400 fs-8 fw-bolder">+{{ $this_month_new_clients_count - 8 }}</span>
                                        </a>
                                        @endif
                                    </div>
                                    <!--end::Users group-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card widget 7-->
                        </div>
                    </div>
                </div>
            </div>
            <!--begin::Row-->
            <div class="row gy-5 g-xl-10">
                <!--begin::Col-->
                <div class="col-xl-12 mb-5 mb-xl-10">
                    <!--begin::Table Widget 4-->
                    <div class="card card-flush h-xl-100">
                        <!--begin::Card header-->
                        <div class="card-header pt-7">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-800">Заказы на сегодня</span>
                                <!-- <span class="text-gray-400 mt-1 fw-bold fs-6">Avg. 57 orders per day</span> -->
                            </h3>
                            <!--end::Title-->
                            <!--begin::Actions-->
                            <div class="card-toolbar">
                            <button type="button" id="clear_btn" class="d-flex justify-content-center align-items-center btn rounded-circle me-2 p-0" style="width: 28px;height: 28px">
                                <span class="svg-icon svg-icon-muted svg-icon-2hx">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                                        <rect x="7" y="15.3137" width="12" height="2" rx="1" transform="rotate(-45 7 15.3137)" fill="currentColor" />
                                        <rect x="8.41422" y="7" width="12" height="2" rx="1" transform="rotate(45 8.41422 7)" fill="currentColor" />
                                    </svg>
                                </span>
                            </button>
                            <form action="{{ route('dashboard') }}" class="d-flex align-items-center">
                                <div class="d-flex align-items-center position-relative my-1 ms-4">
                                    <select class="form-control form-select form-control-solid w-150px" name="status" data-control="select2" data-hide-search="true">
                                        <option value="">Выберите статус</option>
                                        <option value="new" {{ $status == 'new' ? 'selected' : '' }}>Новый</option>
                                        <option value="accepted" {{ $status == 'accepted' ? 'selected' : '' }}>Принят</option>
                                        <option value="collected" {{ $status == 'collected' ? 'selected' : '' }}>Собран</option>
                                        <option value="on_the_way" {{ $status == 'on_the_way' ? 'selected' : '' }}>В пути</option>
                                        <option value="done" {{ $status == 'done' ? 'selected' : '' }}>Доставлен</option>
                                        <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Отменено</option>
                                        <option value="returned" {{ $status == 'returned' ? 'selected' : '' }}>Возврат</option>
                                    </select>
                                </div>
                                <button class="btn btn-success ms-2" style="height: min-content;">Поиск</button>
                            </form>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-2">
                            <!--begin::Table-->
                            <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_4_table">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-100px">НОМЕР ЗАКАЗА</th>
                                        <th class="text-end min-w-100px">Дата ЗАКАЗА</th>
                                        <th class="text-end min-w-125px">Клиент</th>
                                        <th class="text-end min-w-100px">Сумма (в сумах)</th>
                                        <th class="text-end min-w-50px">Статус</th>
                                        <th class="text-end"></th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-bolder text-gray-600">
                                    @foreach($this_day_orders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('orders.show', ['id' => $order->id]) }}" class="text-gray-800 text-hover-primary">#{{ $order->id }}</a>
                                        </td>
                                        <td class="text-end">{{ date('H:i d-m-Y', strtotime($order->created_at)) }}</td>
                                        <td class="text-end">
                                            <a href="#" class="text-gray-600 text-hover-primary">{{ isset($order->user) ? $order->user->name : '--' }}</a>
                                        </td>
                                        <td class="text-end money_format">
                                            @if($order->payment_method == 'cash')
                                            {{ $order->amount / 100 }}
                                            @else
                                            @if($order->payment_card == 'payme')
                                            {{ $order->amount / 100 }}
                                            @elseif($order->payment_card == 'click')
                                            {{ $order->amount }}
                                            @endif
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @switch($order->status)
                                            @case('new')
                                            <span class="badge fs-7 fw-bold text-uppercase" style="background-color: rgb(13,202,240);">Новый</span>
                                            @break
                                            @case('accepted')
                                            <span class="badge fs-7 fw-bold text-uppercase bg-dark">Принят</span>
                                            @break
                                            @case('collected')
                                            <span class="badge fs-7 fw-bold text-uppercase" style="background-color: rgb(13,110,253);">Собран</span>
                                            @break
                                            @case('on_the_way')
                                            <span class="badge fs-7 fw-bold text-uppercase" style="background-color: rgb(225,193,7);">В пути</span>
                                            @break
                                            @case('returned')
                                            <span class="badge fs-7 fw-bold text-uppercase" style="background-color: #ff39f9;">Возврат</span>
                                            @break
                                            @case('done')
                                            <span class="badge fs-7 fw-bold text-uppercase" style="background-color: rgb(25,135,84);">Доставлен</span>
                                            @break
                                            @case('cancelled')
                                            <span class="badge fs-7 fw-bold text-uppercase" style="background-color: rgb(220,53,69);">Отменено</span>
                                            @break
                                            @endswitch
                                            <!-- <span class="badge py-3 px-4 fs-7 badge-light-warning">Pending</span> -->
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <!--end::Table body-->
                            </table>
                            <!--end::Table-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Table Widget 4-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <div class="row mb-10">
                <div class="col-12">
                    <div class="card">
                        <!--begin::Card body-->
                        <div class="card-body d-flex justify-content-between flex-column pb-1 px-0">
                            <!--begin::Chart-->
                            <div id="dashboard_chart" class="min-h-auto ps-4 pe-6"></div>
                            <!--end::Chart-->
                        </div>
                        <!--end::Card body-->
                    </div>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>

@endsection

@section('scripts')

<script>
    var options = {
        chart: {
            type: 'area',
        },
        series: [{
            name: 'Сумма (в сумах)',
            data: [{{
                    implode(',', $orders_for_chart['amount'])
                }}]
        }],
        xaxis: {
            categories: [{{
                    implode(',', $orders_for_chart['day'])
                }}]
        },
        dataLabels: {
          enabled: false
        },
    }

    var chart = new ApexCharts(document.querySelector("#dashboard_chart"), options);

    chart.render();

    // str to money format 
    var item = document.querySelector('.money_format');
    var reverse_text = item.innerText.split("").reverse().join("");

    var nado_delit_na_chasti = Math.floor(reverse_text.length / 3);
    var tochno = reverse_text.length % 3;

    arr = [];
    var counter_for_loop = tochno == 0 ? nado_delit_na_chasti : parseInt(nado_delit_na_chasti) + 1
    for (var i = 0; i < counter_for_loop; i++) {
        arr.push(reverse_text.slice(i * 3, i * 3 + 3));
    }

    var result_text = arr.join(' ');
    item.innerText = result_text.split("").reverse().join("");


    // filter clear
    document.getElementById('clear_btn').addEventListener('click', function() {
        document.getElementsByClassName('select2-selection__rendered').forEach((element, index) => {
            if (index == 0) {
                element.innerText = 'Выберите статус';
            }
        });
        document.querySelector("[name='status']").value = '';
    });
</script>

@endsection
@endif