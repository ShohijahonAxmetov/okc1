@extends('layouts.app')

@section('title', 'WAREHOUSES')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'home',
'route' => 'dashboard'
],
[
'title' => 'warehouses'
]
]
])

@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column" style="min-width:250px">
            <span class="card-label fw-bolder fs-3 mb-1 mb-4">Warehouses</span>
            <p class="mb-0 mt-6">Select warehouse</p>
            <!--begin::Navs-->
            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder">
                @foreach($warehouses as $warehouse)
                <!--begin::Nav item-->
                <li class="nav-item mt-2">
                    <a href="{{ route('warehouses.show', ['id' => $warehouse->venkon_id]) }}" class="nav-link text-active-primary ms-0 me-10 py-5">{{ $warehouse->title }}</a>
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
    </div>
    <!--begin::Body-->
</div>
@endsection