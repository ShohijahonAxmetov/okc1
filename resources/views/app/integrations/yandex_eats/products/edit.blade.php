@extends('layouts.app')

@section('title', 'ПРОДУКТЫ')

@section('links')

<style>
    .alert {
        background-color: #f1416c;
        position: fixed;
        top: 24px;
        right: 24px;
        color: #fff;
        z-index: 100;
        font-weight: 500;
        padding: 0;
        display: flex;
        align-items: center;
        height: 52px;
        padding-left: 16px;
        padding-right: 16px;
    }

    .alert .icon:hover {
        cursor: pointer;
    }

    .alert .icon {
        padding-left: 12px;
    }

    .alert .text {
        padding-right: 12px;
        border-right: 1px solid rgba(255, 255, 255, .5);
    }
</style>

@endsection

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'Главная',
'route' => 'dashboard'
],
[
'title' => 'Продукты',
'route' => 'products.index'
],
[
'title' => 'Изменить'
]
]
])

@endsection

@section('content')


<form action="{{ route('integrations.yandex_eats.products.update', ['product' => $product]) }}" method="post">
    @csrf
    @method('put')
    <div class="row">
        <div class="d-flex w-100 justify-content-end">
            <button type="submit" id="success_button" class="btn btn-success">Сохранить</button>
            <a href="{{ route('integrations.yandex_eats.products') }}" class="btn btn-danger ms-5">Отмена</a>
        </div>

        <div class="col-12 mt-5">

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="kt_tab_pane_4" role="tabpanel">

                    <div class="card mb-5 mb-xl-8">
                        <!--begin::Body-->
                        <div class="card-body py-3">

                            <div class="card card-flush h-lg-100">
                                <!--begin::Header-->
                                <div class="card-header pt-5">
                                    <!--begin::Title-->
                                    <h3 class="card-title align-items-start w-100 mb-10">
                                        <span class="card-label fw-bolder text-dark">Основные данные</span>
                                    </h3>
                                    <div class="w-100">

                                        <div class="mb-6 w-100">
                                            <!--begin::Label-->
                                            <label class="form-label">Наименование продукта</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" class="form-control mb-2" placeholder="Наименование продукта" value="{{ $product->product->title['ru'] }}">
                                            <!--end::Input-->
                                            <!--begin::Description-->
                                            <div class="text-muted fs-7">Название продукта обязательно и рекомендуется, чтобы оно было уникальным..</div>
                                            <!--end::Description-->
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>

                                        <div class="mb-6 w-100">
                                            <!--begin::Label-->
                                            <label class="form-label">Цена</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" class="form-control mb-2" placeholder="Цена" disabled value="{{ $product->price }}">
                                            <!--end::Input-->
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>

                                        <div class="mb-6 w-100">
                                            <!--begin::Label-->
                                            <label class="form-label">Скидочная цена</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" class="form-control mb-2" name="old_price" placeholder="Скидочная цена" value="{{ $product->old_price }}">
                                            <!--end::Input-->
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>

                                    </div>
                                </div>
                                <!--end::Header-->
                                <!--begin::Body-->
                                <div class="card-body pt-0 px-0">

                                </div>
                                <!--end::Body-->
                            </div>

                        </div>
                        <!--begin::Body-->
                    </div>

                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section('scripts')

<script src="/assets/js/custom/utilities/modals/new-target.js" type="text/javascript"></script>

@endsection
