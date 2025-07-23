@extends('layouts.app')

@section('title', 'ЗАКАЗЫ')

@section('links')

<style>
	.stepwizard-step p {
	    margin-top: 10px;    
	}

	.process-row {
	    /*display: table-row;*/
	    display: flex;
	    justify-content: space-around;
	}

	.process {
	    display: table;     
	    width: 100%;
	    position: relative;
	}

	.process-step button[disabled] {
	    opacity: 1 !important;
	    filter: alpha(opacity=100) !important;
	}

	.process-row:before {
	    top: 40px;
	    bottom: 0;
	    position: absolute;
	    content: " ";
	    width: 100%;
	    height: 1px;
	    background-color: #ccc;
	    z-order: 0;
	    
	}

	.process-step {    
	    /*display: table-cell;
	    text-align: center;
	    position: relative;*/
        display: flex;
	    flex-direction: column;
	    align-items: center;
	}

	.process-step > span {    
	    display: flex;
	    justify-content: center;
	    align-items: center;
	    z-index: 1;
	}

	.process-step p {
	    margin-top:10px;
	    
	}

	.btn-circle {
	  width: 80px;
	  height: 80px;
	  text-align: center;
	  padding: 6px 0;
	  font-size: 12px;
	  line-height: 1.428571429;
	  border-radius: 15px;
	}


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
'title' => 'Заказы',
'route' => 'integrations.yandex_eats.orders'
],
[
'title' => 'Изменить'
]
]
])

@endsection

@section('content')

