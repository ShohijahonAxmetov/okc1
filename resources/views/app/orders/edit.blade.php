@extends('layouts.app')

@section('title', 'ЗАКАЗЫ')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'Главная',
'route' => 'dashboard'
],
[
'title' => 'Заказы',
'route' => 'orders.index'
],
[
'title' => '#'.$order->id,
]
]
])

@endsection

@section('content')

<!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
	<!--begin::Container-->
	<div id="kt_content_container" class="container-xxl">
		<!--begin::Form-->
		<form action="{{ route('orders.update', ['id' => $order->id]) }}" method="post" id="kt_ecommerce_edit_order_form" class="form d-flex flex-column flex-lg-row">
			@csrf
			<!--begin::Aside column-->
			<div class="w-100 flex-lg-row-auto w-lg-300px mb-7 me-7 me-lg-10">
				<!--begin::Order details-->
				<div class="card card-flush py-4">
					<!--begin::Card header-->
					<div class="card-header">
						<div class="card-title">
							<h2>Информация о заказе</h2>
						</div>
					</div>
					<!--end::Card header-->
					<!--begin::Card body-->
					<div class="card-body pt-0">
						<div class="d-flex flex-column gap-10">
							<!--begin::Input group-->
							<div class="fv-row">
								<!--begin::Label-->
								<label class="form-label">Заказ ID</label>
								<!--end::Label-->
								<!--begin::Auto-generated ID-->
								<div class="fw-bolder fs-3">#{{ $order->id }}</div>
								<!--end::Input-->
							</div>
							<!--end::Input group-->
							<!--begin::Input group-->
							<div class="fv-row">
								<!--begin::Label-->
								<label class="form-label d-flex align-items-center justify-content-between">
									Status
									@switch($order->status)
									@case('new')
									<span class="badge fs-7 fw-bold text-uppercase rounded-circle d-flex" style="background-color: rgb(13,202,240);width: 20px;height:20px"></span>
									@break
									@case('collected')
									<span class="badge fs-7 fw-bold text-uppercase rounded-circle d-flex" style="background-color: rgb(13,110,253);width: 20px;height:20px"></span>
									@break
									@case('on_the_way')
									<span class="badge fs-7 fw-bold text-uppercase rounded-circle d-flex" style="background-color: rgb(225,193,7);width: 20px;height:20px"></span>
									@break
									@case('returned')
									<span class="badge fs-7 fw-bold text-uppercase rounded-circle d-flex" style="background-color: #ff39f9;width: 20px;height:20px"></span>
									@break
									@case('done')
									<span class="badge fs-7 fw-bold text-uppercase rounded-circle d-flex" style="background-color: rgb(25,135,84);width: 20px;height:20px"></span>
									@break
									@case('cancelled')
									<span class="badge fs-7 fw-bold text-uppercase rounded-circle d-flex" style="background-color: rgb(220,53,69);width: 20px;height:20px"></span>
									@break
									@endswitch
								</label>
								<!--end::Label-->
								<!--begin::Select2-->
								<select class="form-select mb-2 text-uppercase" data-hide-search="true" data-control="select2" data-control="" name="status" id="status">
									<option value="new" {{ $order->status == 'new' ? 'selected' : '' }}>Новый</option>
									<option value="collected" {{ $order->status == 'collected' ? 'selected' : '' }}>Собрано</option>
									<option value="on_the_way" {{ $order->status == 'on_the_way' ? 'selected' : '' }}>В пути</option>
									<option value="returned" {{ $order->status == 'returned' ? 'selected' : '' }}>Вернулся</option>
									<option value="done" {{ $order->status == 'done' ? 'selected' : '' }}>Успешно</option>
									<option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Отменено</option>
								</select>
								<!--end::Select2-->
							</div>
							<!--end::Input group-->
							<!--begin::Input group-->
							@if($order->is_payed == 0)
							<div class="fv-row">
								<!--begin::Label-->
								<label class="required form-label">Метод оплаты</label>
								<!--end::Label-->
								<!--begin::Select2-->
								<select class="form-select mb-2" data-control="select2" data-hide-search="true" name="payment_method" id="kt_ecommerce_edit_order_payment">
									<option value="cash" {{ $order->payment_method == 'cash' ? 'selected' : '' }}>Наличные</option>
									<option value="online" {{ $order->payment_method == 'online' ? 'selected' : '' }}>Онлайн оплата</option>
								</select>
								<!--end::Select2-->
							</div>
							@endif
							<!--end::Input group-->
							<!--begin::Input group-->
							<div class="fv-row">
								<!--begin::Label-->
								<label class="required form-label">Способ доставки</label>
								<!--end::Label-->
								<!--begin::Select2-->
								<select class="form-select mb-2" data-control="select2" data-hide-search="true" name="delivery_method" id="kt_ecommerce_edit_order_shipping">
									<option value="" selected>Из офиса</option>
									<option value="bts" {{ $order->delivery_method == 'bts' ? 'selected' : '' }}>BTS</option>
									<option value="delivery" {{ $order->delivery_method == 'delivery' ? 'selected' : '' }}>Простая доставка</option>
								</select>
								<!--end::Select2-->
							</div>
							<!--end::Input group-->
							<!--begin::Input group-->
							<div class="fv-row">
								<!--begin::Label-->
								<label class="required form-label">Дата заказа</label>
								<!--end::Label-->
								<!--begin::Editor-->
								<input id="kt_ecommerce_edit_order_date" disabled placeholder="Select a date" class="form-control mb-2" value="{{ date('Y-m-d', strtotime($order->created_at)) }}" />
								<!--end::Editor-->
							</div>
							<!--end::Input group-->
						</div>
					</div>
					<!--end::Card header-->
				</div>
				<!--end::Order details-->
			</div>
			<!--end::Aside column-->
			<!--begin::Main column-->
			<div class="d-flex flex-column flex-lg-row-fluid gap-7 gap-lg-10">
				<!--begin::Order details-->
				<div class="card card-flush py-4">
					<!--begin::Card header-->
					<div class="card-header">
						<div class="card-title">
							<h2>Подробности доставки</h2>
						</div>
					</div>
					<!--end::Card header-->
					<!--begin::Card body-->
					<div class="card-body pt-0">
						<!--begin::Billing address-->
						<div class="d-flex flex-column gap-5 gap-md-7">
							<!--begin::Shipping address-->
							<div class="d-flex flex-column gap-5 gap-md-7" id="kt_ecommerce_edit_order_shipping_form">
								<!--begin::Title-->
								<div class="fs-3 fw-bolder mb-n2">Адрес доставки</div>
								<!--end::Title-->
								<!--begin::Input group-->
								<div class="d-flex flex-column flex-md-row gap-5">
									<div class="flex-row-fluid">
										<!--begin::Label-->
										<label class="form-label">Область</label>
										<!--end::Label-->
										<!--begin::Select2-->
										<select class="form-select mb-2" data-control="" data-hide-search="false" name="region" id="region">
											<option value="">Выбрать из списка</option>
											@foreach(config('app.REGIONS') as $item)
											<option value="{{ $item['value'] }}" {{ $order->region == $item['value'] ? 'selected' : '' }}>{{ $item['uz'] }}</option>
											@endforeach
										</select>
										<!--end::Select2-->
									</div>
									<div class="fv-row flex-row-fluid">
										<!--begin::Label-->
										<label class="form-label">Район</label>
										<!--end::Label-->
										<!--begin::Select2-->
										<select class="form-select mb-2" data-control="" data-hide-search="false" name="district" id="district">
											<option value="">Выбрать из списка</option>
											@foreach(config('app.DISTRICTS') as $item)
											<option value="{{ $item['value'] }}">{{ $item['title'] }}</option>
											@endforeach
										</select>
										<!--end::Select2-->
									</div>
									<div class="fv-row flex-row-fluid">
										<!--begin::Label-->
										<label class="form-label">Почтовый Код</label>
										<!--end::Label-->
										<!--begin::Input-->
										<input class="form-control" name="postal_code" placeholder="postal code" value="{{ old('postal_code') ?? $order->postal_code }}" />
										<!--end::Input-->
									</div>
								</div>
								<!--end::Input group-->
								<!--begin::Input group-->
								<div class="fv-row">
									<!--begin::Label-->
									<label class="form-label">Полный адрес</label>
									<!--end::Label-->
									<textarea name="address" id="" cols="30" rows="6" class="form-control form">{{ old('address') ?? $order->address }}</textarea>
								</div>
								<!--end::Input group-->
							</div>
							<!--end::Shipping address-->
						</div>
						<!--end::Billing address-->
					</div>
					<!--end::Card body-->
				</div>
				<!--end::Order details-->
				<div class="d-flex justify-content-end">
					<!--begin::Button-->
					<a href="{{ route('orders.show', ['id' => $order->id]) }}" id="kt_ecommerce_edit_order_cancel" class="btn btn-light me-5">Отмена</a>
					<!--end::Button-->
					<!--begin::Button-->
					<button type="submit" id="kt_ecommerce_edit_order_submit" class="btn btn-primary">
						<span class="indicator-label">Сохранить изменения</span>
						<span class="indicator-progress">Пожалуйста подождите...
							<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
					</button>
					<!--end::Button-->
				</div>
			</div>
			<!--end::Main column-->
		</form>
		<!--end::Form-->
	</div>
	<!--end::Container-->
