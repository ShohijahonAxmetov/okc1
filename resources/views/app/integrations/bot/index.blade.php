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
            <a href="{{route('integrations.bot.messages')}}" class="list-group-item list-group-item-action fs-3">Рассылка</a>
            <a href="{{route('integrations.bot.users')}}" class="list-group-item list-group-item-action fs-3">Пользователи</a>
            <a href="{{route('integrations.bot.feedback')}}" class="list-group-item list-group-item-action fs-3">Обратная связь от пользователей</a>
        </div>
    </div>
    <!--begin::Body-->
</div>

@endsection