<div class="row">
	<div class="d-flex w-100 justify-content-end mb-5">
        <a href="{{ route('integrations.yandex_eats.index') }}" class="btn btn-info ms-5">Назад</a>
    </div>
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Статус заказа</h3>
			</div>
			<div class="card-body">
				<div class="process">
				    <div class="process-row">
				        <div class="process-step">
				            <span class="btn {{ $order->currentStatus == 'NEW' ? 'btn-success' : 'bg-white' }} btn-circle">
				            	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path @if($order->currentStatus == 'NEW') fill="#fff" @endif d="M320 0H64C28.65 0 0 28.65 0 64v384c0 35.35 28.65 64 64 64h256c35.35 0 64-28.65 64-64V64C384 28.65 355.3 0 320 0zM208 352h-128C71.16 352 64 344.8 64 336S71.16 320 80 320h128c8.838 0 16 7.164 16 16S216.8 352 208 352zM304 256h-224C71.16 256 64 248.8 64 240S71.16 224 80 224h224C312.8 224 320 231.2 320 240S312.8 256 304 256zM304 160h-224C71.16 160 64 152.8 64 144S71.16 128 80 128h224C312.8 128 320 135.2 320 144S312.8 160 304 160z"/></svg>
				            </span>
				            <p>1. Новый заказ</p>
				        </div>
				        <div class="process-step">
				            <span class="btn {{ $order->currentStatus == 'ACCEPTED_BY_RESTAURANT' ? 'btn-success' : 'bg-white' }} btn-circle">
				            	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path @if($order->currentStatus == 'ACCEPTED_BY_RESTAURANT') fill="#fff" @endif d="M256 368c0-80.39 54.24-148.2 128-169.2V64c0-35.35-28.65-64-64-64H64C28.65 0 0 28.65 0 64v384c0 35.35 28.65 64 64 64h256c3.357 0 6.548-.487 9.766-.985C285.2 479.1 256 426.9 256 368zM80 128h224C312.8 128 320 135.2 320 144S312.8 160 304 160h-224C71.16 160 64 152.8 64 144S71.16 128 80 128zM176 352h-96C71.16 352 64 344.8 64 336S71.16 320 80 320h96C184.8 320 192 327.2 192 336S184.8 352 176 352zM240 256h-160C71.16 256 64 248.8 64 240S71.16 224 80 224h160C248.8 224 256 231.2 256 240S248.8 256 240 256zM432 224C352.5 224 288 288.5 288 368s64.46 144 144 144C511.5 512 576 447.5 576 368S511.5 224 432 224zM499.3 341.1l-74.66 74.66c-3.125 3.125-7.219 4.688-11.31 4.688s-8.188-1.562-11.31-4.688l-37.34-37.33c-6.25-6.25-6.25-16.38 0-22.62s16.38-6.25 22.62 0l26.03 26.02l63.34-63.34c6.25-6.25 16.38-6.25 22.62 0S505.6 335.7 499.3 341.1z"/></svg>
				            </span>
				            <p>2. Заказ принят</p>
				        </div>
				        <div class="process-step">
				            <span class="btn {{ $order->currentStatus == 'COOKING' ? 'btn-success' : 'bg-white' }} btn-circle">
				            	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path @if($order->currentStatus == 'COOKING') fill="#fff" @endif d="M194.3 143.8C192.9 138.7 192 133.4 192 128.1c0-35.25 28.75-64.03 64-64.03s64 28.78 64 64.03c0 11.25-3.125 21.52-8.25 30.77l8.25 1.001l155.3-19.76l35.75-98.3c2.1-8.375-1.25-17.51-9.625-20.51l-55.5-20.26c-8.25-2.1-17.5 1.255-20.5 9.63l-41.63 114.4C382 55.82 325.8 0 256 0C185.3 0 128 57.3 128 128c0 2.5 .627 4.896 .752 7.396L194.3 143.8zM638.3 271.8L586.8 169c-3-6.25-9.757-9.758-16.63-8.883l-250.2 31.87l91.74 152.1c3.752 6.25 11.38 9.261 18.51 7.261l197.9-56.51C638 292 642.8 281 638.3 271.8zM228.3 344.1l91.73-152.1l-250.2-31.87C62.9 159.3 56.24 162.8 53.24 169L1.755 271.8C-2.87 281.1 2.038 292.1 11.79 294.8l197.1 56.53C216.8 353.4 224.4 350.4 228.3 344.1zM425.8 385.3c-17 0-32.88-8.949-41.5-23.24L320 256l-64.25 106c-8.625 14.42-24.5 23.37-41.5 23.37c-4.5 0-9-.6211-13.25-1.863L64 344.5v80.99c0 14.67 9.999 27.35 24.25 30.83l216.1 53.82c10.25 2.486 20.88 2.486 31 0l216.4-53.82C565.1 452.7 576 440 576 425.5v-80.99l-137 38.9C434.8 384.6 430.3 385.3 425.8 385.3z"/></svg>
				            </span>
				            <p>3. Заказ собирается</p>
				        </div> 
				        <div class="process-step">
				            <span class="btn {{ $order->currentStatus == 'READY' ? 'btn-success' : 'bg-white' }} btn-circle">
				            	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path @if($order->currentStatus == 'READY') fill="#fff" @endif d="M447.6 158.8l-56.38-104.9C386.9 40.75 374.8 32 360.9 32H240v128h206.8C447.1 159.5 447.3 159.3 447.6 158.8zM0 192v240C0 458.5 21.49 480 48 480h352c26.51 0 48-21.49 48-48V192H0zM312.1 296.1l-96 96C212.3 397.7 206.1 400 200 400s-12.28-2.344-16.97-7.031l-48-48c-9.375-9.375-9.375-24.56 0-33.94s24.56-9.375 33.94 0L200 342.1l79.03-79.03c9.375-9.375 24.56-9.375 33.94 0S322.3 287.6 312.1 296.1zM208 160V32H87.13c-13.88 0-26 8.75-30.38 21.88L.375 158.8C.75 159.3 .875 159.5 1.25 160H208z"/></svg>
				            </span>
				            <p>4. Заказ собран</p>
				        </div> 
				        <div class="process-step">
				            <span class="btn {{ $order->currentStatus == 'TAKEN_BY_COURIER' ? 'btn-success' : 'bg-white' }} btn-circle">
				            	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path @if($order->currentStatus == 'TAKEN_BY_COURIER') fill="#fff" @endif d="M624 352H608V237.3c0-16.97-6.746-33.25-18.74-45.24l-77.26-77.26C499.1 102.7 483.7 96 466.8 96H416V48C416 21.49 394.5 0 368 0h-256C85.49 0 64 21.49 64 48V96H15.1C7.164 96 0 103.2 0 111.1C0 120.8 7.164 128 15.1 128h256C280.8 128 288 135.2 288 144C288 152.8 280.8 160 272 160h-224C39.16 160 32 167.2 32 176C32 184.8 39.16 192 47.1 192h192C248.8 192 256 199.2 256 208C256 216.8 248.8 224 240 224h-224C7.164 224 0 231.2 0 240C0 248.8 7.164 256 15.1 256h192C216.8 256 224 263.2 224 272C224 280.8 216.8 288 208 288H64v128c0 53 43 96 96 96s96-43 96-96h128c0 53 43 96 96 96s96-43 96-96h48c8.801 0 16-7.201 16-16v-32C640 359.2 632.8 352 624 352zM160 464c-26.5 0-48-21.5-48-48s21.5-48 48-48s48 21.5 48 48S186.5 464 160 464zM480 464c-26.5 0-48-21.5-48-48s21.5-48 48-48s48 21.5 48 48S506.5 464 480 464zM544 256h-128V160h50.75L544 237.3V256z"/></svg>
				            </span>
				            <p>5. Заказ в пути</p>
				        </div> 
				        <div class="process-step">
				            <span class="btn {{ $order->currentStatus == 'DELIVERED' ? 'btn-success' : 'bg-white' }} btn-circle">
				            	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path @if($order->currentStatus == 'DELIVERED') fill="#fff" @endif d="M543.8 256c0-36.5-18.86-68.38-46.86-86.75c6.875-32.88-2.517-68.63-28.14-94.25c-25.62-25.75-61.61-35.13-94.36-28.25C355.1 18.75 324.1 0 287.8 0S219.5 18.75 200.1 46.75C168.1 39.88 132.5 49.38 106.8 75C81.09 100.6 71.61 136.5 78.48 169.3C50.36 187.8 31.84 219.8 31.84 256s18.64 68.25 46.64 86.75C71.61 375.6 81.21 411.4 106.8 437c25.62 25.75 61.14 35.13 94.14 28.25C219.5 493.4 251.6 512 287.8 512c36.38 0 68.14-18.75 86.64-46.75c33 6.875 68.73-2.625 94.36-28.25c25.75-25.62 35.02-61.5 28.14-94.25C525.1 324.3 543.8 292.3 543.8 256zM384.1 216.1l-112 112C268.3 333.7 262.1 336 256 336s-12.28-2.344-16.97-7.031l-48-48c-9.375-9.375-9.375-24.56 0-33.94s24.56-9.375 33.94 0L256 278.1l95.03-95.03c9.375-9.375 24.56-9.375 33.94 0S394.3 207.6 384.1 216.1z"/></svg>
				            </span>
				            <p>6. Доставлен</p>
				        </div>
				        <div class="process-step">
				            <span class="btn {{ $order->currentStatus == 'CANCELLED' ? 'btn-danger' : 'bg-white' }} btn-circle">
				            	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path @if($order->currentStatus == 'CANCELLED') fill="#fff" @endif d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM362.3 344.5c8.562 10.11 7.297 25.27-2.828 33.83C355 382.1 349.5 384 344 384c-6.812 0-13.59-2.891-18.34-8.5L256 293.2L186.3 375.5C181.6 381.1 174.8 384 167.1 384C162.5 384 157 382.1 152.5 378.3c-10.12-8.562-11.39-23.72-2.828-33.83L224.6 256L149.7 167.5C141.1 157.4 142.4 142.2 152.5 133.7C162.6 125.1 177.8 126.4 186.3 136.5L256 218.8l69.67-82.34c8.547-10.12 23.72-11.41 33.83-2.828c10.12 8.562 11.39 23.72 2.828 33.83L287.4 256L362.3 344.5z"/></svg>
				            </span>
				            <p>7. Отменено</p>
				        </div> 
				    </div>
				</div>
				<div class="btns mt-5">
					<div class="d-flex w-100 justify-content-end">
						@if(!in_array($order->currentStatus, ['READY', 'TAKEN_BY_COURIER', 'DELIVERED', 'CANCELLED']))
						<form action="{{ route('integrations.yandex_eats.orders.update', ['order' => $order]) }}" method="post">
				            @csrf
				            @method('put')
				            <input type="hidden" name="status" value="next">
				            <button type="submit" id="success_button" class="btn btn-success me-3" @if(in_array($order->currentStatus, ['TAKEN_BY_COURIER', 'DELIVERED', 'CANCELLED'])) disabled @endif>
				            	@if($order->currentStatus == 'NEW') Принять заказ @endif
				            	@if($order->currentStatus == 'ACCEPTED_BY_RESTAURANT') Начать сбор заказа @endif
				            	@if($order->currentStatus == 'COOKING') Заказ собран @endif
				            </button>
				        </form>
				        <form action="{{ route('integrations.yandex_eats.orders.update', ['order' => $order]) }}" method="post">
				            @csrf
				            @method('put')
				            <input type="hidden" name="status" value="cancelled">
				            <button type="submit" id="success_button" class="btn btn-danger">Отменить заказ</button>
				        </form>
				        @endif
				    </div>
				</div>
			</div>
		</div>
	</div>

    <div class="col-12 mt-5">
        <div class="card">
            <!--begin::Header-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <h3 class="card-title align-items-start w-100 mb-10">
                    <span class="card-label fw-bolder text-dark">Товары в заказе</span>
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
                                <th class="ps-4 min-w-125px rounded-start">ID</th>
                                <th class="min-w-125px">Наименование товара</th>
                                <th class="min-w-125px">Цена одной единицы товара</th>
                                <th class="min-w-150px">Количество товара в заказе </th>
                                <th class="min-w-150px text-end rounded-end pe-2">Исходная цена товара</th>
                            </tr>
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <a class="text-dark fw-bolder text-hover-primary d-block mb-1 fs-6">{{$item['id'] }}</a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-50px me-5"></div>
                                        <div class="d-flex justify-content-start flex-column">
                                            <a class="text-dark fw-bolder mb-1 fs-6">{{ $item['name'] ?? '-' }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fs-6 fw-bold">{{ $item['price'] }}</span>
                                </td>
                                <td>
                                    <span class="fs-6 fw-bold">{{ $item['quantity'] }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="fs-6 fw-bold">{{ $item['originPrice'] ?? '-' }}</span>
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
    </div>

    <div class="col-6 mt-5">
        <div class="card mb-xl-8">
            <!--begin::Header-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <h3 class="card-title align-items-start w-100 mb-10">
                    <span class="card-label fw-bolder text-dark">Основные данные</span>
                </h3>
            </div>
            <!--end::Header-->

            <!--begin::Body-->
            <div class="card-body py-3">
                <!--begin::Body-->
                <div class="card-body pt-0 px-0">
                    <div class="w-100">

                        <div class="mb-6 w-100">
                            <!--begin::Label-->
                            <label class="form-label">ID заказа в нашей системе</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" disabled class="form-control mb-2" placeholder="ID заказа в нашей системе" value="{{ $order->id }}">
                            <!--end::Input-->
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="mb-6 w-100">
                            <!--begin::Label-->
                            <label class="form-label">ID заказа в Яндекс</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" disabled class="form-control mb-2" placeholder="ID заказа в Яндекс" value="{{ $order->eats_id }}">
                            <!--end::Input-->
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="mb-6 w-100">
                            <!--begin::Label-->
                            <label class="form-label">Бренд</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" disabled class="form-control mb-2" placeholder="Бренд" value="{{ $order->brand }}">
                            <!--end::Input-->
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="mb-6 w-100">
                            <!--begin::Label-->
                            <label class="form-label">Комментарий</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" disabled class="form-control mb-2" placeholder="Комментарий" value="{{ $order->comment }}">
                            <!--end::Input-->
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="mb-6 w-100">
                            <!--begin::Label-->
                            <label class="form-label">Дискриминатор</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" disabled class="form-control mb-2" placeholder="Дискриминатор" value="{{ $order->discriminator }}">
                            <!--end::Input-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Дискриминатор схемы. Для заказов с доставкой курьерами Яндекс Еды равен "yandex".</div>
                            <!--end::Description-->
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="mb-6 w-100">
                            <!--begin::Label-->
                            <label class="form-label">Платформа</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" disabled class="form-control mb-2" placeholder="Платформа" value="{{ $order->platform }}">
                            <!--end::Input-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Идентификатор платформы, DC - Delivery Club, YE - Yandex Eda, LAVKA - Lavka, VSEAPTEKI - Vseapteki.</div>
                            <!--end::Description-->
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--begin::Body-->
        </div>
    </div>

    <div class="col-6 mt-5">
        <div class="card mb-xl-8">
            <!--begin::Header-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <h3 class="card-title align-items-start w-100 mb-10">
                    <span class="card-label fw-bolder text-dark">Информация о доставке</span>
                </h3>
            </div>
            <!--end::Header-->

            <!--begin::Body-->
            <div class="card-body py-3">
                <!--begin::Body-->
                <div class="card-body pt-0 px-0">
                    <div class="w-100">

                        <div class="mb-6 w-100">
                            <!--begin::Label-->
                            <label class="form-label">Дата, когда придет клиент в в торговую точку</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" disabled class="form-control mb-2" placeholder="ID заказа в нашей системе" value="{{ $order->delivery_info['courierArrivementDate'] }}">
                            <!--end::Input-->
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="mb-6 w-100">
                            <!--begin::Label-->
                            <label class="form-label">ФИО клиента</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" disabled class="form-control mb-2" placeholder="ФИО клиента" value="{{ $order->delivery_info['clientName'] ?? '-' }}">
                            <!--end::Input-->
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="mb-6 w-100">
                            <!--begin::Label-->
                            <label class="form-label">Слотовая доставка?</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" disabled class="form-control mb-2" placeholder="Слотовая доставка?" value="{{ $order->delivery_info['isSlotDelivery'] ? 'Да' : 'Нет' }}">
                            <!--end::Input-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Заказ со слотовой доставкой (доставка ко времени).</div>
                            <!--end::Description-->
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="mb-6 w-100">
                            <!--begin::Label-->
                            <label class="form-label">Номер телефона клиента</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" disabled class="form-control mb-2" placeholder="Номер телефона клиента" value="{{ $order->delivery_info['realPhoneNumber'] ?? '-' }}">
                            <!--end::Input-->
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--begin::Body-->
        </div>

        <div class="card mb-xl-8">
            <!--begin::Header-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <h3 class="card-title align-items-start w-100 mb-10">
                    <span class="card-label fw-bolder text-dark">Информация о платеже</span>
                </h3>
            </div>
            <!--end::Header-->

            <!--begin::Body-->
            <div class="card-body py-3">
                <!--begin::Body-->
                <div class="card-body pt-0 px-0">
                    <div class="w-100">

                        <div class="mb-6 w-100">
                            <!--begin::Label-->
                            <label class="form-label">Полная стоимость товаров в заказе</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" disabled class="form-control mb-2" placeholder="Полная стоимость товаров в заказе" value="{{ $order->payment_info['itemsCost'] }}">
                            <!--end::Input-->
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="mb-6 w-100">
                            <!--begin::Label-->
                            <label class="form-label">Информация о типе оплаты</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" disabled class="form-control mb-2" placeholder="Информация о типе оплаты" value="{{ $order->payment_info['paymentType'] }}">
                            <!--end::Input-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">CARD — это оплаченный заказ клиентом на стороне Яндекс Еды, CASH — это неоплаченный заказ (используется в основном при самовывозе алкоголя).</div>
                            <!--end::Description-->
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--begin::Body-->
        </div>
    </div>
</div>


@endsection

@section('scripts')

<script src="/assets/js/custom/utilities/modals/new-target.js" type="text/javascript"></script>

@endsection
