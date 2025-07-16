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
#a_map {
    width: 80%;
    margin-left: 10%;
    height: 400px;
}


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
            <span class="card-label fw-bolder fs-3 mb-1">Создание</span>
            <span class="text-muted mt-1 fw-bold fs-7">Всего</span>
        </h3>
        <div>
            <a href="{{route('integrations.yandex_delivery.loading_points')}}" class="btn btn-danger">Отменить</a>
        </div>
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body py-5">
        <form class="form" action="{{ route('integrations.yandex_delivery.loading_points.store') }}" method="post">
            @csrf
            <!--begin::Modal body-->
            <div class="pt-5">

                <!--begin::Input group-->
                <div class="fv-row mb-9">
                    <!--begin::Label-->
                    <label class="fs-6 fw-bold required mb-2">Наименование</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="text" class="form-control form-control-solid" required placeholder="https://example.com" name="name" value="{{ old('name') }}" />
                    <!--end::Input-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="fv-row mb-9">
                    <!--begin::Label-->
                    <label class="fs-6 fw-bold mb-2">Точный адрес</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="text" class="form-control form-control-solid" placeholder="..." name="address" value="{{ old('address') }}" />
                    <!--end::Input-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="fv-row mb-9">
                    <!--begin::Label-->
                    <label class="fs-6 fw-bold mb-2">Описание</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="text" class="form-control form-control-solid" placeholder="..." name="desc" value="{{ old('desc') }}" />
                    <!--end::Input-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="fv-row mb-9">
                    <!--begin::Label-->
                    <label class="fs-6 fw-bold mb-2">Комментарий для водителя</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="text" class="form-control form-control-solid" placeholder="..." name="comments" value="{{ old('comments') }}" />
                    <!--end::Input-->
                </div>
                <!--end::Input group-->

                <div id="a_map" class="mb-3"></div>

                <!--begin::Input group-->
                <div class="row">
                    <div class="col-6">
                        <div class="fv-row mb-6">
                            <!--begin::Label-->
                            <label class="fs-6 required fw-bold mb-2">Широта</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" id="a_lat_input" class="form-control form-control-solid" placeholder="" name="lat" value="" required />
                            <!--end::Input-->
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fv-row mb-6">
                            <!--begin::Label-->
                            <label class="fs-6 required fw-bold mb-2">Долгота</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" id="a_lon_input" class="form-control form-control-solid" placeholder="" name="lon" value="" required />
                            <!--end::Input-->
                        </div>
                    </div>
                </div>
                <!--end::Input group-->

            </div>

            <div class="d-flex justify-content-end">
                <!--begin::Button-->
                <button type="submit" class="btn btn-primary">
                    <span class="indicator-label">Сохранить</span>
                </button>
                <!--end::Button-->
            </div>
        </form>
    </div>
    <!--begin::Body-->
</div>

@endsection

@section('scripts')

<script>
    ymaps.ready(init);

    function init() {
        const map = new ymaps.Map("a_map", {
            center: [41.311455248270185, 69.27955834412408],
            zoom: 15
        });

        let placemark;

        map.events.add('click', function (e) {
            const [lat, lon] = e.get('coords');

            // Вставка в input поля
            document.getElementById('a_lat_input').value = lat;
            document.getElementById('a_lon_input').value = lon;

            // Удаление предыдущей метки
            if (placemark) {
                map.geoObjects.remove(placemark);
            }

            // Добавление новой метки
            placemark = new ymaps.Placemark([lat, lon], {
                balloonContent: `lat: ${lat.toFixed(6)}<br>lon: ${lon.toFixed(6)}`
            }, {
                preset: 'islands#redDotIcon'
            });

            map.geoObjects.add(placemark);
        });
    }
  </script>

@endsection