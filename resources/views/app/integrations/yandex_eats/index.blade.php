@extends('layouts.app')

@section('title', 'Интеграции')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'Главная',
'route' => 'dashboard'
],
[
'title' => 'Интеграции'
]
]
])

@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
    <!--begin::Body-->
    <div class="card-body py-3">
        <div class="list-group">
            <a href="{{route('integrations.yandex_eats.products')}}" class="list-group-item list-group-item-action fs-3">Продукты</a>
            <!-- <a href="#" class="list-group-item list-group-item-action fs-3">Теги</a> -->
            <!-- <a href="{{route('integrations.yandex_market.branches')}}" class="list-group-item list-group-item-action fs-3">Филиалы</a> -->
            <!-- <a href="{{route('integrations.yandex_market.config')}}" class="list-group-item list-group-item-action fs-3">Настройки</a> -->
        </div>
    </div>
    <!--begin::Body-->
</div>

@endsection