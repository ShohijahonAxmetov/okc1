@extends('layouts.app')

@section('title', 'PRODUCTS')

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
            'title' => 'home',
            'route' => 'dashboard'
        ],
        [
            'title' => 'products',
            'route' => 'products.index'
        ],
        [
            'title' => 'Edit'    
        ]
    ]
])

@endsection

@section('content')


<form action="{{ route('products.update', ['id' => $product->id]) }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="d-flex w-100 justify-content-end">
            <button type="submit" id="success_button" class="btn btn-success">Save</button>
            <a href="{{ route('products.index') }}{{ isset($page_number) ? '?page='.$page_number : '' }}" class="btn btn-danger ms-5">Cancel</a>
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
                                        <span class="card-label fw-bolder text-dark">Main data</span>
                                    </h3>
                                    <div class="w-100">
                                        <!--end::Title-->
                                        <div class="mb-6 w-100">
                                            <!--begin::Label-->
                                            <label class="required form-label">Product Name</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="title_ru" required class="form-control mb-2" placeholder="Product name" value="{{ old('title_ru') ?? $product->title['ru'] }}">
                                            <!--end::Input-->
                                            <!--begin::Description-->
                                            <div class="text-muted fs-7">A product name is required and recommended to be unique.</div>
                                            <!--end::Description-->
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>

                                        <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">Description</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">How to use</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_3">Meta parametrs</a>
                                            </li>
                                        </ul>

                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">

                                                <div>
                                                    <!--begin::Editor-->
                                                    <div id="editor" class="desc" name="desc_ru" style="height: 200px;">{!! old('desc_ru') ?? $product->desc['ru'] ?? '' !!}</div>
                                                    <!--end::Editor-->
                                                </div>

                                            </div>
                                            <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">


                                                <div>
                                                    <!--begin::Editor-->
                                                    <div id="editor2" class="desc" name="how_to_use_ru" style="height: 200px;">{!! old('how_to_use_ru') ?? $product->how_to_use['ru'] ?? '' !!}</div>
                                                    <!--end::Editor-->
                                                </div>

                                            </div>
                                            <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel">

                                                <!--begin::Meta options-->
                                                <!--begin::Input group-->
                                                <div>
                                                    <!--begin::Label-->
                                                    <label class="form-label">Meta Tag Keywords</label>
                                                    <!--end::Label-->
                                                    <!--begin::Editor-->
                                                    <input id="kt_ecommerce_add_category_meta_keywords" name="meta_keywords_ru" class="form-control mb-2" value="{{ old('meta_keywords_ru') ?? $product->meta_keywords['ru'] ?? '' }}" />
                                                    <!--end::Editor-->
                                                    <!--begin::Description-->
                                                    <div class="text-muted fs-7">Set a list of keywords that the category is related to. Separate the keywords by adding a comma
                                                        <code>,</code>between each keyword.
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
                                        <span class="card-label fw-bolder text-dark">Main data</span>
                                    </h3>
                                    <div class="w-100">
                                        <!--end::Title-->
                                        <div class="mb-6 w-100">
                                            <!--begin::Label-->
                                            <label class="form-label">Product Name</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="title_uz" class="form-control mb-2" placeholder="Product name" value="{{ old('title_uz') ?? $product->title['uz'] }}">
                                            <!--end::Input-->
                                            <!--begin::Description-->
                                            <div class="text-muted fs-7">A product name is required and recommended to be unique.</div>
                                            <!--end::Description-->
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>

                                        <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_11">Description</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_22">How to use</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_31">Meta parametrs</a>
                                            </li>
                                        </ul>

                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="kt_tab_pane_11" role="tabpanel">

                                                <div>
                                                    <!--begin::Editor-->
                                                    <div id="editor1" class="desc" name="desc_uz" style="height: 200px;">{!! old('desc_uz') ?? $product->desc['uz'] ?? '' !!}</div>
                                                    <!--end::Editor-->
                                                </div>

                                            </div>
                                            <div class="tab-pane fade" id="kt_tab_pane_22" role="tabpanel">


                                                <div>
                                                    <!--begin::Editor-->
                                                    <div id="editor21" class="desc" name="how_to_use_uz" style="height: 200px;">{!! old('how_to_use_uz') ?? $product->how_to_use['uz'] ?? '' !!}</div>
                                                    <!--end::Editor-->
                                                </div>

                                            </div>
                                            <div class="tab-pane fade" id="kt_tab_pane_31" role="tabpanel">

                                                <!--begin::Meta options-->
                                                <!--begin::Input group-->
                                                <div>
                                                    <!--begin::Label-->
                                                    <label class="form-label">Meta Tag Keywords</label>
                                                    <!--end::Label-->
                                                    <!--begin::Editor-->
                                                    <input id="kt_ecommerce_add_category_meta_keywords" name="meta_keywords_uz" class="form-control mb-2" value="{{ old('meta_keywords_uz') ?? $product->meta_keywords['uz'] ?? '' }}" />
                                                    <!--end::Editor-->
                                                    <!--begin::Description-->
                                                    <div class="text-muted fs-7">Set a list of keywords that the category is related to. Separate the keywords by adding a comma
                                                        <code>,</code>between each keyword.
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

            <h3 class="mb-6">Product Variations ({{ count($product->productVariations) }})</h3>
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
                                <div class="mb-6 w-100">
                                    <!--begin::Label-->
                                    <label class="form-label">Product code</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" data-id="data_{{ $variation->id }}" name="product_code" class="form-control mb-2" placeholder="Product code" value="{{ $variation->product_code }}">
                                    <!--end::Input-->
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-6 w-100">
                                    <!--begin::Label-->
                                    <label class="required form-label">Price</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" required data-id="data_{{ $variation->id }}" name="price" class="form-control mb-2" placeholder="Price" value="{{ $variation->price }}">
                                    <!--end::Input-->
                                </div>
                            </div>
                        </div>
                        <div class="row w-100">
                            <div class="col-6">
                                <div class="mb-6">
                                    <label for="exampleFormControlInput1" class="form-label">Status</label>
                                    <select class="form-select" aria-label="" data-id="data_{{ $variation->id }}" name="variation_status" data-control="select2" data-hide-search="true">
                                        <option value="1" {{ $variation->is_active == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $variation->is_active == 0 ? 'selected' : '' }}>Not active</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-6">
                                    <label for="exampleFormControlInput1" class="form-label">Color</label>
                                    <select class="form-select" aria-label="" data-id="data_{{ $variation->id }}" name="color" data-control="select2" data-hide-search="true">
                                        <option value="">Select</option>
                                        @foreach($colors as $color)
                                        <option value="{{ $color->venkon_id }}" {{ $variation->color_id == $color->venkon_id ? 'selected' : '' }}>{{ $color->title['ru'] }}</option>
                                        @endforeach
                                    </select>
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
                                            <h3 class="fs-5 fw-bolder text-gray-900 mb-1">Drop files here or click to upload.</h3>
                                            <span class="fs-7 fw-bold text-gray-400">Upload up to 6 files</span>
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
        <input type="hidden" data-id="data_{{ $variation->id }}" name="variation_real_id" class="form-control mb-2" placeholder="Product code" value="{{ $variation->id }}" }}">
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
                                    <label for="exampleFormControlInput1" class="form-label required">Status</label>
                                    <select class="form-select" aria-label="" name="status" data-control="select2" data-hide-search="true">
                                        <option value="1" {{ $product->is_active == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $product->is_active == 0 ? 'selected' : '' }}>Not active</option>
                                    </select>
                                    <div class="text-muted fs-7 mt-2">Set the product status.</div>
                                </div>

                                <div class="mb-6 w-100">
                                    <!--begin::Label-->
                                    <label class="form-label">Remainder</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" disabled class="form-control mb-2" value="152">
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
                                <span class="card-label fw-bolder text-dark">Parametrs</span>
                            </h3>
                            <div class="w-100">
                                <div class="mb-6">
                                    <label for="is_popular" class="form-label">Popular</label>
                                    <select class="form-select" aria-label="" name="is_popular" id="is_popular" data-control="select2" data-hide-search="true">
                                        <option value="1" {{ $product->is_popular == 1 ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ $product->is_popular == 1 ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="mb-6">
                                    <label for="brand" class="form-label required">Brand</label>
                                    <select class="form-select mb-2" name="brand" id="brand" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="kt_ecommerce_add_product_status_select">
                                        @foreach($brands as $brand)
                                        <option value="{{ $brand->venkon_id }}" {{ $product->brand_id == $brand->venkon_id ? 'selected' : '' }}>{{ $brand->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="w-100">
                                <div class="mb-6">
                                    <label for="categories" class="required form-label">Categories</label>
                                    <select class="form-select categories" aria-label="" name="categories" id="categories" multiple>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->venkon_id }}" {{ in_array($category->venkon_id, $product->categories->pluck('venkon_id')->toArray()) ? 'selected' : '' }}>{{ $category->title['ru'] ?? null }}</option>
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
    var quill = new Quill('#editor', {
        theme: 'snow',
        // modules: {
        //     toolbar: [

        //     ]
        // }
    });
    var quill = new Quill('#editor2', {
        theme: 'snow',
        // modules: {
        //     toolbar: [

        //     ]
        // }
    });

    var quill = new Quill('#editor1', {
        theme: 'snow',
        // modules: {
        //     toolbar: [

        //     ]
        // }
    });
    var quill = new Quill('#editor21', {
        theme: 'snow',
        // modules: {
        //     toolbar: [

        //     ]
        // }
    });

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
        removedfile: function(file) {
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

        if(title_ru == null || title_ru == '') {
            // document.querySelector('[name="title_ru"]').style.border = '1px solid red';
            $.notify("Product title is required!");
            return false;
        } else {
            // document.querySelector('[name="title_ru"]').style.border = '1px solid #e4e6ef';
        }

        let title_uz = document.querySelector('[name="title_uz"]').value;
        let desc_ru = document.querySelector('[name="desc_ru"]').textContent;
        let desc_uz = document.querySelector('[name="desc_uz"]').textContent;
        let how_to_use_ru = document.querySelector('[name="how_to_use_ru"]').textContent;
        let how_to_use_uz = document.querySelector('[name="how_to_use_uz"]').textContent;
        let meta_keywords_ru = document.querySelector('[name="meta_keywords_ru"]').value;
        let meta_keywords_uz = document.querySelector('[name="meta_keywords_uz"]').value;
        let is_popular = document.querySelector('[name="is_popular"]').value;
        let status = document.querySelector('[name="status"]').value;
        let brand = document.querySelector('[name="brand"]').value;

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
        @if(isset($product->productVariations[0]))
        @foreach($product->productVariations as $variation)

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
            if(item[1].value == null || item[1].value == '') {
                stop_script = true;
                $.notify("Price input is required!", 'error');
            }
        });
        if(stop_script) {
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

        fetch('/dashboard/products/' + {{ $product->id }} + '/update?_token=' + csrf, {
            method: 'POST',
            body: params
        }).then((response) => {
            response.json().then(text => {


                if(text.success) {
                    @if($page_number)
                    let redirect_url = '/dashboard/products?page=' + {{ $page_number }} + '&success=true';
                    @else
                    let redirect_url = '/dashboard/products?success=true';
                    @endif
                    window.location.href = redirect_url;

                } else {
                    $.notify("Something went wrong !", 'error');
                }

            }).catch((error) => {

                $.notify("Something went wrong !", 'error');

            });
        });
    });

    let alert = document.getElementsByClassName('alert-div')[0];
</script>
<script src="/assets/js/custom/utilities/modals/new-target.js" type="text/javascript"></script>

@endsection