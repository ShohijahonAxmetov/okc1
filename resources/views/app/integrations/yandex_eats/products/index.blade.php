@extends('layouts.app')

@php
$remainderRouteParams = [];
$remainderRouteParams = request()->sort == 'remainder-asc' ? ['sort' => 'remainder-desc'] : ['sort' => 'remainder-asc'];
if ($warehouse != '') $remainderRouteParams['warehouse'] = $warehouse;

$idRouteParams = [];
$idRouteParams = request()->sort == 'asc' ? ['sort' => 'desc'] : ['sort' => 'asc'];
if ($warehouse != '') $idRouteParams['warehouse'] = $warehouse;

$nameRouteParams = [];
$nameRouteParams = request()->sort == 'title-asc' ? ['sort' => 'title-desc'] : ['sort' => 'title-asc'];
if ($warehouse != '') $nameRouteParams['warehouse'] = $warehouse;
@endphp

@section('title', 'ПРОДУКТЫ')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'Главная',
'route' => 'dashboard'
],
[
'title' => 'YandexEats',
'route' => 'integrations.yandex_eats.index'
],
[
'title' => 'Продукты'
]
]
])

@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">Продукты</span>
            <span class="text-muted mt-1 fw-bold fs-7">Количество продуктов: {{ count($products) }} </span>
        </h3>
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
            <form action="{{ route('integrations.yandex_eats.products') }}" class="d-flex align-items-center">
                @if(request()->sort)
                <input type="hidden" name="sort" value="{{request()->sort}}">
                @endif
                <div class="d-flex align-items-center position-relative my-1">
                    <select class="form-control form-select form-control-solid w-250px" name="warehouse" data-control="select2" data-hide-search="false">
                        <option value="">Выберите магазин</option>
                        @foreach($warehouses as $item)
                        <option value="{{ $item->integration_id }}" {{ $warehouse == $item->integration_id ? 'selected' : '' }}>{{ $item->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex align-items-center position-relative my-1 ms-4">
                    <span class="svg-icon svg-icon-1 position-absolute ms-4">
                    </span>
                </div>
                <button class="btn btn-primary ms-2" style="height: min-content;">Поиск</button>
            </form>

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
                        <th class="ps-4 min-w-125px rounded-start"><a href="{{route('products.index', $idRouteParams)}}">ID <svg xmlns="http://www.w3.org/2000/svg" style="fill: #009ef7;width: 14px;" viewBox="0 0 576 512"><path d="M450.7 38c8.3 6 13.3 15.7 13.3 26l0 96 16 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-48 0-48 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l16 0 0-51.6-5.9 2c-16.8 5.6-34.9-3.5-40.5-20.2s3.5-34.9 20.2-40.5l48-16c9.8-3.3 20.5-1.6 28.8 4.4zM160 32c9 0 17.5 3.8 23.6 10.4l88 96c11.9 13 11.1 33.3-2 45.2s-33.3 11.1-45.2-2L192 146.3 192 448c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-301.7L95.6 181.6c-11.9 13-32.2 13.9-45.2 2s-13.9-32.2-2-45.2l88-96C142.5 35.8 151 32 160 32zM445.7 364.9A32 32 0 1 0 418.3 307a32 32 0 1 0 27.4 57.9zm-40.7 54.9C369.6 408.4 344 375.2 344 336c0-48.6 39.4-88 88-88s88 39.4 88 88c0 23.5-7.5 46.3-21.5 65.2L449.7 467c-10.5 14.2-30.6 17.2-44.8 6.7s-17.2-30.6-6.7-44.8l6.8-9.2z"/></svg></a></th>
                        <th class="ps-4 min-w-325px"><a href="{{route('products.index', $nameRouteParams)}}">Продукт <svg xmlns="http://www.w3.org/2000/svg" style="fill: #009ef7;width: 14px;" viewBox="0 0 576 512"><path d="M183.6 42.4C177.5 35.8 169 32 160 32s-17.5 3.8-23.6 10.4l-88 96c-11.9 13-11.1 33.3 2 45.2s33.3 11.1 45.2-2L128 146.3 128 448c0 17.7 14.3 32 32 32s32-14.3 32-32l0-301.7 32.4 35.4c11.9 13 32.2 13.9 45.2 2s13.9-32.2 2-45.2l-88-96zM320 64c0 17.7 14.3 32 32 32l50.7 0-73.4 73.4c-9.2 9.2-11.9 22.9-6.9 34.9s16.6 19.8 29.6 19.8l128 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-50.7 0 73.4-73.4c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L352 32c-17.7 0-32 14.3-32 32zm96 192c-12.1 0-23.2 6.8-28.6 17.7l-64 128-16 32c-7.9 15.8-1.5 35 14.3 42.9s35 1.5 42.9-14.3l7.2-14.3 88.4 0 7.2 14.3c7.9 15.8 27.1 22.2 42.9 14.3s22.2-27.1 14.3-42.9l-16-32-64-128C439.2 262.8 428.1 256 416 256zM395.8 400L416 359.6 436.2 400l-40.4 0z"/></svg></a></th>
                        <th class="min-w-125px">Бренд</th>
                        <th class="min-w-125px"><a href="{{route('products.index', $remainderRouteParams)}}">Остаток <svg xmlns="http://www.w3.org/2000/svg" style="fill: #009ef7;width: 14px;" viewBox="0 0 576 512"><path d="M450.7 38c8.3 6 13.3 15.7 13.3 26l0 96 16 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-48 0-48 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l16 0 0-51.6-5.9 2c-16.8 5.6-34.9-3.5-40.5-20.2s3.5-34.9 20.2-40.5l48-16c9.8-3.3 20.5-1.6 28.8 4.4zM160 32c9 0 17.5 3.8 23.6 10.4l88 96c11.9 13 11.1 33.3-2 45.2s-33.3 11.1-45.2-2L192 146.3 192 448c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-301.7L95.6 181.6c-11.9 13-32.2 13.9-45.2 2s-13.9-32.2-2-45.2l88-96C142.5 35.8 151 32 160 32zM445.7 364.9A32 32 0 1 0 418.3 307a32 32 0 1 0 27.4 57.9zm-40.7 54.9C369.6 408.4 344 375.2 344 336c0-48.6 39.4-88 88-88s88 39.4 88 88c0 23.5-7.5 46.3-21.5 65.2L449.7 467c-10.5 14.2-30.6 17.2-44.8 6.7s-17.2-30.6-6.7-44.8l6.8-9.2z"/></svg></a></th>
                        <th class="min-w-150px">Статус</th>
                        <th class="min-w-150px text-end rounded-end pe-2">Действия</th>
                    </tr>
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>
                            <a class="text-dark fw-bolder text-hover-primary d-block mb-1 fs-6">#{{$product->id }}</a>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-5">

                                    <img src="{{ $product && $product->productVariationImages->first() ? asset($product->productVariationImages->first()->img) : '/assets/media/default.png' }}" class="" alt="" style="object-fit:cover">
                                </div>
                                <div class="d-flex justify-content-start flex-column">
                                    <a class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{ $product->product->title['ru'] }}</a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="text-dark fw-bolder text-hover-primary d-block mb-1 fs-6">{{ isset($product->brand) ? $product->brand->title : '--' }}</a>
                        </td>
                        <td>
                            <span class="fs-6 fw-bold">{{ $product->pivot->remainder }}</span>
                        </td>
                        <td>
                            @if($product->is_active)
                            <span class="badge badge-light-success fs-7 fw-bold">Активный</span>
                            @else
                            <span class="badge badge-light-danger fs-7 fw-bold">Неактивный</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('integrations.yandex_eats.products.edit', ['product' => $product]) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm ms-4" title="Edit product">
                                    <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                            <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                </a>
                            </div>

                            <!-- <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
                                <span class="svg-icon svg-icon-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
                                        <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
                                        <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
                                    </svg>
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

@endsection

@section('scripts')

<script>
    document.getElementById('clear_btn').addEventListener('click', function() {
        document.getElementsByClassName('select2-selection__rendered').forEach((element, index) => {
            if (index == 0) {
                element.innerText = 'Выберите магазин';
            }
        });
        document.querySelector("[name='warehouse']").value = '';
    });
</script>

@endsection