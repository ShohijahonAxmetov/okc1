@extends('layouts.app')

@section('title', 'DASHBOARD')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'home',
'route' => 'dashboard'
]
]
])

@endsection

@section('content')

@endsection