</div>
<!--end::Post-->

@endsection

@section('scripts')

<script>
	let region_select = document.querySelector('[name="region"]');
	console.log('old value = ' + region_select.value);
	if (region_select.value) {
		let region_id = region_select.value;
		console.log(region_id);

		let params = new FormData();
		params.set('region_id', region_id);

		fetch('{{url("")}}/dashboard/get_regions_districts', {
				method: 'post',
				body: params,
				headers: {
					'X-CSRF-TOKEN': document.querySelector('[name="csrf-token"]').getAttribute('content'),
				}
			})
			.then((response) => {
				response.json().then(text => {

					if (text.success) {
						console.log(text.districts);

						let options = text.districts.map(district => {
							let option = document.createElement('option');
							option.textContent = district.title;
							option.setAttribute('value', district.value);
							if('{{ $order->district }}' == district.value) {
								option.setAttribute('selected', 'selected');
							}
							return option;
						});

						let district_select = document.querySelector('[name="district"]');
						district_select.innerHTML = '<option value="">Выбрать из списка</option>';
						district_select.append(...options);

					} else {
						console.log('error');
					}

				}).catch((error) => {

					console.log(error);

				});
			});
	}

	region_select.addEventListener('change', () => {

		let region_id = region_select.value;
		console.log(region_id);

		let params = new FormData();
		params.set('region_id', region_id);

		fetch('{{url("")}}/dashboard/get_regions_districts', {
				method: 'post',
				body: params,
				headers: {
					'X-CSRF-TOKEN': document.querySelector('[name="csrf-token"]').getAttribute('content'),
				}
			})
			.then((response) => {
				response.json().then(text => {

					if (text.success) {
						console.log(text.districts);

						let options = text.districts.map(district => {
							let option = document.createElement('option');
							option.textContent = district.title;
							option.setAttribute('value', district.value);
							return option;
						});

						let district_select = document.querySelector('[name="district"]');
						district_select.innerHTML = '<option value="">Выбрать из списка</option>';
						district_select.append(...options);

					} else {
						console.log('error');
					}

				}).catch((error) => {

					console.log(error);

				});
			});
	});
</script>

@endsection
