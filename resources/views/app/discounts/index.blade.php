@extends('layouts.app')

@section('title', 'СКИДКИ')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'Главная',
'route' => 'dashboard'
],
[
'title' => 'Скидки'
]
]
])

@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">Скидки</span>
            <!-- <span class="text-muted mt-1 fw-bold fs-7">Last 12 applications</span> -->
        </h3>
        <div class="card-toolbar">
        </div>
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
                        <th class="ps-4 min-w-75px rounded-start">ID</th>
                        <th class="min-w-125px">Скидка на</th>
                        <th class="min-w-200">Скидочные продукты</th>
                        <th class="min-w-200">Интервал ( в сумах )</th>
                        <th class="min-w-125px">Значение</th>
                        <th class="min-w-75px rounded-end">Статус</th>
                    </tr>
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody>
                    @foreach($discounts as $discount)
                    <tr>
                        <td class="ps-3 fw-bold">
                            <p>#{{ $discount->id }}</p>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="d-flex justify-content-start flex-column ms-2">
                                    <a class="text-dark fw-bolder text-hover-primary d-block mb-1 fs-6">
                                        @switch($discount->discount_type)
                                        @case('order')
                                        Заказ
                                        @break
                                        @case('brand')
                                        Бренд
                                        @break
                                        @case('product')
                                        Продукты
                                        @break
                                        @case('category')
                                        Категорию
                                        @break
                                        @default
                                        @break
                                        @endswitch
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a class="text-dark fw-bolder text-hover-primary mb-1 fs-6">
                                @switch($discount->discount_type)
                                @case('order')
                                Все продукты
                                @break
                                @case('brand')
                                На все продукты бренда {{ $discount->venkon_brand_id }}
                                @break
                                @case('product')
                                @foreach($discount->venkon_product_id as $product)
                                @php
                                $product = \App\Models\ProductVariation::where('integration_id', $product)->first();
                                @endphp
                                <a href="{{ route('products.edit', ['product' => $product->product]) }}">- {{ $product->product->title['ru'] ?? '--' }}</a><br>
                                @endforeach
                                @break
                                @case('category')
                                На все продукты категории {{ $discount->venkon_category_id }}
                                @break
                                @default
                                @break
                                @endswitch
                            </a>
                        </td>
                        <td>
                            <a class="text-dark fw-bolder text-hover-primary d-block mb-1 fs-6">
                                @if($discount->from_amount == 0 && $discount->to_amount == 0)
                                На любую сумму
                                @else
                                <span class="money_format">{{ $discount->from_amount }}</span>- <span class="money_format">{{ $discount->to_amount }}</span>
                                @endif
                            </a>
                        </td>
                        <td>
                            <a class="text-dark fw-bolder text-hover-primary d-block mb-1 fs-6">
                                {{ $discount->discount }}
                                @switch($discount->amount_type)
                                @case('percent')
                                %
                                @break
                                @case('fixed')
                                сум
                                @break
                                @default
                                @break
                                @endswitch
                            </a>
                        </td>
                        <td>
                            <a class="text-dark fw-bolder text-hover-primary d-block mb-1 fs-6">
                                @if($discount->is_active)
                                <span class="d-flex badge badge-success rounded-circle" style="width: 24px;height: 24px;"></span>
                                @else
                                <span class="d-flex badge badge-danger rounded-circle" style="width: 24px;height: 24px;"></span>
                                @endif
                            </a>
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

    {{ $discounts->links() }}

</div>

@endsection