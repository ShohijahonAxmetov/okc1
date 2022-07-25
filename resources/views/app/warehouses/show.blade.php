@extends('layouts.app')

@section('title', 'СКЛАДЫ')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'Главная',
'route' => 'dashboard'
],
[
'title' => 'Склады'
]
]
])

@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column" style="min-width:250px">
            <span class="card-label fw-bolder fs-3 mb-1 mb-4">Склады</span>

            <!--begin::Navs-->
            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder">
                @foreach($warehouses as $warehouse)
                <!--begin::Nav item-->
                <li class="nav-item mt-2">
                    <a href="{{ route('warehouses.show', ['id' => $warehouse->venkon_id]) }}" class="nav-link text-active-primary ms-0 me-10 py-5 {{ $warehouse->venkon_id == $id ? 'active' : '' }}">{{ $warehouse->title }}</a>
                </li>
                @endforeach
                <!--end::Nav item-->
            </ul>
            <!--begin::Navs-->
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
                        <th class="ps-4 min-w-325px rounded-start">Продукт</th>
                        <th class="min-w-125px">Бренд</th>
                        <th class="min-w-150px text-end rounded-end pe-2">Остаток</th>
                    </tr>
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody>
                    @if($products)
                    @foreach($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-5">

                                    <img src="{{ $product->productVariationImages->first() ? asset($product->productVariationImages->first()->img) : '/assets/media/default.png' }}" class="" alt="" style="object-fit:cover">
                                </div>
                                <div class="d-flex justify-content-start flex-column">
                                    <a href="#" class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{ $product->product->title['ru'] }}</a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a class="text-dark fw-bolder text-hover-primary d-block mb-1 fs-6">{{ isset($product->product->brand) ? $product->product->brand->title : '--' }}</a>
                        </td>
                        <td class="text-end">
                        <a class="text-dark fw-bolder text-hover-primary d-block mb-1 fs-6">{{ isset($product->pivot->remainder) ? $product->pivot->remainder : '--' }}</a>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
                <!--end::Table body-->
            </table>
            <!--end::Table-->
        </div>
        <!--end::Table container-->
    </div>
    <!--begin::Body-->
</div>
@if($products)
{!! $products->links() !!}
@endif
@endsection
