@extends('layouts.app')

@section('title', 'ДОСТАВКА')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'Главная',
'route' => 'dashboard'
],
[
'title' => 'Доставка'
]
]
])

@endsection

@section('content')

<script src="https://api-maps.yandex.ru/2.1/?apikey=5a063b97-1242-44c9-827b-dadab1a2b1ad&lang=ru_RU" type="text/javascript"></script>
<style>
#a_map {
    width: 80%;
    height: 400px;
    margin-left: 10%;
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
            <span class="card-label fw-bolder fs-3 mb-1">Доставка</span>
        </h3>
        <div>
            <a href="{{route('integrations.yandex_delivery.orders')}}" class="btn btn-danger">Отменить</a>
        </div>
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body py-5">
        <form class="form" action="{{ route('integrations.yandex_delivery.order') }}" method="post">
            @csrf
            <!--begin::Modal body-->
            <div class="pt-5">

                <!--begin::Input group-->
                <div class="fv-row mb-9">
                    <label for="order_id" class="fs-6 fw-bold mb-2">Для какого заказа</label>
                    <select class="form-select" aria-label="order_id" name="order_id">
                        <option value=""></option>
                        @if($currentOrder)
                        @foreach([$currentOrder] as $order)
                        <option value="{{$order->id}}" @if(isset($currentOrder) && $currentOrder->id == $order->id) selected @endif>ID: {{$order->id}}, {{$order->name}}, {{$order->phone_number}}, {{date('d-m-Y H:i', strtotime($order->created_at))}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="fv-row mb-9">
                    <label for="start_point" class="fs-6 fw-bold mb-2">Пункт погрузки</label>
                    <select class="form-select" aria-label="start_point" name="start_point" id="start_point">
                        <option value=""></option>
                        @foreach($loadingPoints as $point)
                        <option value="{{$point->id}}" data-lat="{{$point->lat}}" data-lon="{{$point->lon}}">ID: {{$point->id}}, {{$point->name}}</option>
                        @endforeach
                    </select>
                </div>
                <!--end::Input group-->


                <div id="a_map" class="mb-3"></div>

                <!--begin::Input group-->
                <div class="row">
                    <div class="col-6">
                        <div class="fv-row mb-6">
                            <!--begin::Label-->
                            <!-- <label class="fs-6 required fw-bold mb-2">Точка отправления (Широта)</label> -->
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="hidden" id="a_lat_input" class="form-control form-control-solid" placeholder="" name="a_lat" value="" required />
                            <!--end::Input-->
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fv-row mb-6">
                            <!--begin::Label-->
                            <!-- <label class="fs-6 required fw-bold mb-2">Точка отправления (Долгота)</label> -->
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="hidden" id="a_lon_input" class="form-control form-control-solid" placeholder="" name="a_lon" value="" required />
                            <!--end::Input-->
                        </div>
                    </div>
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row">
                    <div class="col-6">
                        <div class="fv-row mb-6">
                            <!--begin::Label-->
                            <!-- <label class="fs-6 required fw-bold mb-2">Точка назначения (Широта)</label> -->
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="hidden" id="b_lat_input" class="form-control form-control-solid" placeholder="" name="b_lat" value="{{$currentOrder ? $currentOrder->lat : ''}}" required />
                            <!--end::Input-->
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fv-row mb-6">
                            <!--begin::Label-->
                            <!-- <label class="fs-6 required fw-bold mb-2">Точка назначения (Долгота)</label> -->
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="hidden" id="b_lon_input" class="form-control form-control-solid" placeholder="" name="b_lon" value="{{$currentOrder ? $currentOrder->lon : ''}}" required />
                            <!--end::Input-->
                        </div>
                    </div>
                </div>
                <!--end::Input group-->

                <div class="row mb-6">
                    <div class="col-9">
                        <!--begin::Label-->
                        <label class="fs-6 fw-bold mb-2 required">Предварительная сумма</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control form-control-solid" id="preliminary_amount" required placeholder="" name="preliminary_amount" value="" />
                        <!--end::Input-->
                    </div>
                    <!--begin::Modal footer-->
                    <div class="col-3 align-content-end">
                        <!--begin::Button-->
                        <button type="button" id="check_price" class="btn btn-primary w-100">
                            Рассчитать
                        </button>
                        <!--end::Button-->
                    </div>
                    <span class="small" id="small_txt"></span>
                </div>

            </div>

            <div class="d-flex justify-content-end">
                <!--begin::Button-->
                <button type="submit" class="btn btn-success d-none" id="order_btn">
                    <span class="indicator-label">Заказать</span>
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
    ymaps.ready(function () {

      @if($currentOrder)
      
      let = startCoords = [{{$currentOrder->lat}}, {{$currentOrder->lon}}];

      @else

      let startCoords = [41.311455248270185, 69.27955834412408];

      @endif

      const map = new ymaps.Map("a_map", {
        center: startCoords,
        zoom: 15
      });

      @if($currentOrder)

      const startPlacemark = new ymaps.Placemark(startCoords, {
        balloonContent: 'Начальная точка: Локация заказа № {{$currentOrder->id}}'
      }, {
        preset: 'islands#redGovernmentIcon'
      });

      map.geoObjects.add(startPlacemark);

      @endif

      let destinationPlacemark = null;

      document.getElementById('start_point').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const startLat = selectedOption.dataset.lat;
        const startLon = selectedOption.dataset.lon;

        let startPointLatInput = document.getElementById('a_lat_input');
        let startPointLonInput = document.getElementById('a_lon_input');
        startPointLatInput.value = startLat;
        startPointLonInput.value = startLon;

        // Удаляем старую метку, если есть
        if (destinationPlacemark) {
          map.geoObjects.remove(destinationPlacemark);
        }

        if (selectedOption) {
          const [lat, lon] = [startLat, startLon];
          destinationPlacemark = new ymaps.Placemark([lat, lon], {
            balloonContent: 'Точка назначения'
          }, {
            preset: 'islands#blueIcon'
          });

          map.geoObjects.add(destinationPlacemark);

          // Зумируем карту так, чтобы обе точки были видны
          const bounds = ymaps.geoQuery([startPlacemark, destinationPlacemark]).getBounds();
          map.setBounds(bounds, {
            checkZoomRange: true,
            zoomMargin: 30
          });
        }

        // рассчитать заново сумму заказа
        let preliminaryInput = document.getElementById('preliminary_amount');
        let orderBtnHidden = document.getElementById('order_btn');
        let smallTextSpan = document.getElementById('small_txt');

        preliminaryInput.value = '';
        orderBtnHidden.classList.add('d-none');
        smallTextSpan.innerHTML = '';
      });
    });


    let checkPriceBtn = document.getElementById('check_price');

    checkPriceBtn.addEventListener('click', (event) => {
        event.target.innerHTML = '<span class="loader d-inline-block"></span>';

        fetch('https://admin.okc.uz/api/for/check_price', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                a_lat: document.getElementById('a_lat_input').value,
                a_lon: document.getElementById('a_lon_input').value,
                b_lat: document.getElementById('b_lat_input').value,
                b_lon: document.getElementById('b_lon_input').value,
                order_id: document.querySelector('select[name="order_id"]').value,
                start_point: document.querySelector('select[name="start_point"]').value,
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Ответ от API:', data);

            document.getElementById('preliminary_amount').value = data.pricing.offer.price;

            let res2show = {
                'Примерное время ожидания (сек)': data.route_points[0].visited_at.expected_waiting_time_sec
            };
            document.getElementById('small_txt').textContent = 'Детали заказа: ' + JSON.stringify(res2show);

            let orderBtn = document.getElementById('order_btn');
            orderBtn.classList.remove('d-none');

            event.target.innerHTML = 'Рассчитать';
        })
        .catch(error => event.target.innerHTML = 'Рассчитать');

    });
</script>

@endsection