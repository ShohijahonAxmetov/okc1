@extends('layouts.app')

@section('title', 'КАТЕГОРИИ')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'Главная',
'route' => 'dashboard'
],
[
'title' => 'Категории'
]
]
])

@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">Категории</span>
            <span class="text-muted mt-1 fw-bold fs-7">Всего {{ count($categories) }}</span>
        </h3>
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
                        <th class="ps-4 min-w-75px rounded-start">#</th>
                        <th class="min-w-325px">Название</th>
                        <th class="min-w-125px">Категория с товарами</th>
                        <th class="min-w-150px">Прикрепленная категория</th>
                        <!-- <th class="min-w-150px">Статус</th> -->
                        <!-- <th class="min-w-150px">Позиция</th> -->
                        <th class="min-w-75px text-end rounded-end">Действия</th>
                    </tr>
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td class="ps-3 fw-bold">
                            <p>#{{ $loop->iteration }}</p>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="d-flex justify-content-start flex-column">
                                    
                                    <a href="
                                    {{route('integrations.yandex_market.categories', ['id' => $category['integration_id']])}}
                                    " class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{ $category['name'] }}</a>                                    
                                </div>
                            </div>
                        </td>
                        <td>
                            <a class="text-dark text-hover-primary d-block mb-1 fs-6">{{ isset($category->children[0]) ? 'Нет' : 'Да' }}</a>
                        </td>
                        <td>
                            <a class="text-dark text-hover-primary d-block mb-1 fs-6">{{ isset($category->category) ? $category->category->title['ru'] : '-' }}</a>
                        </td>
                        <td class="text-end">
                            @if(!isset($category->children[0]))
                            <a class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#edit_category{{ $category['id'] }}">
                                <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                <span class="svg-icon svg-icon-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" height="800px" width="800px" version="1.1" id="Capa_1" viewBox="0 0 490.125 490.125" xml:space="preserve"><g><g><g><path d="M300.625,5.025c-6.7-6.7-17.6-6.7-24.3,0l-72.6,72.6c-6.7,6.7-6.7,17.6,0,24.3l16.3,16.3l-40.3,40.3l-63.5-7     c-3-0.3-6-0.5-8.9-0.5c-21.7,0-42.2,8.5-57.5,23.8l-20.8,20.8c-6.7,6.7-6.7,17.6,0,24.3l108.5,108.5l-132.4,132.4     c-6.7,6.7-6.7,17.6,0,24.3c3.3,3.3,7.7,5,12.1,5s8.8-1.7,12.1-5l132.5-132.5l108.5,108.5c3.3,3.3,7.7,5,12.1,5s8.8-1.7,12.1-5     l20.8-20.8c17.6-17.6,26.1-41.8,23.3-66.4l-7-63.5l40.3-40.3l16.2,16.2c6.7,6.7,17.6,6.7,24.3,0l72.6-72.6c3.2-3.2,5-7.6,5-12.1     s-1.8-8.9-5-12.1L300.625,5.025z M400.425,250.025l-16.2-16.3c-6.4-6.4-17.8-6.4-24.3,0l-58.2,58.3c-3.7,3.7-5.5,8.8-4.9,14     l7.9,71.6c1.6,14.3-3.3,28.3-13.5,38.4l-8.7,8.7l-217.1-217.1l8.7-8.6c10.1-10.1,24.2-15,38.4-13.5l71.7,7.9     c5.2,0.6,10.3-1.2,14-4.9l58.2-58.2c6.7-6.7,6.7-17.6,0-24.3l-16.3-16.3l48.3-48.3l160.3,160.3L400.425,250.025z"/></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></g></svg>
                                </span>
                                <!--end::Svg Icon-->
                            </a>
                            @endif
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
<div class="modal fade" id="edit_category{{ $category['id'] }}" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <form class="form" action="{{ route('integrations.yandex_market.pin_category') }}" id="kt_modal_add_event_form" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{$category['integration_id']}}">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bolder" data-kt-calendar="title">Прикрепление категории к "{{ $category['name'] }}"</h2>
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
                    <div class="fv-row mb-9">
                        <label for="exampleFormControlInput1" class="fs-6 fw-bold mb-2">Наша категория</label>
                        <select class="form-select" aria-label="" name="our_category_id" data-control="select2" data-hide-search="true">
                            @foreach($ourCategories as $ourCategory)
                            <option value="{{$ourCategory->id}}" {{ isset($category->category) && ($category->category->id == $ourCategory->id) ? 'selected' : '' }}>{{$ourCategory->title['ru']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!--end::Modal body-->
                <!--begin::Modal footer-->
                <div class="modal-footer flex-center">
                    <!--begin::Button-->
                    <button type="reset" id="kt_modal_add_event_cancel" class="btn btn-light me-3" data-bs-dismiss="modal">Отмена</button>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">Сохранить</span>
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

@section('scripts')

<script>
    document.getElementById('clear_btn').addEventListener('click', function() {
        document.querySelector("[name='search']").value = '';
    });
</script>

@endsection