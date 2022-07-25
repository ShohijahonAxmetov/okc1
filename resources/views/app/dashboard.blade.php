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

@endsection
