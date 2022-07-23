@extends('layouts.app')

@section('title', 'CATEGORIES')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'home',
'route' => 'dashboard'
],
[
'title' => 'categories'
]
]
])

@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">Categories</span>
            <span class="text-muted mt-1 fw-bold fs-7">Showing {{ $show_count }} of {{ $all_categories_count }}</span>
        </h3>
        <!-- <div class="card-toolbar">
            <a href="#" class="btn btn-sm btn-light-primary">
                <span class="svg-icon svg-icon-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor"></rect>
                        <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor"></rect>
                    </svg>
                </span>
                New Item
            </a>
        </div> -->
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body py-3">
        <!--begin::Table container-->
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table align-middle gs-0 gy-4">
                <!--begin::Table head-->
                <thead>
                    <tr class="fw-bolder text-muted bg-light">
                        <th class="ps-4 min-w-75px rounded-start">ID</th>
                        <th class="min-w-325px">Title</th>
                        <th class="min-w-125px">Parents</th>
                        <th class="min-w-150px">Status</th>
                        <th class="min-w-200px text-end rounded-end">Actions</th>
                    </tr>
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td class="ps-3 fw-bold">
                            <p>#{{ $category->id }}</p>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-5">
                                    <img src="{{ isset($category->img) ? asset($category->img) : '/assets/media/default.png' }}" class="" alt="">
                                </div>
                                <div class="d-flex justify-content-start flex-column">
                                    <a href="#" class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{ $category->title['ru'] }}</a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="text-dark text-hover-primary d-block mb-1 fs-6">{{ isset($category->parent) ? $category->parent['title']['ru'] : '--' }}</a>
                        </td>
                        <td>
                            @if($category->is_active)
                            <span class="badge badge-light-success fs-7 fw-bold">Active</span>
                            @else
                            <span class="badge badge-light-danger fs-7 fw-bold">Not active</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <!-- <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                <span class="svg-icon svg-icon-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="currentColor"></path>
                                        <path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                            </a> -->
                            <a class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#edit_category{{ $category->id }}">
                                <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                <span class="svg-icon svg-icon-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                        <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </a>
                            <!-- <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
                                <span class="svg-icon svg-icon-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
                                        <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
                                        <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                            </a> -->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <!--end::Table body-->
            </table>
            <!--end::Table-->
        </div>
        <!--end::Table container-->
    </div>
    <!--begin::Body-->
</div>


@foreach($categories as $category)
<!--begin::Modal - New Product-->
<div class="modal fade" id="edit_category{{ $category->id }}" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <form class="form" action="{{ route('categories.update', ['id' => $category->id]) }}" id="kt_modal_add_event_form" method="post" enctype="multipart/form-data">
                @csrf
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bolder" data-kt-calendar="title">Edit category "{{ $category->title['ru'] }}"</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body py-10 px-lg-17">

                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                        @foreach($languages as $language)
                        @if($loop->first)
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_4{{ $category->id }}">Русский</a>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_5{{ $category->id }}">O’zbek</a>
                        </li>
                        @endif
                        @endforeach
                    </ul>

                    <div class="tab-content mb-9">
                        <div class="tab-pane fade show active" id="kt_tab_pane_4{{ $category->id }}" role="tabpanel">

                            <!--begin::Input group-->
                            <div class="fv-row mb-6">
                                <!--begin::Label-->
                                <label class="fs-6 required fw-bold mb-2">Title</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" placeholder="" name="title_ru" value="{{ $category->title['ru'] ?? '' }}" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-6">
                                <!--begin::Label-->
                                <label class="fs-6 fw-bold mb-2">Description</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <textarea name="desc_ru" class="form-control form-control-solid" id="desc_ru" rows="4" placeholder="description...">{{ $category->desc['ru'] ?? '' }}</textarea>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-6">
                                <!--begin::Label-->
                                <label class="fs-6 fw-bold mb-2">Meta keywords</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <textarea name="meta_keywords_ru" class="form-control form-control-solid" id="desc_ru" rows="4" placeholder="key,keys,klyuchi">{{ $category->meta_keywords['ru'] ?? '' }}</textarea>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-6">
                                <!--begin::Label-->
                                <label class="fs-6 fw-bold mb-2">Meta Description</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <textarea name="meta_desc_ru" class="form-control form-control-solid" id="desc_ru" rows="3" placeholder="meta description...">{{ $category->meta_desc['ru'] ?? '' }}</textarea>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                        </div>
                        <div class="tab-pane fade" id="kt_tab_pane_5{{ $category->id }}" role="tabpanel">

                            <!--begin::Input group-->
                            <div class="fv-row mb-6">
                                <!--begin::Label-->
                                <label class="fs-6 fw-bold mb-2">Title</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" placeholder="" name="title_uz" value="{{ $category->title['uz'] ?? '' }}" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-6">
                                <!--begin::Label-->
                                <label class="fs-6 fw-bold mb-2">Description</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <textarea name="desc_uz" class="form-control form-control-solid" id="desc_ru" rows="4" placeholder="description...">{{ $category->desc['uz'] ?? '' }}</textarea>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-6">
                                <!--begin::Label-->
                                <label class="fs-6 fw-bold mb-2">Meta keywords</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <textarea name="meta_keywords_uz" class="form-control form-control-solid" id="desc_ru" rows="4" placeholder="key,keys,klyuchi">{{ $category->meta_keywords['uz'] ?? '' }}</textarea>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-6">
                                <!--begin::Label-->
                                <label class="fs-6 fw-bold mb-2">Meta Description</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <textarea name="meta_desc_uz" class="form-control form-control-solid" id="desc_ru" rows="3" placeholder="meta description...">{{ $category->meta_desc['uz'] ?? '' }}</textarea>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                        </div>
                    </div>


                    <!--begin::Input group-->
                    <div class="fv-row mb-9">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2">Image</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="file" class="form-control form-control-solid" placeholder="" name="img" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->

                    <div class="fv-row mb-9">
                        <label for="exampleFormControlInput1" class="fs-6 fw-bold mb-2">Parent category</label>
                        <select class="form-select" aria-label="" name="parent_id" data-control="select2" data-hide-search="true">
                            <option value="">Select from the list</option>
                            @foreach($all_categories as $item)
                            <option value="{{ $item->id }}" {{ $category->parent_id == $item->id ? 'selected' : '' }}>{{ $item->title['ru'] }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="fv-row mb-9">
                        <label for="exampleFormControlInput1" class="fs-6 fw-bold mb-2">Status</label>
                        <select class="form-select" aria-label="" name="is_active" data-control="select2" data-hide-search="true">
                            <option value="1" {{ $category->is_active == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $category->is_active == 0 ? 'selected' : '' }}>Not active</option>
                        </select>
                    </div>
                </div>
                <!--end::Modal body-->
                <!--begin::Modal footer-->
                <div class="modal-footer flex-center">
                    <!--begin::Button-->
                    <button type="reset" id="kt_modal_add_event_cancel" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">Submit</span>
                    </button>
                    <!--end::Button-->
                </div>
                <!--end::Modal footer-->
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>
<!--end::Modal - New Product-->
@endforeach

@endsection