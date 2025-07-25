@extends('layouts.app')

@section('title', 'ПРОДУКТЫ')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'Главная',
'route' => 'dashboard'
],
[
'title' => 'Продукты'
]
]
])

@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">Продукты</span>
            <span class="text-muted mt-1 fw-bold fs-7">Всего {{ count($products) }}</span>
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
                        <th class="min-w-125px">Категория</th>
                        <!-- <th class="min-w-150px">Прикрепленная категория</th> -->
                        <!-- <th class="min-w-150px">Статус</th> -->
                        <!-- <th class="min-w-150px">Позиция</th> -->
                        <th class="min-w-75px text-end rounded-end">Действия</th>
                    </tr>
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td class="ps-3 fw-bold">
                            <p>#{{ $loop->iteration }}</p>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="d-flex justify-content-start flex-column">
                                    
                                    <a href="
                                    {{route('integrations.yandex_market.categories', ['id' => $product['integration_id']])}}
                                    " class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{ $product->title['ru'] }}</a>                                    
                                </div>
                            </div>
                        </td>
                        <td>
                            <a class="text-dark text-hover-primary d-block mb-1 fs-6">
                                @foreach($product->categories as $categoryItem)
                                    {{$categoryItem->title['ru']}} {{$loop->last ? '' : ','}}
                                @endforeach
                            </a>
                        </td>
                        <!-- <td>
                            <a class="text-dark text-hover-primary d-block mb-1 fs-6">{{ isset($product->category) ? $product->category->title['ru'] : '-' }}</a>
                        </td> -->
                        <td class="text-end">
                            @if(!isset($product->children[0]))
                            <a class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#edit_category{{ $product['id'] }}">
                                <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                <span class="svg-icon svg-icon-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                        <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                                    </svg>
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


@foreach($products as $product)
<!--begin::Modal - New Product-->
<div class="modal fade" id="edit_category{{ $product['id'] }}" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <form class="form" action="{{ route('integrations.yandex_market.product_characteristics') }}" id="kt_modal_add_event_form" method="post">
                @csrf
                <input type="hidden" name="id" value="{{$product->productVariations[0]['id']}}">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bolder" data-kt-calendar="title">Редактирование характеристик продукта {{$product->title['ru']}}</h2>
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

                    @foreach($characteristics as $characteristic)

                    @switch($characteristic->type)

                    @case('NUMERIC')
                    <!--begin::Input group-->
                    <div class="fv-row mb-6">
                        <!--begin::Label-->
                        <label class="fs-6 {{$characteristic->required ? 'required' : ''}} fw-bold mb-2">{{$characteristic->name}}</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="number" class="form-control form-control-solid" placeholder="..." name="{{$characteristic->market_characteristic_id}}" value="{{$characteristicValues->where('product_variation_id', $product->productVariations[0]->id)->where('characteristic_id', $characteristic->market_characteristic_id)->first()->value ?? null}}" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    @break

                    @case('TEXT')
                    <!--begin::Input group-->
                    <div class="fv-row mb-6">
                        <!--begin::Label-->
                        <label class="fs-6 {{$characteristic->required ? 'required' : ''}} fw-bold mb-2">{{$characteristic->name}}</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control form-control-solid" placeholder="..." name="{{$characteristic->market_characteristic_id}}" value="{{$characteristicValues->where('product_variation_id', $product->productVariations[0]->id)->where('characteristic_id', $characteristic->market_characteristic_id)->first()->value ?? null}}" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    @break

                    @case('BOOLEAN')
                    <div class="fv-row mb-9">
                        <label for="{{$characteristic->market_characteristic_id}}" class="fs-6 fw-bold mb-2">{{$characteristic->name}}  ({{$characteristic->description}})</label>
                        <select class="form-select" aria-label="" id="{{$characteristic->market_characteristic_id}}" name="{{$characteristic->market_characteristic_id}}">
                            <option value=""></option>
                            <option value="1" {{($characteristicValues->where('product_variation_id', $product->productVariations[0]->id)->where('characteristic_id', $characteristic->market_characteristic_id)->first()->value ?? null) == 1 ? 'selected' : ''}}>Да</option>
                            <option value="0" {{($characteristicValues->where('product_variation_id', $product->productVariations[0]->id)->where('characteristic_id', $characteristic->market_characteristic_id)->first()->value ?? null) === '0' ? 'selected' : ''}}>Нет</option>
                        </select>
                    </div>
                    @break

                    @case('ENUM')

                        @if(isset($characteristic->values))
                        <div class="fv-row mb-9">
                            <label for="{{$characteristic->market_characteristic_id}}" class="fs-6 fw-bold mb-2">{{$characteristic->name}} {{$characteristic->allowCustomValues ? '(Выберите из списка или напишите)' : ''}}</label>
                            <select class="form-select" aria-label="" id="{{$characteristic->market_characteristic_id}}" name="{{$characteristic->market_characteristic_id}}[select]">
                                <option value=""></option>
                                @foreach(json_decode($characteristic->values, true) as $item)
                                <option value="{{$item['id']}}" {{($characteristicValues->where('product_variation_id', $product->productVariations[0]->id)->where('characteristic_id', $characteristic->market_characteristic_id)->first()->select_value ?? null) == $item['id'] ? 'selected' : ''}}>{{$item['value']}}</option>
                                @endforeach
                            </select>
                        </div>

                            @if($characteristic->allowCustomValues)
                            <!--begin::Input group-->
                            <div class="fv-row mb-6">
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" placeholder="..." name="{{$characteristic->market_characteristic_id}}[input]" value="{{$characteristicValues->where('product_variation_id', $product->productVariations[0]->id)->where('characteristic_id', $characteristic->market_characteristic_id)->first()->value ?? null}}" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            @endif

                        @else
                        <!--begin::Input group-->
                        <div class="fv-row mb-6">
                            <!--begin::Label-->
                            <label class="fs-6 {{$characteristic->required ? 'required' : ''}} fw-bold mb-2">{{$characteristic->name}}</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" class="form-control form-control-solid" placeholder="" name="{{$characteristic->market_characteristic_id}}" value="{{$characteristicValues->where('product_variation_id', $product->productVariations[0]->id)->where('characteristic_id', $characteristic->market_characteristic_id)->first()->value ?? null}}" />
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        @endif

                    @break

                    @endswitch

                    @endforeach

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