@extends('layouts.app')

@section('title', 'Информация')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'Главная',
'route' => 'dashboard'
],
[
'title' => 'Информация'
]
]
])

@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
    <!--begin::Body-->
    <div class="card-body py-3">
        <form class="form" action="{{ route('infos.update', ['info' => $data]) }}" method="post" enctype="multipart/form-data" id="form">
            @csrf
            @method('put')
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                @foreach($languages as $language)
                @if($loop->first)
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_44a">Русский</a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_55a">O’zbek</a>
                </li>
                @endif
                @endforeach
            </ul>
            
            <div class="tab-content mb-9">
                <div class="tab-pane fade show active" id="kt_tab_pane_44a" role="tabpanel">
                    <!-- FOR SEO -->
                    <div class="fv-row mb-6">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2">Meta Заголовок</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control form-control-solid" placeholder="" name="meta_title_ru" value="{{$data->meta_title ? $data->meta_title['ru'] : ''}}" />
                        <!--end::Input-->
                    </div>
                    <div class="fv-row mb-6">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2">Meta Описание</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control form-control-solid" placeholder="" name="meta_desc_ru" value="{{$data->meta_desc ? $data->meta_desc['ru'] : ''}}" />
                        <!--end::Input-->
                    </div>
                    <div class="fv-row mb-6">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2">Ключевые слова</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control form-control-solid" placeholder="" name="meta_keywords_ru" value="{{$data->meta_keywords ? $data->meta_keywords['ru'] : ''}}" />
                        <!--end::Input-->
                    </div>
                </div>

                <div class="tab-pane fade" id="kt_tab_pane_55a" role="tabpanel">
                    <!-- FOR SEO -->
                    <div class="fv-row mb-6">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2">Meta Заголовок</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control form-control-solid" placeholder="" name="meta_title_uz" value="{{$data->meta_title ? $data->meta_title['uz'] : ''}}" />
                        <!--end::Input-->
                    </div>
                    <div class="fv-row mb-6">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2">Meta Описание</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control form-control-solid" placeholder="" name="meta_desc_uz" value="{{$data->meta_desc ? $data->meta_desc['uz'] : ''}}" />
                        <!--end::Input-->
                    </div>
                    <div class="fv-row mb-6">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2">Ключевые слова</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control form-control-solid" placeholder="" name="meta_keywords_uz" value="{{$data->meta_keywords ? $data->meta_keywords['uz'] : ''}}" />
                        <!--end::Input-->
                    </div>
                </div>
            </div>

            <div class="fv-row mb-6">
                <!--begin::Label-->
                <label class="fs-6 fw-bold mb-2">Заголовок</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="text" class="form-control form-control-solid" placeholder="" name="title" value="{{$data->title ? $data->title : ''}}" />
                <!--end::Input-->
            </div>
            <div class="fv-row mb-6">
                <!--begin::Label-->
                <label class="fs-6 fw-bold mb-2">Facebook</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="text" class="form-control form-control-solid" placeholder="" name="facebook" value="{{$data->facebook ? $data->facebook : ''}}" />
                <!--end::Input-->
            </div>
            <div class="fv-row mb-6">
                <!--begin::Label-->
                <label class="fs-6 fw-bold mb-2">Instagram</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="text" class="form-control form-control-solid" placeholder="" name="instagram" value="{{$data->instagram ? $data->instagram : ''}}" />
                <!--end::Input-->
            </div>
            <div class="fv-row mb-6">
                <!--begin::Label-->
                <label class="fs-6 fw-bold mb-2">Telegram</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="text" class="form-control form-control-solid" placeholder="" name="telegram" value="{{$data->telegram ? $data->telegram : ''}}" />
                <!--end::Input-->
            </div>
            <div class="fv-row mb-6">
                <!--begin::Label-->
                <label class="fs-6 fw-bold mb-2">Youtube</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="text" class="form-control form-control-solid" placeholder="" name="youtube" value="{{$data->youtube ? $data->youtube : ''}}" />
                <!--end::Input-->
            </div>
            <div class="fv-row mb-6">
                <!--begin::Label-->
                <label class="fs-6 fw-bold mb-2">Номер телефона</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="text" class="form-control form-control-solid" placeholder="" name="phone_number" value="{{$data->phone_number ? $data->phone_number : ''}}" />
                <!--end::Input-->
            </div>
            <div class="fv-row mb-6">
                <!--begin::Label-->
                <label class="fs-6 fw-bold mb-2">Дополнительный номер телефона</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="text" class="form-control form-control-solid" placeholder="" name="dop_phone_number" value="{{$data->dop_phone_number ? $data->dop_phone_number : ''}}" />
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