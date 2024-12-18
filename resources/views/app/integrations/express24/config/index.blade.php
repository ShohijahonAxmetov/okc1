@extends('layouts.app')

@section('title', 'Настройки')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'Главная',
'route' => 'dashboard'
],
[
'title' => 'Настройки'
]
]
])

@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
    <!--begin::Body-->
    <div class="card-body py-3">
        <form class="form" action="{{ route('integrations.express24.config.update') }}" method="post" id="form">
            @csrf

            <div class="fv-row mb-6">
                <!--begin::Label-->
                <label class="fs-6 fw-bold mb-2">Налог на добавленную стоимость (VAT)</label>
                <small>Не больше 12%</small>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="number" class="form-control form-control-solid" placeholder="" name="vat" value="{{old('vat', $config->vat)}}" />
                <!--end::Input-->
            </div>
            <div class="fv-row mb-6">
                <!--begin::Label-->
                <label class="fs-6 fw-bold mb-2">Повышение цен (%)</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="number" class="form-control form-control-solid" placeholder="10" name="price_up" value="{{old('price_up', $config->price_up)}}" />
                <!--end::Input-->
            </div>
            <div class="fv-row mb-6">
                <!--begin::Label-->
                <label class="fs-6 fw-bold mb-2">Кол-во продуктов которые доступны в Express24</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="number" class="form-control form-control-solid" placeholder="10" name="products_min_count" value="{{old('products_min_count', $config->products_min_count)}}" />
                <!--end::Input-->
            </div>
            
            <div class="d-flex justify-content-end mt-8">
                <button class="btn btn-success me-2 mb-2 px-8">Сохранить</button>
            </div>
        </form>
    </div>
    <!--begin::Body-->
</div>

@endsection