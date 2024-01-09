@extends('layouts.app')

@section('title', 'Остатки по складам')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'Главная',
'route' => 'dashboard'
],
[
'title' => 'Остатки по складам'
]
]
])

@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">Остатки по складам</span>
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
            <form action="{{ route('remainders.index') }}" class="d-flex align-items-center">
                <div class="d-flex align-items-center position-relative my-1">
                    <select class="form-control form-select form-control-solid w-150px" name="brand" data-control="select2" data-hide-search="false">
                        <option value="">Выберите бренда</option>
                        @foreach($brands as $item)
                        <option value="{{ $item->integration_id }}" {{ $brand == $item->integration_id ? 'selected' : '' }}>{{ $item->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex align-items-center position-relative my-1 ms-4">
                    <span class="svg-icon svg-icon-1 position-absolute ms-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" class="form-control form-control-solid w-250px ps-14" placeholder="Поиск продукта" value="{{ $search }}">
                </div>
                <button class="btn btn-success ms-2" style="height: min-content;">Поиск</button>
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
                        <th class="ps-4 min-w-325px rounded-start">
                            <span class="mb-2 d-flex">Продукт</span>
                            <span class="d-flex">
                                ( <span class="d-flex me-1 ms-1 bg-warning" style="width: 20px;height: 20px;"></span><span>- остались меньше 20 шт</span>
                                <span class="d-flex me-1 ms-3 bg-danger" style="width: 20px;height: 20px;"></span><span class="me-1">- остались меньше 5 шт</span> )
                            </span>
                        </th>
                        @foreach($warehouses as $warehouse)
                        <th class="min-w-75px border-start" style="vertical-align: bottom;text-align: center;"><span style="-ms-writing-mode: tb-rl;-webkit-writing-mode: vertical-rl;writing-mode: vertical-rl;transform: rotate(180deg);white-space: nowrap;">{{ $warehouse->title }}</span></th>
                        @endforeach
                    </tr>
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody>
                    @php
                    $total = 0;
                    @endphp

                    @foreach($variations as $variation)

                    @php
                    foreach($warehouses as $warehouse) {
                        if($variation->warehouses()->where('integration_id', $warehouse->integration_id)->first()) {
                            $total += $variation->warehouses()->where('integration_id', $warehouse->integration_id)->first()->pivot->remainder;
                        }
                    }
                    @endphp
                    <tr class="{{ $total < 20 && $total >= 5 ? 'bg-warning' : '' }} {{ $total < 5 ? 'bg-danger' : '' }}">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-5">

                                    <img src="{{ $variation && $variation->productVariationImages->first() ? asset($variation->productVariationImages->first()->img) : '/assets/media/default.png' }}" class="" alt="" style="object-fit:cover">
                                </div>
                                <div class="d-flex justify-content-start flex-column">
                                    <a class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{ $variation->product->title['ru'] }}</a>
                                    <span class="small">{{ $variation->integration_id }}, {{ $variation->id }}</span>
                                </div>
                            </div>
                        </td>
                        @foreach($warehouses as $warehouse)
                        <td class="text-end border-start text-center">
                            <span class="fs-6 fw-bold">{{ $variation->warehouses()->where('integration_id', $warehouse->integration_id)->first() ? $variation->warehouses()->where('integration_id', $warehouse->integration_id)->first()->pivot->remainder : 0 }}</span>
                        </td>
                        @endforeach
                    </tr>

                    @php
                    $total = 0;
                    @endphp

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

{!! $variations->links() !!}

@endsection

@section('scripts')

<script>
    document.getElementById('clear_btn').addEventListener('click', function() {
        document.getElementsByClassName('select2-selection__rendered').forEach((element, index) => {
            if (index == 0) {
                element.innerText = 'Выберите бренд';
            }
        });
        document.querySelector("[name='brand']").value = '';
        document.querySelector("[name='search']").value = '';
    });
</script>

@endsection