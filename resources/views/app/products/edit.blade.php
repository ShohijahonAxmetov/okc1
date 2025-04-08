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


<form action="{{ route('products.update', ['id' => $product->id]) }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="d-flex w-100 justify-content-end">
            <button type="submit" id="success_button" class="btn btn-success">Сохранить</button>
            <a href="{{ route('products.index') }}{{ isset($page_number) ? '?page='.$page_number : '' }}" class="btn btn-danger ms-5">Отмена</a>
        </div>

        <div class="col-8">
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                @foreach($languages as $language)
                @if($loop->first)
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_4">Русский</a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_5">O’zbek</a>
                </li>
                @endif
                @endforeach
            </ul>

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
                                        <!--end::Title-->
                                        <div class="mb-6 w-100">
                                            <!--begin::Label-->
                                            <label class="required form-label">Наименование продукта</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="title_ru" required class="form-control mb-2" placeholder="Product name" value="{{ old('title_ru') ?? $product->title['ru'] }}">
                                            <!--end::Input-->
                                            <!--begin::Description-->
                                            <div class="text-muted fs-7">Название продукта обязательно и рекомендуется, чтобы оно было уникальным..</div>
                                            <!--end::Description-->
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>

                                        <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">Описание</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">Как использовать</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_3">Meta параметры</a>
                                            </li>
                                        </ul>

                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">

                                                <div>
                                                    <!--begin::Editor-->
                                                    <textarea name="desc_ru" class="ckeditor desc" id="editor1" cols="30" rows="10" style="height: 200px;">{!! old('desc_ru') ?? $product->desc['ru'] ?? '' !!}</textarea>
                                                    <!--end::Editor-->
                                                </div>

                                            </div>
                                            <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">


                                                <div>
                                                    <!--begin::Editor-->
                                                    <textarea name="how_to_use_ru" class="ckeditor desc" id="editor2" cols="30" rows="10" style="height: 200px;">{!! old('how_to_use_ru') ?? $product->how_to_use['ru'] ?? '' !!}</textarea>
                                                    <!--end::Editor-->
                                                </div>

                                            </div>
                                            <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel">

                                                <!--begin::Meta options-->
                                                <!--begin::Input group-->
                                                <div>
                                                    <!--begin::Label-->
                                                    <label class="form-label">Meta заголовок</label>
                                                    <!--end::Label-->
                                                    <!--begin::Editor-->
                                                    <input name="meta_title_ru" class="form-control mb-2" value="{{ old('meta_title_ru') ?? $product->meta_title['ru'] ?? '' }}" />
                                                    <!--end::Editor-->
                                                </div>
                                                <div>
                                                    <!--begin::Label-->
                                                    <label class="form-label">Meta описание</label>
                                                    <!--end::Label-->
                                                    <!--begin::Editor-->
                                                    <textarea name="meta_desc_ru" rows="6" class="form-control mb-2">{{ old('meta_desc_ru') ?? $product->meta_desc['ru'] ?? '' }}</textarea>
                                                    <!--end::Editor-->
                                                </div>
                                                <div>
                                                    <!--begin::Label-->
                                                    <label class="form-label">Meta Tag Keywords</label>
                                                    <!--end::Label-->
                                                    <!--begin::Editor-->
                                                    <input id="kt_ecommerce_add_category_meta_keywords" name="meta_keywords_ru" class="form-control mb-2" value="{{ old('meta_keywords_ru') ?? $product->meta_keywords['ru'] ?? '' }}" />
                                                    <!--end::Editor-->
                                                    <!--begin::Description-->
                                                    <div class="text-muted fs-7">Установите список ключевых слов, с которыми связана категория. Разделяйте ключевые слова, добавляя запятую
                                                        <code>,</code>между каждым ключевым словом.
                                                    </div>
                                                    <!--end::Description-->
                                                </div>
                                                <!--end::Input group-->
                                                <!--end::Meta options-->

                                            </div>
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
                <div class="tab-pane fade" id="kt_tab_pane_5" role="tabpanel">

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
                                        <!--end::Title-->
                                        <div class="mb-6 w-100">
                                            <!--begin::Label-->
                                            <label class="form-label">Наименование продукта</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="title_uz" class="form-control mb-2" placeholder="Product name" value="{{ old('title_uz') ?? $product->title['uz'] }}">
                                            <!--end::Input-->
                                            <!--begin::Description-->
                                            <div class="text-muted fs-7">Название продукта обязательно и рекомендуется, чтобы оно было уникальным..</div>
                                            <!--end::Description-->
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>

                                        <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_11">Описание</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_22">Как использовать</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_31">Meta параметры</a>
                                            </li>
                                        </ul>

                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="kt_tab_pane_11" role="tabpanel">

                                                <div>
                                                    <!--begin::Editor-->
                                                    <textarea name="desc_uz" class="ckeditor desc" id="editor3" style="height: 200px;" cols="30" rows="10">{!! old('desc_uz') ?? $product->desc['uz'] ?? '' !!}</textarea>
                                                    <!--end::Editor-->
                                                </div>

                                            </div>
                                            <div class="tab-pane fade" id="kt_tab_pane_22" role="tabpanel">


                                                <div>
                                                    <!--begin::Editor-->
                                                    <textarea name="how_to_use_uz" class="ckeditor desc" id="editor4" style="height: 200px;" cols="30" rows="10">{!! old('how_to_use_uz') ?? $product->how_to_use['uz'] ?? '' !!}</textarea>
                                                    <!--end::Editor-->
                                                </div>

                                            </div>
                                            <div class="tab-pane fade" id="kt_tab_pane_31" role="tabpanel">

                                                <!--begin::Meta options-->
                                                <!--begin::Input group-->
                                                <div>
                                                    <div>
                                                    <!--begin::Label-->
                                                    <label class="form-label">Meta заголовок</label>
                                                    <!--end::Label-->
                                                    <!--begin::Editor-->
                                                    <input name="meta_title_uz" class="form-control mb-2" value="{{ old('meta_title_uz') ?? $product->meta_title['uz'] ?? '' }}" />
                                                    <!--end::Editor-->
                                                </div>
                                                <div>
                                                    <!--begin::Label-->
                                                    <label class="form-label">Meta описание</label>
                                                    <!--end::Label-->
                                                    <!--begin::Editor-->
                                                    <textarea name="meta_desc_uz" rows="6" class="form-control mb-2">{{ old('meta_desc_uz') ?? $product->meta_desc['uz'] ?? '' }}</textarea>
                                                    <!--end::Editor-->
                                                </div>
                                                    <!--begin::Label-->
                                                    <label class="form-label">Meta Tag Keywords</label>
                                                    <!--end::Label-->
                                                    <!--begin::Editor-->
                                                    <input id="kt_ecommerce_add_category_meta_keywords" name="meta_keywords_uz" class="form-control mb-2" value="{{ old('meta_keywords_uz') ?? $product->meta_keywords['uz'] ?? '' }}" />
                                                    <!--end::Editor-->
                                                    <!--begin::Description-->
                                                    <div class="text-muted fs-7">Установите список ключевых слов, с которыми связана категория. Разделяйте ключевые слова, добавляя запятую
                                                        <code>,</code>между каждым ключевым словом.
                                                    </div>
                                                    <!--end::Description-->
                                                </div>
                                                <!--end::Input group-->
                                                <!--end::Meta options-->

                                            </div>
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

            <h3 class="mb-6">Варианты продукта ({{ count($product->productVariations) }})</h3>
            @if(isset($product->productVariations[0]))
            @foreach($product->productVariations as $variation)
            <div class="card mb-5 mb-xl-8">
                <!--begin::Body-->
                <div class="card-body py-3">

                    <div class="card card-flush h-lg-100">
                        <!--begin::Header-->
                        <div class="card-header p-3 pt-5">
                            <div class="row w-100">
                                <div class="col-6">
                                    <div class="w-100 d-flex">
                                        <p class="fw-bold me-3 mb-0">Slug:</p><span>{{ $variation->slug }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row w-100">
                                <div class="col-6">
                                    <div class="w-100 d-flex">
                                        <p class="fw-bold me-3 mb-0">ID:</p><span>{{ $variation->integration_id }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row w-100">
                                <div class="col-6">
                                    <div class="w-100 d-flex">
                                        <p class="fw-bold me-3 mb-0">Остаток:</p><span>{{ $variation->remainder }}</span>
                                    </div>
                                </div>
                            </div>
                            <hr class="w-100">
                            <div class="row w-100">
                                <div class="col-6">
                                    <div class="mb-6 w-100">
                                        <!--begin::Label-->
                                        <label class="form-label">Код продукта</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" data-id="data_{{ $variation->id }}" name="product_code" class="form-control mb-2" placeholder="Код продукта" value="{{ $variation->product_code }}">
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-6 w-100">
                                        <!--begin::Label-->
                                        <label class="required form-label">Цена</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" required data-id="data_{{ $variation->id }}" name="price" class="form-control mb-2" placeholder="Цена" value="{{ $variation->price }}">
                                        <!--end::Input-->
                                    </div>
                                </div>
                            </div>
                            <div class="row w-100">
                                <div class="col-6">
                                    <div class="mb-6">
                                        <label for="exampleFormControlInput1" class="form-label">Статус</label>
                                        <select class="form-select" aria-label="" data-id="data_{{ $variation->id }}" name="variation_status" data-control="select2" data-hide-search="true">
                                            <option value="1" {{ $variation->is_active == 1 ? 'selected' : '' }}>Активный</option>
                                            <option value="0" {{ $variation->is_active == 0 ? 'selected' : '' }}>Неактивный</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-6">
                                        <label for="exampleFormControlInput1" class="form-label">Цвет</label>
                                        <select class="form-select" aria-label="" data-id="data_{{ $variation->id }}" name="color" data-control="select2" data-hide-search="true">
                                            <option value="">Выберите</option>
                                            @foreach($colors as $color)
                                            <option value="{{ $color->integration_id }}" {{ $variation->color_id == $color->integration_id ? 'selected' : '' }}>{{ $color->title['ru'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row w-100">
                                <div class="col-12">
                                    <div class="mb-6 w-100">
                                        <!--begin::Label-->
                                        <label class="form-label">Slug</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" data-id="data_{{ $variation->id }}" name="slug" class="form-control mb-2" placeholder="slug" value="{{ $variation->slug }}">
                                        <!--end::Input-->
                                    </div>
                                </div>
                            </div>
                            <div class="row w-100">
                                <div class="col-12">
                                    <!--begin::Dropzone-->
                                    <div class="dropzone" id="dropzone_{{ $variation->id }}">
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
            <input type="hidden" data-id="data_{{ $variation->id }}" name="variation_real_id" class="form-control mb-2" placeholder="Код продукта" value="{{ $variation->id }}">
            @endforeach
            @endif
        </div>
        <div class="col-4">

            <div class="card mb-5 mb-xl-8" style="margin-top: 48px;">
                <!--begin::Body-->
                <div class="card-body py-3">

                    <div class="card card-flush h-lg-100">
                        <!--begin::Header-->
                        <div class="card-header pt-5">
                            <!--begin::Title-->
                            <div class="w-100">
                                <div class="mb-6">
                                    <label for="exampleFormControlInput1" class="form-label required">Статус</label>
                                    <select class="form-select" aria-label="" name="status" data-control="select2" data-hide-search="true">
                                        <option value="1" {{ $product->is_active == 1 ? 'selected' : '' }}>Активный</option>
                                        <option value="0" {{ $product->is_active == 0 ? 'selected' : '' }}>Неактивный</option>
                                    </select>
                                    <div class="text-muted fs-7 mt-2">Установить статус продукта.</div>
                                </div>

                                <div class="mb-6 w-100">
                                    <!--begin::Label-->
                                    <label class="form-label">Остаток</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" disabled class="form-control mb-2" value="{{ $remainder }}">
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

            <div class="card mb-5 mb-xl-8">
                <!--begin::Body-->
                <div class="card-body py-3">

                    <div class="card card-flush h-lg-100">
                        <!--begin::Header-->
                        <div class="card-header pt-5">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start w-100 mb-10">
                                <span class="card-label fw-bolder text-dark">Параметры</span>
                            </h3>
                            <div class="w-100">
                                <div class="mb-6">
                                    <label for="is_popular" class="form-label">Популярный,</label>
                                    <select class="form-select" aria-label="" name="is_popular" id="is_popular" data-control="select2" data-hide-search="true">
                                        <option value="1" {{ $product->is_popular == 1 ? 'selected' : '' }}>Да</option>
                                        <option value="0" {{ $product->is_popular == 0 ? 'selected' : '' }}>Нет</option>
                                    </select>
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="mb-6">
                                    <label for="brand" class="form-label required">Бренд</label>
                                    <select class="form-select mb-2" name="brand" id="brand" data-control="select2" data-hide-search="true" data-placeholder="Выберите вариант" id="kt_ecommerce_add_product_status_select">
                                        @foreach($brands as $brand)
                                        <option value="{{ $brand->integration_id }}" {{ $product->brand_id == $brand->integration_id ? 'selected' : '' }}>{{ $brand->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="w-100">
                                <div class="mb-6">
                                    <label for="categories" class="required form-label">Категории</label>
                                    <select class="form-select categories" aria-label="" name="categories" id="categories" multiple>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->integration_id }}" {{ in_array($category->integration_id, $product->categories->pluck('integration_id')->toArray()) ? 'selected' : '' }}>{{ $category->title['ru'] ?? null }}</option>
                                        @endforeach
                                    </select>
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
</form>

@endsection

@section('scripts')

<script>

    @if(isset($product->productVariations[0]))
    @foreach($product->productVariations as $variation)

    var myDropzone = new Dropzone("#dropzone_" + {{ $variation->id }}, {
        url: "{{ url('/upload_from_dropzone') }}", // Set the url for your upload script location
        paramName: "file", // The name that will be used to transfer the file
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        maxFiles: 6,
        maxFilesize: 10, // MB
        addRemoveLinks: true,
        sending: (file, response, formData) => {
            formData.append("variation_id", '{{ $variation->id }}')
        },
        success: (file, response) => {
            let input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('value', response.file_name);
            input.setAttribute('data-id', response.variation_id);
            input.setAttribute('name', 'dropzone_images');
            document.body.append(input);
            // console.log(response);
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
            @if(isset($variation->productVariationImages[0]))
            var thisDropzone = Dropzone.forElement("#dropzone_" + {{ $variation->id }});
                let images_count = '{{ count($variation->productVariationImages) }}';
                console.log('yes images');
                console.log(images_count);
                @foreach($variation->productVariationImages as $image)
                    var input = document.createElement('input');
                    input.setAttribute('type', 'hidden');
                    input.setAttribute('value', '{{ explode("/", $image->img)[count(explode("/", $image->img)) - 1] }}');
                    input.setAttribute('data-id', '{{ $variation->id }}');
                    input.setAttribute('name', 'dropzone_images');
                    document.body.append(input);

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

    @endforeach
    @endif

    $('.categories').bsMultiSelect();

    // pure JS

    let success_button = document.getElementById('success_button');
    success_button.addEventListener('click', e => {
        e.preventDefault();

        let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let title_ru = document.querySelector('[name="title_ru"]').value;

        if (title_ru == null || title_ru == '') {
            // document.querySelector('[name="title_ru"]').style.border = '1px solid red';
            $.notify("Product title is required!");
            return false;
        } else {
            // document.querySelector('[name="title_ru"]').style.border = '1px solid #e4e6ef';
        }

        let title_uz = document.querySelector('[name="title_uz"]').value;
        // let desc_ru = document.querySelector('[name="desc_ru"]').textContent;
        // let desc_uz = document.querySelector('[name="desc_uz"]').textContent;
        // let how_to_use_ru = document.querySelector('[name="how_to_use_ru"]').value;
        // let how_to_use_uz = document.querySelector('[name="how_to_use_uz"]').textContent;
        let desc_ru = CKEDITOR.instances.editor1.getData();
        let desc_uz = CKEDITOR.instances.editor3.getData();
        let how_to_use_ru = CKEDITOR.instances.editor2.getData();
        let how_to_use_uz = CKEDITOR.instances.editor4.getData();
        let meta_keywords_ru = document.querySelector('[name="meta_keywords_ru"]').value;
        let meta_keywords_uz = document.querySelector('[name="meta_keywords_uz"]').value;
        let is_popular = document.querySelector('[name="is_popular"]').value;
        let status = document.querySelector('[name="status"]').value;
        let brand = document.querySelector('[name="brand"]').value;

        let meta_title_ru = document.querySelector('[name="meta_title_ru"]').value;
        let meta_title_uz = document.querySelector('[name="meta_title_uz"]').value;
        let meta_desc_ru = document.querySelector('[name="meta_desc_ru"]').value;
        let meta_desc_uz = document.querySelector('[name="meta_desc_uz"]').value;

        var checked_dropzone = document.querySelectorAll('[name="dropzone_images"]');
        var dropzone_images = [...checked_dropzone].map(option => {
            var object = new Object();
            object.value = option.value,
                object.id = option.getAttribute('data-id')

            return object;
        });

        let checked = document.querySelectorAll('[name="categories"] :checked');
        let categories = [...checked].map(option => option.value);

        var variations_data = [];
        @if(isset($product -> productVariations[0]))
        @foreach($product -> productVariations as $variation)

        var variations = document.querySelectorAll('[data-id="data_' + '{{ $variation->id }}' + '"]');
        var variations_data_item = [...variations].map(option => {
            var object = new Object();
            object.value = option.value;
            return object;
        });

        variations_data.push(variations_data_item);

        @endforeach
        @endif


        var stop_script = false;
        variations_data.forEach((item) => {
            if (item[1].value == null || item[1].value == '') {
                stop_script = true;
                $.notify("Укажите цену!", 'error');
            }
        });
        if (stop_script) {
            return false;
        }

        let params = new FormData();
        params.set('title_ru', title_ru);
        params.set('title_uz', title_uz);
        params.set('desc_ru', desc_ru);
        params.set('desc_uz', desc_uz);
        params.set('how_to_use_ru', how_to_use_ru);
        params.set('how_to_use_uz', how_to_use_uz);
        params.set('meta_keywords_ru', meta_keywords_ru);
        params.set('meta_keywords_uz', meta_keywords_uz);
        params.set('is_popular', is_popular);
        params.set('status', status);
        params.set('brand', brand);
        params.set('categories', categories);
        params.set('dropzone_images', JSON.stringify(dropzone_images));
        params.set('variations_data', JSON.stringify(variations_data));

        params.set('meta_title_ru', meta_title_ru);
        params.set('meta_title_uz', meta_title_uz);
        params.set('meta_desc_ru', meta_desc_ru);
        params.set('meta_desc_uz', meta_desc_uz);

        console.log(how_to_use_ru);

        fetch('/dashboard/products/' + {{ $product -> id }} + '/update?_token=' + csrf, {
            method: 'POST',
            body: params
        }).then((response) => {
            response.json().then(text => {


                if (text.success) {
                    @if($page_number)
                    let redirect_url = '/dashboard/products?page=' + {{ $page_number }} + '&success=true';
                    @else
                    let redirect_url = '/dashboard/products?success=true';
                    @endif
                    window.location.href = redirect_url;

                } else {
                    $.notify("Что-то пошло не так !", 'error');
                }

            }).catch((error) => {

                $.notify("Что-то пошло не так !", 'error');

            });
        });
    });

    let alert = document.getElementsByClassName('alert-div')[0];
</script>
<script src="/assets/js/custom/utilities/modals/new-target.js" type="text/javascript"></script>

@endsection
