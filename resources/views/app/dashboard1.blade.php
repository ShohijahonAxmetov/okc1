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

@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
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
                                    <span class="fs-6 fw-boldest text-gray-800 d-block mb-2">Today’s Heroes</span>
                                    <!--end::Title-->
                                    <!--begin::Users group-->
                                    <div class="symbol-group symbol-hover flex-nowrap">
                                        <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Alan Warden">
                                            <span class="symbol-label bg-warning text-inverse-warning fw-bolder">A</span>
                                        </div>
                                        <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Michael Eberon">
                                            <img alt="Pic" src="assets/media/avatars/300-11.jpg" />
                                        </div>
                                        <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Susan Redwood">
                                            <span class="symbol-label bg-primary text-inverse-primary fw-bolder">S</span>
                                        </div>
                                        <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Melody Macy">
                                            <img alt="Pic" src="assets/media/avatars/300-2.jpg" />
                                        </div>
                                        <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Perry Matthew">
                                            <span class="symbol-label bg-danger text-inverse-danger fw-bolder">P</span>
                                        </div>
                                        <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Barry Walter">
                                            <img alt="Pic" src="assets/media/avatars/300-12.jpg" />
                                        </div>
                                        <a href="#" class="symbol symbol-35px symbol-circle" data-bs-toggle="modal" data-bs-target="#kt_modal_view_users">
                                            <span class="symbol-label bg-light text-gray-400 fs-8 fw-bolder">+42</span>
                                        </a>
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
            <!--end::Row-->
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
                                <!--begin::Filters-->
                                <div class="d-flex flex-stack flex-wrap gap-4">
                                    <!--begin::Destination-->
                                    <div class="d-flex align-items-center fw-bolder">
                                        <!--begin::Label-->
                                        <div class="text-gray-400 fs-7 me-2">Категория</div>
                                        <!--end::Label-->
                                        <!--begin::Select-->
                                        <select class="form-select form-select-transparent text-graY-800 fs-base lh-1 fw-bolder py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select an option">
                                            <option></option>
                                            <option value="Show All" selected="selected">Все</option>
                                            <option value="a">Category A</option>
                                            <option value="b">Category A</option>
                                        </select>
                                        <!--end::Select-->
                                    </div>
                                    <!--end::Destination-->
                                    <!--begin::Status-->
                                    <div class="d-flex align-items-center fw-bolder">
                                        <!--begin::Label-->
                                        <div class="text-gray-400 fs-7 me-2">Статус</div>
                                        <!--end::Label-->
                                        <!--begin::Select-->
                                        <select class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bolder py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select an option" data-kt-table-widget-4="filter_status">
                                            <option></option>
                                            <option value="Show All" selected="selected">Все</option>
                                            <option value="Shipped">Shipped</option>
                                            <option value="Confirmed">Confirmed</option>
                                            <option value="Rejected">Rejected</option>
                                            <option value="Pending">Pending</option>
                                        </select>
                                        <!--end::Select-->
                                    </div>
                                    <!--end::Status-->
                                </div>
                                <!--begin::Filters-->
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
                                        <td class="text-end money_format">{{ $order->amount }}</td>
                                        <td class="text-end">
                                            <span class="badge py-3 px-4 fs-7 badge-light-warning">Pending</span>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-25px w-25px" data-kt-table-widget-4="expand_row">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr087.svg-->
                                                <span class="svg-icon svg-icon-3 m-0 toggle-off">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                                                        <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr089.svg-->
                                                <span class="svg-icon svg-icon-3 m-0 toggle-on">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                            </button>
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
            <!--begin::Row-->
            <div class="row gy-5 g-xl-10">
                <!--begin::Col-->
                <div class="col-xl-12">
                    <!--begin::Table Widget 5-->
                    <div class="card card-flush h-xl-100">
                        <!--begin::Card header-->
                        <div class="card-header pt-7">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-dark">Отчет о запасах</span>
                                <span class="text-gray-400 mt-1 fw-bold fs-6">Всего 2356 единиц товара на складе</span>
                            </h3>
                            <!--end::Title-->
                            <!--begin::Actions-->
                            <div class="card-toolbar">
                                <!--begin::Filters-->
                                <div class="d-flex flex-stack flex-wrap gap-4">
                                    <!--begin::Destination-->
                                    <div class="d-flex align-items-center fw-bolder">
                                        <!--begin::Label-->
                                        <div class="text-muted fs-7 me-2">Категория</div>
                                        <!--end::Label-->
                                        <!--begin::Select-->
                                        <select class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bolder py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select an option">
                                            <option></option>
                                            <option value="Show All" selected="selected">Показать все</option>
                                            <option value="a">Category A</option>
                                            <option value="b">Category B</option>
                                        </select>
                                        <!--end::Select-->
                                    </div>
                                    <!--end::Destination-->
                                    <!--begin::Status-->
                                    <div class="d-flex align-items-center fw-bolder">
                                        <!--begin::Label-->
                                        <div class="text-muted fs-7 me-2">СТАТУС</div>
                                        <!--end::Label-->
                                        <!--begin::Select-->
                                        <select class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bolder py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select an option" data-kt-table-widget-5="filter_status">
                                            <option></option>
                                            <option value="Show All" selected="selected">Показать все</option>
                                            <option value="In Stock">In Stock</option>
                                            <option value="Out of Stock">Out of Stock</option>
                                            <option value="Low Stock">Low Stock</option>
                                        </select>
                                        <!--end::Select-->
                                    </div>
                                    <!--end::Status-->
                                </div>
                                <!--begin::Filters-->
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body">
                            <!--begin::Table-->
                            <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_5_table">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-100px">Продукт</th>
                                        <th class="text-end pe-3 min-w-100px">ID Продукта</th>
                                        <th class="text-end pe-3 min-w-150px">Дата последнего добавления</th>
                                        <th class="text-end pe-3 min-w-100px">Цена (в сумах)</th>
                                        <th class="text-end pe-3 min-w-50px">Статус</th>
                                        <th class="text-end pe-0 min-w-25px">Кол-во</th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-bolder text-gray-600">
                                    @foreach($stock as $product)
                                    <tr>
                                        <!--begin::Item-->
                                        <td>
                                            <a href="../../demo1/dist/apps/ecommerce/catalog/edit-product.html" class="text-dark text-hover-primary">{{ $product->product->title['ru'] }}</a>
                                        </td>
                                        <!--end::Item-->
                                        <!--begin::Product ID-->
                                        <td class="text-end">#{{ $product->venkon_id }}</td>
                                        <!--end::Product ID-->
                                        <!--begin::Date added-->
                                        <td class="text-end">{{ date('H:i d-m-Y', strtotime($product->updated_at)) }}</td>
                                        <!--end::Date added-->
                                        <!--begin::Price-->
                                        <td class="text-end money_format">{{ $product->price }}</td>
                                        <!--end::Price-->
                                        <!--begin::Status-->
                                        <td class="text-end">
                                            <span class="badge py-3 px-4 fs-7 badge-light-primary">In Stock</span>
                                        </td>
                                        <!--end::Status-->
                                        <!--begin::Qty-->
                                        <td class="text-end" data-order="290">
                                            <span class="text-dark fw-bolder">{{ $product->remainder }}</span>
                                        </td>
                                        <!--end::Qty-->
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <!--begin::Item-->
                                        <td>
                                            <a href="../../demo1/dist/apps/ecommerce/catalog/edit-product.html" class="text-dark text-hover-primary">AudioEngine HD3</a>
                                        </td>
                                        <!--end::Item-->
                                        <!--begin::Product ID-->
                                        <td class="text-end">#PXF-578</td>
                                        <!--end::Product ID-->
                                        <!--begin::Date added-->
                                        <td class="text-end">24 Mar, 2022</td>
                                        <!--end::Date added-->
                                        <!--begin::Price-->
                                        <td class="text-end">$1,060</td>
                                        <!--end::Price-->
                                        <!--begin::Status-->
                                        <td class="text-end">
                                            <span class="badge py-3 px-4 fs-7 badge-light-danger">Out of Stock</span>
                                        </td>
                                        <!--end::Status-->
                                        <!--begin::Qty-->
                                        <td class="text-end" data-order="46">
                                            <span class="text-dark fw-bolder">46 PCS</span>
                                        </td>
                                        <!--end::Qty-->
                                    </tr>
                                    <tr>
                                        <!--begin::Item-->
                                        <td>
                                            <a href="../../demo1/dist/apps/ecommerce/catalog/edit-product.html" class="text-dark text-hover-primary">HP Hyper LTR</a>
                                        </td>
                                        <!--end::Item-->
                                        <!--begin::Product ID-->
                                        <td class="text-end">#PXF-778</td>
                                        <!--end::Product ID-->
                                        <!--begin::Date added-->
                                        <td class="text-end">16 Jan, 2022</td>
                                        <!--end::Date added-->
                                        <!--begin::Price-->
                                        <td class="text-end">$4500</td>
                                        <!--end::Price-->
                                        <!--begin::Status-->
                                        <td class="text-end">
                                            <span class="badge py-3 px-4 fs-7 badge-light-primary">In Stock</span>
                                        </td>
                                        <!--end::Status-->
                                        <!--begin::Qty-->
                                        <td class="text-end" data-order="78">
                                            <span class="text-dark fw-bolder">78 PCS</span>
                                        </td>
                                        <!--end::Qty-->
                                    </tr>
                                    <tr>
                                        <!--begin::Item-->
                                        <td>
                                            <a href="../../demo1/dist/apps/ecommerce/catalog/edit-product.html" class="text-dark text-hover-primary">Dell 32 UltraSharp</a>
                                        </td>
                                        <!--end::Item-->
                                        <!--begin::Product ID-->
                                        <td class="text-end">#XGY-356</td>
                                        <!--end::Product ID-->
                                        <!--begin::Date added-->
                                        <td class="text-end">22 Dec, 2022</td>
                                        <!--end::Date added-->
                                        <!--begin::Price-->
                                        <td class="text-end">$1,060</td>
                                        <!--end::Price-->
                                        <!--begin::Status-->
                                        <td class="text-end">
                                            <span class="badge py-3 px-4 fs-7 badge-light-warning">Low Stock</span>
                                        </td>
                                        <!--end::Status-->
                                        <!--begin::Qty-->
                                        <td class="text-end" data-order="8">
                                            <span class="text-dark fw-bolder">8 PCS</span>
                                        </td>
                                        <!--end::Qty-->
                                    </tr>
                                    <tr>
                                        <!--begin::Item-->
                                        <td>
                                            <a href="../../demo1/dist/apps/ecommerce/catalog/edit-product.html" class="text-dark text-hover-primary">Google Pixel 6 Pro</a>
                                        </td>
                                        <!--end::Item-->
                                        <!--begin::Product ID-->
                                        <td class="text-end">#XVR-425</td>
                                        <!--end::Product ID-->
                                        <!--begin::Date added-->
                                        <td class="text-end">27 Dec, 2022</td>
                                        <!--end::Date added-->
                                        <!--begin::Price-->
                                        <td class="text-end">$1,060</td>
                                        <!--end::Price-->
                                        <!--begin::Status-->
                                        <td class="text-end">
                                            <span class="badge py-3 px-4 fs-7 badge-light-primary">In Stock</span>
                                        </td>
                                        <!--end::Status-->
                                        <!--begin::Qty-->
                                        <td class="text-end" data-order="124">
                                            <span class="text-dark fw-bolder">124 PCS</span>
                                        </td>
                                        <!--end::Qty-->
                                    </tr>
                                </tbody>
                                <!--end::Table body-->
                            </table>
                            <!--end::Table-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Table Widget 5-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
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
    for(var i=0; i<counter_for_loop; i++) {
        arr.push(reverse_text.slice(i*3, i*3+3));
    }

    var result_text = arr.join(' ');
    item.innerText = result_text.split("").reverse().join("");


</script>

@endsection