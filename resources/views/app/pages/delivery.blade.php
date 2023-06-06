@extends('layouts.app')

@section('title', $data->title)

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'Главная',
'route' => 'dashboard'
],
[
'title' => $data->title
]
]
])

@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
    <!--begin::Body-->
    <div class="card-body py-3">
        <form class="form" action="{{ route('pages.update', ['id' => $data->id]) }}" method="post" enctype="multipart/form-data" id="form">
            @csrf
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
                    <div class="fv-row mb-6">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2">Основной текст</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <textarea name="main_text_ru" class="form-control ckeditor form-control-solid" id="main_text_ru" rows="4" placeholder="текст...">{{$data->main_text ? $data->main_text['ru'] : ''}}</textarea>
                        <!--end::Input-->
                    </div>
                    <div class="fv-row mb-6">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2">Дополнительный текст</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <textarea name="text_ru" class="form-control ckeditor form-control-solid" rows="4" placeholder="текст...">{{$data->text ? $data->text['ru'] : ''}}</textarea>
                        <!--end::Input-->
                    </div>

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
                    <div class="fv-row mb-6">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2">Основной текст</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <textarea name="main_text_uz" class="form-control ckeditor form-control-solid" id="main_text_uz" rows="4" placeholder="текст...">{{$data->main_text ? $data->main_text['uz'] : ''}}</textarea>
                        <!--end::Input-->
                    </div>
                    <div class="fv-row mb-6">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2">Дополнительный текст</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <textarea name="text_uz" class="form-control ckeditor form-control-solid" rows="4" placeholder="текст...">{{$data->text ? $data->text['uz'] : ''}}</textarea>
                        <!--end::Input-->
                    </div>

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

            <div class="fv-row mb-9">
                <!--begin::Label-->
                <label class="fs-6 fw-bold mb-2">Фото (файлы)</label>
                <!--end::Label-->
                <!--begin::Input-->
                <div class="row w-100">
                    <div class="col-12">
                        <!--begin::Dropzone-->
                        <div class="dropzone" id="dropzone">
                            <!--begin::Message-->
                            <div class="dz-message needsclick">
                                <!--begin::Icon-->
                                <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>
                                <!--end::Icon-->

                                <!--begin::Info-->
                                <div class="ms-4">
                                    <h3 class="fs-5 fw-bolder text-gray-900 mb-1">Перетащите файлы сюда или нажмите, чтобы загрузить.</h3>
                                    <span class="fs-7 fw-bold text-gray-400">Загрузить до 6 файлов</span>
                                </div>
                                <!--end::Info-->
                            </div>
                        </div>
                        <!--end::Dropzone-->
                    </div>
                </div>
                <!--end::Input-->
            </div>

            <!--begin::Input group-->
            <div class="fv-row mb-9">
                <!--begin::Label-->
                <label class="fs-6 fw-bold mb-2">Видео (файл)</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="file" class="form-control form-control-solid" accept="video/mp4,video/x-m4v,video/*" placeholder="" name="video" />
                <!--end::Input-->
            </div>
            <!--end::Input group-->
            <div class="d-flex justify-content-end mt-8">
                <button class="btn btn-success me-2 mb-2 px-8">Сохранить</button>
            </div>
        </form>
    </div>
    <!--begin::Body-->
</div>

@endsection


@section('scripts')

<script>
    var myDropzone = new Dropzone("#dropzone", {
        url: "{{ url('/upload_from_dropzone') }}", // Set the url for your upload script location
        paramName: "file", // The name that will be used to transfer the file
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        maxFiles: 6,
        maxFilesize: 10, // MB
        addRemoveLinks: true,
        success: (file, response) => {
            let input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('value', response.file_name);
            input.setAttribute('name', 'dropzone_images[]');
            document.getElementById('form').append(input);
        },
        removedfile: function (file) {
            if(file.xhr) {
                let data = JSON.parse(file.xhr.response);
                let removing_img = document.querySelector('[value="' + data.file_name + '"]');
                removing_img.remove();
            } else {
                let data = file.name.split('/')[file.name.split('/').length - 1]
                let removing_img = document.querySelector('[value="' + data + '"]');
                removing_img.remove();
            }

            file.previewElement.remove();
        },
        init: () => {
            @if(isset($data->images[0]))
            var thisDropzone = Dropzone.forElement("#dropzone");
                @foreach($data->images as $image)
                    var input = document.createElement('input');
                    input.setAttribute('type', 'hidden');
                    input.setAttribute('value', '{{ explode("/", $image->img)[count(explode("/", $image->img)) - 1] }}');
                    input.setAttribute('data-id', '{{ $image->id }}');
                    input.setAttribute('name', 'dropzone_images[]');
                    document.getElementById('form').append(input);

                    var mockFile = {
                        name: '{{ $image->img }}',
                        size: 1024 * 512
                    };

                    thisDropzone.emit("addedfile", mockFile);
                    thisDropzone.emit("thumbnail", mockFile, '{{ $image->img }}');
                    thisDropzone.emit("complete", mockFile);
                @endforeach

            @endif

        }
    });
</script>

@endsection