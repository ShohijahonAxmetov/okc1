<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<!--begin::Head-->

<head>
	<title>Админ панель | @yield('title', 'ACTIVE PAGE')</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="shortcut icon" href="/assets/media/okc-shortcut.png" />
	<!--begin::Fonts-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<!--end::Fonts-->
	<!--begin::Page Vendor Stylesheets(used by this page)-->
	<link href="/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
	<link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
	<!-- Include stylesheet -->
	<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
	<!--end::Page Vendor Stylesheets-->
	<!--begin::Global Stylesheets Bundle(used by all pages)-->
	<link href="/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
	<link href="/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
	<!-- multiselect -->
	<link rel="stylesheet" href="{{ asset('app/BsMultiSelect.min.css') }}">
	@yield('links')
	<link href="/assets/style.css" rel="stylesheet" type="text/css" />
	<!--end::Global Stylesheets Bundle-->

	<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
	<script>
		// CKEDITOR.config.toolbar = [
		// 	{ name: 'document', items : [ 'Undo','Redo'] },
		// ];
		// 	{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Subscript','Superscript','Format' ] },
		// CKEDITOR.config.filebrowserBrowseUrl = '/browse.php';
		// CKEDITOR.config.extraPlugins = 'uploadimage';
		CKEDITOR.config.filebrowserUploadUrl = "{{ route('upload-image', ['_token' => csrf_token()]) }}";
		CKEDITOR.config.filebrowserUploadMethod = 'form';
	</script>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
	<div class="position-fixed w-100 h-100 d-none" style="z-index: 999;" id="preloader_for_1c">
		<div class="preloader-modal d-flex align-items-center justify-content-center flex-column bg-dark position-absolute" style="width: 50%;height: 300px;z-index: 9999;top: 50%;left: 50%;transform: translate(-50%, -50%);">
			<h2 class="text-white">Данные загружается с 1с</h2>
			<small class="text-white">Это займет примерно 2-5 минут</small>
			<span id="circle1" class="svg-icon svg-icon-1 mt-5">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<path d="M14.5 20.7259C14.6 21.2259 14.2 21.826 13.7 21.926C13.2 22.026 12.6 22.0259 12.1 22.0259C9.5 22.0259 6.9 21.0259 5 19.1259C1.4 15.5259 1.09998 9.72592 4.29998 5.82592L5.70001 7.22595C3.30001 10.3259 3.59999 14.8259 6.39999 17.7259C8.19999 19.5259 10.8 20.426 13.4 19.926C13.9 19.826 14.4 20.2259 14.5 20.7259ZM18.4 16.8259L19.8 18.2259C22.9 14.3259 22.7 8.52593 19 4.92593C16.7 2.62593 13.5 1.62594 10.3 2.12594C9.79998 2.22594 9.4 2.72595 9.5 3.22595C9.6 3.72595 10.1 4.12594 10.6 4.02594C13.1 3.62594 15.7 4.42595 17.6 6.22595C20.5 9.22595 20.7 13.7259 18.4 16.8259Z" fill="currentColor"></path>
					<path opacity="0.3" d="M2 3.62592H7C7.6 3.62592 8 4.02592 8 4.62592V9.62589L2 3.62592ZM16 14.4259V19.4259C16 20.0259 16.4 20.4259 17 20.4259H22L16 14.4259Z" fill="currentColor"></path>
				</svg>
			</span>
		</div>
		<div class="preloader-background w-100 h-100 position-fixed bg-dark" style="z-index: 999;opacity: .7;">

		</div>
	</div>
	<!--begin::Main-->
	<!--begin::Root-->
	<div class="d-flex flex-column flex-root">
		<!--begin::Page-->
		<div class="page d-flex flex-row flex-column-fluid">
			<!--begin::Aside-->
			<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
				<!--begin::Brand-->
				<div class="aside-logo flex-column-auto" id="kt_aside_logo">
					<!--begin::Logo-->
					<a href="{{ route('dashboard') }}">
						<img alt="Logo" src="/assets/media/logo.svg" class="h-40px logo" />
					</a>
					<!--end::Logo-->
					<!--begin::Aside toggler-->
					<div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="aside-minimize">
						<!--begin::Svg Icon | path: icons/duotune/arrows/arr079.svg-->
						<span class="svg-icon svg-icon-1 rotate-180">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								<path opacity="0.5" d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z" fill="currentColor" />
								<path d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z" fill="currentColor" />
							</svg>
						</span>
						<!--end::Svg Icon-->
					</div>
					<!--end::Aside toggler-->
				</div>
				<!--end::Brand-->
				<!--begin::Aside menu-->
				<div class="aside-menu flex-column-fluid">
					<!--begin::Aside Menu-->
					<div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="0">
						<!--begin::Menu-->
						<div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true" data-kt-menu-expand="false">


							<div class="menu-item">
								<a class="menu-link {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}">
									<span class="menu-icon">
										<!--begin::Svg Icon | path: icons/duotune/general/gen014.svg-->
										<span class="svg-icon svg-icon-2">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
												<rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor" />
												<rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="currentColor" />
												<rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="currentColor" />
												<rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="currentColor" />
											</svg>
										</span>
										<!--end::Svg Icon-->
									</span>
									<span class="menu-title">Главная</span>
								</a>
							</div>


							<div class="menu-item">
								<div class="menu-content pt-8 pb-2">
									<span class="menu-section text-muted text-uppercase fs-8 ls-1">Главные разделы</span>
								</div>
							</div>

							<div data-kt-menu-trigger="click" class="menu-item menu-accordion show {{ Request::is('dashboard/orders/*') || Request::is('dashboard/orders') ? 'here' : '' }}">
								<span class="menu-link">
									<span class="menu-icon">
										<!--begin::Svg Icon | path: icons/duotune/communication/com013.svg-->
										<span class="svg-icon svg-icon-2">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
												<path d="M11.2929 2.70711C11.6834 2.31658 12.3166 2.31658 12.7071 2.70711L15.2929 5.29289C15.6834 5.68342 15.6834 6.31658 15.2929 6.70711L12.7071 9.29289C12.3166 9.68342 11.6834 9.68342 11.2929 9.29289L8.70711 6.70711C8.31658 6.31658 8.31658 5.68342 8.70711 5.29289L11.2929 2.70711Z" fill="currentColor" />
												<path d="M11.2929 14.7071C11.6834 14.3166 12.3166 14.3166 12.7071 14.7071L15.2929 17.2929C15.6834 17.6834 15.6834 18.3166 15.2929 18.7071L12.7071 21.2929C12.3166 21.6834 11.6834 21.6834 11.2929 21.2929L8.70711 18.7071C8.31658 18.3166 8.31658 17.6834 8.70711 17.2929L11.2929 14.7071Z" fill="currentColor" />
												<path opacity="0.3" d="M5.29289 8.70711C5.68342 8.31658 6.31658 8.31658 6.70711 8.70711L9.29289 11.2929C9.68342 11.6834 9.68342 12.3166 9.29289 12.7071L6.70711 15.2929C6.31658 15.6834 5.68342 15.6834 5.29289 15.2929L2.70711 12.7071C2.31658 12.3166 2.31658 11.6834 2.70711 11.2929L5.29289 8.70711Z" fill="currentColor" />
												<path opacity="0.3" d="M17.2929 8.70711C17.6834 8.31658 18.3166 8.31658 18.7071 8.70711L21.2929 11.2929C21.6834 11.6834 21.6834 12.3166 21.2929 12.7071L18.7071 15.2929C18.3166 15.6834 17.6834 15.6834 17.2929 15.2929L14.7071 12.7071C14.3166 12.3166 14.3166 11.6834 14.7071 11.2929L17.2929 8.70711Z" fill="currentColor" />
											</svg>
										</span>
										<!--end::Svg Icon-->
									</span>
									<span class="menu-title">Заказы</span>
									<span class="menu-arrow"></span>
								</span>
								<div class="menu-sub menu-sub-accordion menu-active-bg">
									<div class="menu-item">
										<a class="menu-link" href="{{ route('orders.index') }}">
											<span class="menu-bullet">
												<span class="bullet bullet-dot"></span>
											</span>
											<span class="menu-title">Все Заказы</span>
										</a>
									</div>
									<div class="menu-item">
										<a class="menu-link" href="{{ route('orders.new') }}">
											<span class="menu-bullet">
												<span class="bullet bullet-dot"></span>
											</span>
											<span class="menu-title">Новые заказы ({{ $orders_count['new'] }})</span>
										</a>
									</div>
									<div class="menu-item">
										<a class="menu-link" href="{{ route('orders.accepted') }}">
											<span class="menu-bullet">
												<span class="bullet bullet-dot"></span>
											</span>
											<span class="menu-title">Принятые заказы ({{ $orders_count['accepted'] }})</span>
										</a>
									</div>
									<div class="menu-item">
										<a class="menu-link" href="{{ route('orders.collected') }}">
											<span class="menu-bullet">
												<span class="bullet bullet-dot"></span>
											</span>
											<span class="menu-title">Готов к отправке ({{ $orders_count['collected'] }})</span>
										</a>
									</div>
									<div class="menu-item">
										<a class="menu-link" href="{{ route('orders.on_the_way') }}">
											<span class="menu-bullet">
												<span class="bullet bullet-dot"></span>
											</span>
											<span class="menu-title">В доставке ({{ $orders_count['on_the_way'] }})</span>
										</a>
									</div>
									<div class="menu-item">
										<a class="menu-link" href="{{ route('orders.returned') }}">
											<span class="menu-bullet">
												<span class="bullet bullet-dot"></span>
											</span>
											<span class="menu-title">Возврат ({{ $orders_count['returned'] }})</span>
										</a>
									</div>
									<div class="menu-item">
										<a class="menu-link" href="{{ route('orders.done') }}">
											<span class="menu-bullet">
												<span class="bullet bullet-dot"></span>
											</span>
											<span class="menu-title">Доставлено ({{ $orders_count['done'] }})</span>
										</a>
									</div>
									<div class="menu-item">
										<a class="menu-link" href="{{ route('orders.cancelled') }}">
											<span class="menu-bullet">
												<span class="bullet bullet-dot"></span>
											</span>
											<span class="menu-title">Отмененные заказы ({{ $orders_count['cancelled'] }})</span>
										</a>
									</div>
								</div>
							</div>

							<div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ Request::is('dashboard/brands/*') || Request::is('dashboard/brands') || Request::is('dashboard/categories/*') || Request::is('dashboard/categories') || Request::is('dashboard/products/*') || Request::is('dashboard/products') || Request::is('dashboard/attributes/*') || Request::is('dashboard/attributes') || Request::is('dashboard/discounts/*') || Request::is('dashboard/discounts') || Request::is('dashboard/remainders') ? 'here show' : '' }}">
								<span class="menu-link">
									<span class="menu-icon">
										<!--begin::Svg Icon | path: icons/duotune/communication/com013.svg-->
										<span class="svg-icon svg-icon-2">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
												<path d="M11.2929 2.70711C11.6834 2.31658 12.3166 2.31658 12.7071 2.70711L15.2929 5.29289C15.6834 5.68342 15.6834 6.31658 15.2929 6.70711L12.7071 9.29289C12.3166 9.68342 11.6834 9.68342 11.2929 9.29289L8.70711 6.70711C8.31658 6.31658 8.31658 5.68342 8.70711 5.29289L11.2929 2.70711Z" fill="currentColor" />
												<path d="M11.2929 14.7071C11.6834 14.3166 12.3166 14.3166 12.7071 14.7071L15.2929 17.2929C15.6834 17.6834 15.6834 18.3166 15.2929 18.7071L12.7071 21.2929C12.3166 21.6834 11.6834 21.6834 11.2929 21.2929L8.70711 18.7071C8.31658 18.3166 8.31658 17.6834 8.70711 17.2929L11.2929 14.7071Z" fill="currentColor" />
												<path opacity="0.3" d="M5.29289 8.70711C5.68342 8.31658 6.31658 8.31658 6.70711 8.70711L9.29289 11.2929C9.68342 11.6834 9.68342 12.3166 9.29289 12.7071L6.70711 15.2929C6.31658 15.6834 5.68342 15.6834 5.29289 15.2929L2.70711 12.7071C2.31658 12.3166 2.31658 11.6834 2.70711 11.2929L5.29289 8.70711Z" fill="currentColor" />
												<path opacity="0.3" d="M17.2929 8.70711C17.6834 8.31658 18.3166 8.31658 18.7071 8.70711L21.2929 11.2929C21.6834 11.6834 21.6834 12.3166 21.2929 12.7071L18.7071 15.2929C18.3166 15.6834 17.6834 15.6834 17.2929 15.2929L14.7071 12.7071C14.3166 12.3166 14.3166 11.6834 14.7071 11.2929L17.2929 8.70711Z" fill="currentColor" />
											</svg>
										</span>
										<!--end::Svg Icon-->
									</span>
									<span class="menu-title">Каталог</span>
									<span class="menu-arrow"></span>
								</span>
								<div class="menu-sub menu-sub-accordion menu-active-bg">
									<div class="menu-item">
										<a class="menu-link {{ Request::is('dashboard/brands/*') || Request::is('dashboard/brands') ? 'active' : '' }}" href="{{ route('brands.index') }}">
											<span class="menu-bullet">
												<span class="bullet bullet-dot"></span>
											</span>
											<span class="menu-title">Бренды</span>
										</a>
									</div>
									<div class="menu-item">
										<a class="menu-link {{ Request::is('dashboard/categories/*') || Request::is('dashboard/categories') ? 'active' : '' }}" href="{{ route('categories.index') }}">
											<span class="menu-bullet">
												<span class="bullet bullet-dot"></span>
											</span>
											<span class="menu-title">Категории</span>
										</a>
									</div>
									<div class="menu-item">
										<a class="menu-link {{ Request::is('dashboard/products/*') || Request::is('dashboard/products') ? 'active' : '' }}" href="{{ route('products.index') }}">
											<span class="menu-bullet">
												<span class="bullet bullet-dot"></span>
											</span>
											<span class="menu-title">Продукты</span>
										</a>
									</div>
									<div class="menu-item">
										<a class="menu-link {{ Request::is('dashboard/discounts/*') || Request::is('dashboard/discounts') ? 'active' : '' }}" href="{{ route('discounts.index') }}">
											<span class="menu-bullet">
												<span class="bullet bullet-dot"></span>
											</span>
											<span class="menu-title">Скидки</span>
										</a>
									</div>
									<div class="menu-item">
										<a class="menu-link {{ Request::is('dashboard/remainders/*') || Request::is('dashboard/remainders') ? 'active' : '' }}" href="{{ route('remainders.index') }}">
											<span class="menu-bullet">
												<span class="bullet bullet-dot"></span>
											</span>
											<span class="menu-title">Остатки по складам</span>
										</a>
									</div>
									<!-- <div class="menu-item">
										<a class="menu-link {{ Request::is('dashboard/attributes/*') || Request::is('dashboard/attributes') ? 'active' : '' }}" href="">
											<span class="menu-bullet">
												<span class="bullet bullet-dot"></span>
											</span>
											<span class="menu-title">Attributes</span>
										</a>
									</div> -->
								</div>
							</div>

							<div class="menu-item">
								<a class="menu-link {{ Request::is('dashboard/warehouses/*') || Request::is('dashboard/warehouses') ? 'active' : '' }}" href="{{ route('warehouses.index') }}">
									<span class="menu-icon">
										<span class="svg-icon ms-2 svg-icon-3 rotate-180">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
												<path opacity="0.3" d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z" fill="currentColor" />
												<path d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z" fill="currentColor" />
											</svg>
										</span>
									</span>
									<span class="menu-title">Склады</span>
								</a>
							</div>

							<div class="menu-item">
								<a class="menu-link {{ Request::is('dashboard/applications/*') || Request::is('dashboard/applications') ? 'active' : '' }}" href="{{ route('applications.index') }}">
									<span class="menu-icon">
										<!--begin::Svg Icon | path: /assets/media/icons/duotune/communication/com003.svg-->
										<span class="svg-icon ms-2 svg-icon-3 rotate-180">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
												<path opacity="0.3" d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z" fill="currentColor" />
												<path d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z" fill="currentColor" />
											</svg>
										</span>
										<!--end::Svg Icon-->
									</span>
									<span class="menu-title">Заявки</span>
								</a>
							</div>

							<div class="menu-item">
								<a class="menu-link {{ Request::is('dashboard/banners/*') || Request::is('dashboard/banners') ? 'active' : '' }}" href="{{ route('banners.index') }}">
									<span class="menu-icon">
										<!--begin::Svg Icon | path: icons/duotune/general/gen014.svg-->
										<!--begin::Svg Icon | path: /assets/media/icons/duotune/general/gen006.svg-->
										<span class="svg-icon ms-2 svg-icon-3 rotate-180">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
												<path opacity="0.3" d="M22 5V19C22 19.6 21.6 20 21 20H19.5L11.9 12.4C11.5 12 10.9 12 10.5 12.4L3 20C2.5 20 2 19.5 2 19V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5ZM7.5 7C6.7 7 6 7.7 6 8.5C6 9.3 6.7 10 7.5 10C8.3 10 9 9.3 9 8.5C9 7.7 8.3 7 7.5 7Z" fill="currentColor" />
												<path d="M19.1 10C18.7 9.60001 18.1 9.60001 17.7 10L10.7 17H2V19C2 19.6 2.4 20 3 20H21C21.6 20 22 19.6 22 19V12.9L19.1 10Z" fill="currentColor" />
											</svg>
										</span>
										<!--end::Svg Icon-->
										<!--end::Svg Icon-->
									</span>
									<span class="menu-title">Баннеры</span>
								</a>
							</div>

							<!-- <div class="menu-item">
								<a class="menu-link {{ Request::is('dashboard/filters/*') || Request::is('dashboard/filters') ? 'active' : '' }}" href="">
									<span class="menu-icon">
										<span class="svg-icon ms-2 svg-icon-3 rotate-180">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
												<path opacity="0.3" d="M13.625 22H9.625V3C9.625 2.4 10.025 2 10.625 2H12.625C13.225 2 13.625 2.4 13.625 3V22Z" fill="currentColor" />
												<path d="M19.625 10H12.625V4H19.625L21.025 6.09998C21.325 6.59998 21.325 7.30005 21.025 7.80005L19.625 10Z" fill="currentColor" />
												<path d="M3.62499 16H10.625V10H3.62499L2.225 12.1001C1.925 12.6001 1.925 13.3 2.225 13.8L3.62499 16Z" fill="currentColor" />
											</svg>
										</span>
									</span>
									<span class="menu-title">Filters</span>
								</a>
							</div> -->

							<div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ Request::is('dashboard/mailing/*') || Request::is('dashboard/mailing') || Request::is('dashboard/sms_mailing/*') || Request::is('dashboard/sms_mailing') ? 'here show' : '' }}">
								<span class="menu-link">
									<span class="menu-icon">
										<!--begin::Svg Icon | path: icons/duotune/communication/com013.svg-->
										<span class="svg-icon ms-2 svg-icon-3 rotate-180">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
												<path opacity="0.3" d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z" fill="currentColor"></path>
												<path d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z" fill="currentColor"></path>
											</svg>
										</span>
										<!--end::Svg Icon-->
									</span>
									<span class="menu-title">Рассылки</span>
									<span class="menu-arrow"></span>
								</span>
								<div class="menu-sub menu-sub-accordion menu-active-bg">
									<div class="menu-item">
										<a class="menu-link {{ Request::is('dashboard/mailing/*') || Request::is('dashboard/mailing') ? 'active' : '' }}" href="{{ route('mailing.index') }}">
											<span class="menu-bullet">
												<span class="bullet bullet-dot"></span>
											</span>
											<span class="menu-title">По Email</span>
										</a>
									</div>
									<div class="menu-item">
										<a class="menu-link {{ Request::is('dashboard/sms_mailing/*') || Request::is('dashboard/sms_mailing') ? 'active' : '' }}" href="{{ route('mailing.sms.index') }}">
											<span class="menu-bullet">
												<span class="bullet bullet-dot"></span>
											</span>
											<span class="menu-title">По SMS</span>
										</a>
									</div>
								</div>
							</div>

							<div class="menu-item">
								<a class="menu-link {{ Request::is('dashboard/users/*') || Request::is('dashboard/users') ? 'active' : '' }}" href="{{ route('users.index') }}">
									<span class="menu-icon">
										<!--begin::Svg Icon | path: /assets/media/icons/duotune/communication/com014.svg-->
										<span class="svg-icon ms-2 svg-icon-3 rotate-180">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
												<path d="M16.0173 9H15.3945C14.2833 9 13.263 9.61425 12.7431 10.5963L12.154 11.7091C12.0645 11.8781 12.1072 12.0868 12.2559 12.2071L12.6402 12.5183C13.2631 13.0225 13.7556 13.6691 14.0764 14.4035L14.2321 14.7601C14.2957 14.9058 14.4396 15 14.5987 15H18.6747C19.7297 15 20.4057 13.8774 19.912 12.945L18.6686 10.5963C18.1487 9.61425 17.1285 9 16.0173 9Z" fill="currentColor" />
												<rect opacity="0.3" x="14" y="4" width="4" height="4" rx="2" fill="currentColor" />
												<path d="M4.65486 14.8559C5.40389 13.1224 7.11161 12 9 12C10.8884 12 12.5961 13.1224 13.3451 14.8559L14.793 18.2067C15.3636 19.5271 14.3955 21 12.9571 21H5.04292C3.60453 21 2.63644 19.5271 3.20698 18.2067L4.65486 14.8559Z" fill="currentColor" />
												<rect opacity="0.3" x="6" y="5" width="6" height="6" rx="3" fill="currentColor" />
											</svg>
										</span>
										<!--end::Svg Icon-->
										<!--end::Svg Icon-->
									</span>
									<span class="menu-title">Клиенты</span>
								</a>
							</div>

							<div class="menu-item">
								<a class="menu-link {{ Request::is('dashboard/posts/*') || Request::is('dashboard/posts') ? 'active' : '' }}" href="{{ route('posts.index') }}">
									<span class="menu-icon">
										<!--begin::Svg Icon | path: /assets/media/icons/duotune/communication/com003.svg-->
										<span class="svg-icon ms-2 svg-icon-3 rotate-180">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
												<path opacity="0.3" d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z" fill="currentColor" />
												<path d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z" fill="currentColor" />
											</svg>
										</span>
										<!--end::Svg Icon-->
									</span>
									<span class="menu-title">Блог</span>
								</a>
							</div>

							<div class="menu-item">
								<a class="menu-link {{ Request::is('dashboard/comments/*') || Request::is('dashboard/comments') ? 'active' : '' }}" href="{{ route('comments.index') }}">
									<span class="menu-icon">
										<!--begin::Svg Icon | path: /assets/media/icons/duotune/communication/com003.svg-->
										<span class="svg-icon ms-2 svg-icon-3 rotate-180">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
												<path opacity="0.3" d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z" fill="currentColor" />
												<path d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z" fill="currentColor" />
											</svg>
										</span>
										<!--end::Svg Icon-->
									</span>
									<span class="menu-title">Комментарии</span>
								</a>
							</div>


							<div class="menu-item">
								<div class="menu-content">
									<div class="separator mx-1 my-4"></div>
								</div>
							</div>

							@if(auth()->user()->role == 'admin')
							<div class="menu-item">
								<a class="menu-link {{ Request::is('dashboard/admins/*') || Request::is('dashboard/admins') ? 'active' : '' }}" href="{{ route('admins.index') }}">
									<span class="menu-icon">
										<span class="svg-icon ms-2 svg-icon-3 rotate-180">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
												<path d="M16.0173 9H15.3945C14.2833 9 13.263 9.61425 12.7431 10.5963L12.154 11.7091C12.0645 11.8781 12.1072 12.0868 12.2559 12.2071L12.6402 12.5183C13.2631 13.0225 13.7556 13.6691 14.0764 14.4035L14.2321 14.7601C14.2957 14.9058 14.4396 15 14.5987 15H18.6747C19.7297 15 20.4057 13.8774 19.912 12.945L18.6686 10.5963C18.1487 9.61425 17.1285 9 16.0173 9Z" fill="currentColor" />
												<rect opacity="0.3" x="14" y="4" width="4" height="4" rx="2" fill="currentColor" />
												<path d="M4.65486 14.8559C5.40389 13.1224 7.11161 12 9 12C10.8884 12 12.5961 13.1224 13.3451 14.8559L14.793 18.2067C15.3636 19.5271 14.3955 21 12.9571 21H5.04292C3.60453 21 2.63644 19.5271 3.20698 18.2067L4.65486 14.8559Z" fill="currentColor" />
												<rect opacity="0.3" x="6" y="5" width="6" height="6" rx="3" fill="currentColor" />
											</svg>
										</span>
									</span>
									<span class="menu-title">Контент-менеджеры</span>
								</a>
							</div>
							<div class="menu-item">
								<a class="menu-link {{ Request::is('dashboard/logs/*') || Request::is('dashboard/logs') ? 'active' : '' }}" href="{{ route('logs.index') }}">
									<span class="menu-icon">
										<span class="svg-icon svg-icon-muted">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
												<path opacity="0.3" d="M18 22C19.7 22 21 20.7 21 19C21 18.5 20.9 18.1 20.7 17.7L15.3 6.30005C15.1 5.90005 15 5.5 15 5C15 3.3 16.3 2 18 2H6C4.3 2 3 3.3 3 5C3 5.5 3.1 5.90005 3.3 6.30005L8.7 17.7C8.9 18.1 9 18.5 9 19C9 20.7 7.7 22 6 22H18Z" fill="currentColor" />
												<path d="M18 2C19.7 2 21 3.3 21 5H9C9 3.3 7.7 2 6 2H18Z" fill="currentColor" />
												<path d="M9 19C9 20.7 7.7 22 6 22C4.3 22 3 20.7 3 19H9Z" fill="currentColor" />
											</svg>
										</span>
									</span>
									<span class="menu-title">Логи</span>
								</a>
							</div>
							@endif


						</div>
						<!--end::Menu-->
					</div>
					<!--end::Aside Menu-->
				</div>
				<!--end::Aside menu-->
				<div class="aside-footer flex-column-auto pt-5 pb-7 px-5" id="kt_aside_footer">
					<a onclick="clickBtn()" href="{{ route('upload_datas') }}" id="download_updates" class="btn btn-custom btn-primary w-100" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click" title="" data-bs-original-title="Загружать обновления с серверов 1с">
						<!--begin::Svg Icon | path: icons/duotune/general/gen005.svg-->
						<span id="circle" class="svg-icon svg-icon-2">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								<path d="M14.5 20.7259C14.6 21.2259 14.2 21.826 13.7 21.926C13.2 22.026 12.6 22.0259 12.1 22.0259C9.5 22.0259 6.9 21.0259 5 19.1259C1.4 15.5259 1.09998 9.72592 4.29998 5.82592L5.70001 7.22595C3.30001 10.3259 3.59999 14.8259 6.39999 17.7259C8.19999 19.5259 10.8 20.426 13.4 19.926C13.9 19.826 14.4 20.2259 14.5 20.7259ZM18.4 16.8259L19.8 18.2259C22.9 14.3259 22.7 8.52593 19 4.92593C16.7 2.62593 13.5 1.62594 10.3 2.12594C9.79998 2.22594 9.4 2.72595 9.5 3.22595C9.6 3.72595 10.1 4.12594 10.6 4.02594C13.1 3.62594 15.7 4.42595 17.6 6.22595C20.5 9.22595 20.7 13.7259 18.4 16.8259Z" fill="currentColor"></path>
								<path opacity="0.3" d="M2 3.62592H7C7.6 3.62592 8 4.02592 8 4.62592V9.62589L2 3.62592ZM16 14.4259V19.4259C16 20.0259 16.4 20.4259 17 20.4259H22L16 14.4259Z" fill="currentColor"></path>
							</svg>
						</span>
						<!--end::Svg Icon-->
						<span id="btn-txt" class="btn-label">Скачать обновления</span>
					</a>
				</div>
			</div>
			<!--end::Aside-->
			<!--begin::Wrapper-->
			<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
				<!--begin::Header-->
				<div id="kt_header" style="" class="header align-items-stretch">
					<!--begin::Container-->
					<div class="container-fluid d-flex align-items-stretch justify-content-between">
						<!--begin::Aside mobile toggle-->
						<div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show aside menu">
							<div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px" id="kt_aside_mobile_toggle">
								<!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
								<span class="svg-icon svg-icon-1">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
										<path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="currentColor" />
										<path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="currentColor" />
									</svg>
								</span>
								<!--end::Svg Icon-->
							</div>
						</div>
						<!--end::Aside mobile toggle-->
						<!--begin::Mobile logo-->
						<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
							<a href="{{ route('dashboard') }}" class="d-lg-none">
								<img alt="Logo" src="/assets/media/okc-shortcut.png" class="h-30px ps-1" />
							</a>
						</div>
						<!--end::Mobile logo-->
						<!--begin::Wrapper-->
						<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
							<!--begin::Navbar-->
							<div class="d-flex align-items-stretch" id="kt_header_nav">
							</div>
							<!--end::Navbar-->
							<!--begin::Toolbar wrapper-->
							<div class="d-flex align-items-stretch flex-shrink-0">
								<!--begin::Theme mode-->
								<div class="d-flex align-items-center ms-1 ms-lg-3">
									<!--begin::Theme mode docs-->
									<!-- <a class="btn btn-icon btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px">
										<i class="fonticon-sun fs-2"></i>
									</a> -->
									<!--end::Theme mode docs-->
								</div>
								<!--end::Theme mode-->
								<!--begin::User menu-->
								<div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
									<!--begin::Menu wrapper-->
									<div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
										<img src="{{ isset(auth()->user()->img) ? auth()->user()->img : '/assets/media/avatars/300-1.jpg' }}" alt="user" />
									</div>
									<!--begin::User account menu-->
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px" data-kt-menu="true">
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<div class="menu-content d-flex align-items-center px-3">
												<!--begin::Avatar-->
												<div class="symbol symbol-50px me-5">
													<img alt="Logo" src="{{ isset(auth()->user()->img) ? auth()->user()->img : '/assets/media/avatars/300-1.jpg' }}" />
												</div>
												<!--end::Avatar-->
												<!--begin::Username-->
												<div class="d-flex flex-column">
													<div class="fw-bolder d-flex align-items-center fs-5">{{ auth()->user()->name }}
														<span class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2">{{ auth()->user()->role }}</span>
													</div>
													<a class="fw-bold text-muted text-hover-primary fs-7">{{ auth()->user()->username }}</a>
												</div>
												<!--end::Username-->
											</div>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu separator-->
										<!-- <div class="separator my-2"></div> -->
										<!--end::Menu separator-->
										<!--begin::Menu item-->
										<!-- <div class="menu-item px-5">
											<a class="menu-link px-5">Профиль</a>
										</div> -->
										<!--end::Menu item-->
										<!--begin::Menu separator-->
										<div class="separator my-2"></div>
										<!--end::Menu separator-->
										<!--begin::Menu item-->
										<div class="menu-item px-5 my-1">
											<a href="{{ route('profile.edit') }}" class="menu-link px-5">Настройки аккаунта</a>
										</div>
										<!--end::Menu item-->
										<div class="separator my-2"></div>
										<!--begin::Menu item-->
										<div class="menu-item px-5">
											<form action="{{ route('auth.logout') }}" method="post">
												@csrf
												<button class="menu-link px-5 border-0 bg-transparent">Выйти</button>
											</form>
										</div>
										<!--end::Menu item-->
									</div>
									<!--end::User account menu-->
									<!--end::Menu wrapper-->
								</div>
								<!--end::User menu-->
								<!--begin::Header menu toggle-->
								<!-- <div class="d-flex align-items-center d-lg-none ms-2 me-n3" title="Show header menu">
									<div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px" id="kt_header_menu_mobile_toggle">
										<span class="svg-icon svg-icon-1">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
												<path d="M13 11H3C2.4 11 2 10.6 2 10V9C2 8.4 2.4 8 3 8H13C13.6 8 14 8.4 14 9V10C14 10.6 13.6 11 13 11ZM22 5V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4V5C2 5.6 2.4 6 3 6H21C21.6 6 22 5.6 22 5Z" fill="currentColor" />
												<path opacity="0.3" d="M21 16H3C2.4 16 2 15.6 2 15V14C2 13.4 2.4 13 3 13H21C21.6 13 22 13.4 22 14V15C22 15.6 21.6 16 21 16ZM14 20V19C14 18.4 13.6 18 13 18H3C2.4 18 2 18.4 2 19V20C2 20.6 2.4 21 3 21H13C13.6 21 14 20.6 14 20Z" fill="currentColor" />
											</svg>
										</span>
									</div>
								</div> -->
								<!--end::Header menu toggle-->
							</div>
							<!--end::Toolbar wrapper-->
						</div>
						<!--end::Wrapper-->
					</div>
					<!--end::Container-->
				</div>
				<!--end::Header-->
				<!--begin::Content-->
				<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
					<!--begin::Toolbar-->
					<div class="toolbar" id="kt_toolbar">
						<!--begin::Container-->
						<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
							<!--begin::Page title-->
							<div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
								<!--begin::Title-->
								<h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">@yield('title', 'Главная')</h1>
								<!--end::Title-->
								<!--begin::Separator-->
								<span class="h-20px border-gray-300 border-start mx-4"></span>
								<!--end::Separator-->
								<!--begin::Breadcrumb-->
								@yield('breadcrumb')
								<!--end::Breadcrumb-->
							</div>
							<!--end::Page title-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Toolbar-->


					<div class="post d-flex flex-column-fluid" id="kt_post">
						<!--begin::Container-->
						<div id="kt_content_container" class="container-xxl">

							@yield('content')

						</div>
						<!--end::Container-->
					</div>


				</div>
				<!--end::Content-->
			</div>
			<!--end::Wrapper-->
		</div>
		<!--end::Page-->
	</div>
	<!--end::Root-->
	<!--end::Main-->
	<!--begin::Engage drawers-->
	<!--begin::Demos drawer-->
	<div id="kt_engage_demos" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="explore" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'350px', 'lg': '475px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_engage_demos_toggle" data-kt-drawer-close="#kt_engage_demos_close">
		<!--begin::Card-->
		<div class="card shadow-none rounded-0 w-100">
			<!--begin::Header-->
			<div class="card-header" id="kt_engage_demos_header">
				<h3 class="card-title fw-bolder text-gray-700">Исследовать</h3>
				<div class="card-toolbar">
					<button type="button" class="btn btn-sm btn-icon btn-active-color-primary h-40px w-40px me-n6" id="kt_engage_demos_close">
						<!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
						<span class="svg-icon svg-icon-2">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
								<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
							</svg>
						</span>
						<!--end::Svg Icon-->
					</button>
				</div>
			</div>
			<!--end::Header-->
		</div>
		<!--end::Card-->
	</div>
	<!--end::Demos drawer-->
	<!--end::Engage drawers-->
	<!--begin::Scrolltop-->
	<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
		<!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
		<span class="svg-icon">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
				<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
				<path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor" />
			</svg>
		</span>
		<!--end::Svg Icon-->
	</div>
	<!--end::Scrolltop-->
	<!--begin::Modals-->
	<!--end::Modals-->
	<!--begin::Javascript-->
	<script>
		var hostUrl = "/assets/";
	</script>
	<!--begin::Global Javascript Bundle(used by all pages)-->
	<script src="/assets/plugins/global/plugins.bundle.js"></script>
	<script src="/assets/js/scripts.bundle.js"></script>
	<!--end::Global Javascript Bundle-->
	<!--begin::Page Vendors Javascript(used by this page)-->
	<script src="/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
	<script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
	<!--end::Page Vendors Javascript-->
	<!-- multiselect -->
	<script type="text/javascript" src="{{ asset('app/BsMultiSelect.min.js') }}"></script>
	<!-- notyf -->
	<script type="text/javascript" src="{{ asset('app/notify.js') }}"></script>
	@if(isset($_GET['success']) && $_GET['success'] == 'true' || session('success') && session('success') == true)
	<script>
		$.notify("Success!", 'success');
	</script>
	@endif
	@if(session()->has('success') && session('success') == false)
	<script>
		$.notify("{{ session()->get('message') }}", 'error');
	</script>
	@endif
	<!-- Include the Quill library -->
	<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
	<!--begin::Page Custom Javascript(used by this page)-->
	<script src="/assets/js/widgets.bundle.js"></script>
	<script src="/assets/js/custom/widgets.js"></script>
	<script src="/assets/js/custom/apps/chat/chat.js"></script>
	<script src="/assets/js/custom/utilities/modals/upgrade-plan.js"></script>
	<script src="/assets/js/custom/utilities/modals/create-app.js"></script>
	<script src="/assets/js/custom/utilities/modals/users-search.js"></script>
	<!--end::Page Custom Javascript-->
	<!--end::Javascript-->
	<script src="{{ asset('assets/script.js') }}"></script>
	@yield('scripts')
</body>
<!--end::Body-->

</html>