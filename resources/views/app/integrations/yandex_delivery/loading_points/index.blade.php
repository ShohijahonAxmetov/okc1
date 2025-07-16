@extends('layouts.app')

@section('title', 'Пункты погрузки')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'Главная',
'route' => 'dashboard'
],
[
'title' => 'Пункты погрузки'
]
]
])

@endsection

@section('content')

<script src="https://api-maps.yandex.ru/2.1/?apikey=5a063b97-1242-44c9-827b-dadab1a2b1ad&lang=ru_RU" type="text/javascript"></script>
<style>
@foreach($loadingPoints as $point)
#map{{$point->id}} {
    width: 100%;
    height: 300px;
}
@endforeach

.loader {
  width: 32px;
  aspect-ratio: 2;
  --_g: no-repeat radial-gradient(circle closest-side,#fff 90%,#0000);
  background: 
    var(--_g) 0%   50%,
    var(--_g) 50%  50%,
    var(--_g) 100% 50%;
  background-size: calc(100%/3) 50%;
  animation: l3 1s infinite linear;
}
@keyframes l3 {
    20%{background-position:0%   0%, 50%  50%,100%  50%}
    40%{background-position:0% 100%, 50%   0%,100%  50%}
    60%{background-position:0%  50%, 50% 100%,100%   0%}
    80%{background-position:0%  50%, 50%  50%,100% 100%}
}
</style>

<div class="card mb-5 mb-xl-8">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5 d-flex">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">Пункты погрузки</span>
            <span class="text-muted mt-1 fw-bold fs-7">Всего {{ count($loadingPoints) }}</span>
        </h3>
        <div>
            <a href="{{route('integrations.yandex_delivery.loading_points.create')}}" class="btn btn-success">Создать</a>
        </div>
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
                        <th class="min-w-325px">Наименование</th>
                        <th class="min-w-125px">Точный адрес</th>
                        <th class="min-w-150px">Описание</th>
                        <th class="min-w-150px">Комменты</th>
                        <!-- <th class="min-w-150px">Предварительная сумма</th> -->
                        <!-- <th class="min-w-150px">Время создания</th> -->
                        <th class="min-w-75px text-end rounded-end">Действия</th>
                    </tr>
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody>
                    @foreach($loadingPoints as $order)
                    <tr>
                        <td class="ps-3 fw-bold">
                            <p>#{{ $loop->iteration }}</p>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="d-flex justify-content-start flex-column">
                                    
                                    <a href="{{route('integrations.yandex_market.categories', ['id' => $order->id])}}
                                    " class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{$order->name}}</a>                                    
                                </div>
                            </div>
                        </td>
                        <td>
                            <a class="text-dark text-hover-primary d-block mb-1 fs-6">{{ $order->address ?? '-' }}</a>
                        </td>
                        <td>
                            <a class="text-dark text-hover-primary d-block mb-1 fs-6">{{ $order->desc ?? '-' }}</a>
                        </td>
                        <td>
                            <a class="text-dark text-hover-primary d-block mb-1 fs-6">{{ $order->comments ?? '-' }}</a>
                        </td>
                        <!-- <td>
                            <a class="text-dark text-hover-primary d-block mb-1 fs-6">{{ $order->preliminary_amount }}</a>
                        </td> -->
                        <!-- <td>
                            <a class="text-dark text-hover-primary d-block mb-1 fs-6">{{ date('H:i d-m-Y', strtotime($order->created_at)) }}</a>
                        </td> -->
                        <td class="text-end">
                            <div class="d-flex justify-content-end">
                                <form action="{{ route('integrations.yandex_delivery.loading_points.delete', ['id' => $order->id]) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="button" onclick="confirmation(this)" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" type="button">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </form>
                                <a class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 ms-4" data-bs-toggle="modal" data-bs-target="#edit_banner{{ $order->id }}">
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                            <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </a>
                            </div>
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


@foreach($loadingPoints as $order)
<!--begin::Modal - New Product-->
<div class="modal fade" id="edit_banner{{ $order->id }}" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <form class="form" action="{{ route('integrations.yandex_delivery.loading_points.update', ['id' => $order->id]) }}" id="kt_modal_add_event_form" method="post">
                @csrf
                @method('put')
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bolder" data-kt-calendar="title">Редактирование</h2>
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

                    <!--begin::Input group-->
                    <div class="fv-row mb-9">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold required mb-2">Наименование</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control form-control-solid" required placeholder="https://example.com" name="name" value="{{ old('name', $order->name) }}" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="fv-row mb-9">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2">Точный адрес</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control form-control-solid" placeholder="..." name="address" value="{{ old('address', $order->address) }}" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="fv-row mb-9">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2">Описание</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control form-control-solid" placeholder="..." name="desc" value="{{ old('desc', $order->desc) }}" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="fv-row mb-9">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2">Комментарий для водителя</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control form-control-solid" placeholder="..." name="comments" value="{{ old('comments', $order->comments) }}" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->

                    <div id="map{{$order->id}}" class="mb-3"></div>

                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-6">
                            <div class="fv-row mb-6">
                                <!--begin::Label-->
                                <label class="fs-6 required fw-bold mb-2">Широта</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" id="lat_input{{$order->id}}" class="form-control form-control-solid" placeholder="" name="lat" value="{{$order->lat}}" required />
                                <!--end::Input-->
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="fv-row mb-6">
                                <!--begin::Label-->
                                <label class="fs-6 required fw-bold mb-2">Долгота</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" id="lon_input{{$order->id}}" class="form-control form-control-solid" placeholder="" name="lon" value="{{$order->lon}}" required />
                                <!--end::Input-->
                            </div>
                        </div>
                    </div>
                    <!--end::Input group-->

                </div>
                <!--end::Modal body-->
                <!--begin::Modal footer-->
                <div class="modal-footer flex-center">
                    <!--begin::Button-->
                    <button type="reset" id="kt_modal_add_event_cancel" class="btn btn-light me-3" data-bs-dismiss="modal">Отмена</button>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button type="submit" id="success_button" class="btn btn-primary">
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
    function confirmation(item) {
        Swal.fire({
            title: 'Вы уверены?',
            text: "Вы не сможете отменить это!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Да, удалить!'
        }).then((result) => {
            if (result.value) {
                item.parentNode.submit();
            }
        });
    }
</script>

@foreach($loadingPoints as $point)
<script>
    
    ymaps.ready(function () {
        const map{{$point->id}} = new ymaps.Map("map{{$point->id}}", {
            center: [{{$point->lat}}, {{$point->lon}}],
            zoom: 15
        });

        // Добавим метку в центр
        const placemark_{{$point->id}} = new ymaps.Placemark([{{$point->lat}}, {{$point->lon}}], {
            balloonContent: `<br>lat: ${{{$point->lat}}}, lon: ${{{$point->lon}}}`
        }, {
            preset: 'islands#greenDotIcon'
        });

        map{{$point->id}}.geoObjects.add(placemark_{{$point->id}});

        let placemark{{$point->id}};

        map{{$point->id}}.events.add('click', function (e) {
            const [lat{{$point->id}}, lon{{$point->id}}] = e.get('coords');

            // Вставка в input поля
            document.getElementById('lat_input{{$point->id}}').value = lat{{$point->id}};
            document.getElementById('lon_input{{$point->id}}').value = lon{{$point->id}};

            // Удаление предыдущей метки
            if (placemark{{$point->id}}) {
                map{{$point->id}}.geoObjects.remove(placemark{{$point->id}});
            }

            // Добавление новой метки
            placemark{{$point->id}} = new ymaps.Placemark([lat{{$point->id}}, lon{{$point->id}}], {
                balloonContent: `lat: ${lat{{$point->id}}.toFixed(6)}<br>lon: ${lon{{$point->id}}.toFixed(6)}`
            }, {
                preset: 'islands#redDotIcon'
            });

            map{{$point->id}}.geoObjects.add(placemark{{$point->id}});
        });
    });

</script>
@endforeach

@endsection