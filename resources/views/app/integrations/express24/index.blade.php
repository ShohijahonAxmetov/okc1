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
            <a href="{{route('integrations.express24.categories')}}" class="list-group-item list-group-item-action fs-3">Категории</a>
            <a href="{{route('integrations.express24.branches')}}" class="list-group-item list-group-item-action fs-3">Филиалы</a>
            <a href="{{route('integrations.express24.config')}}" class="list-group-item list-group-item-action fs-3">Настройки</a>
        </div>
    </div>
    <!--begin::Body-->
</div>

@endsection