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
'title' => 'Заказы'
]
]
])

@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">Заказы</span>
            <!-- <span class="text-muted mt-1 fw-bold fs-7">Last 12 orders</span> -->
        </h3>
        <div class="card-toolbar">
            <form action="{{ route('orders.index') }}" class="d-flex align-items-center">
                <div class="d-flex align-items-center position-relative my-1">
                    <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                    <span class="svg-icon svg-icon-1 position-absolute ms-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"></path>
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                    <input type="text" name="search" class="form-control form-control-solid w-250px ps-14" placeholder="Введите заказ ID..." value="{{ $search }}">
                </div>
                <button class="btn btn-success ms-2" style="height: min-content;">Поиск</button>
            </form>
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
                        <th class="ps-4 min-w-75px rounded-start">Заказ ID</th>
                        <th class="min-w-200">Клиент</th>
                        <th class="min-w-125px">Дата добавления</th>
                        <th class="min-w-125px">Дата изменения</th>
                        <th class="min-w-125px">Общяя(сумма)</th>
                        <th class="min-w-125px">Статус</th>
                        <th class="min-w-125px text-end rounded-end">Действия</th>
                    </tr>
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <!-- <div class="symbol symbol-50px me-5">
                                    <img src="/assets/media/stock/600x400/img-26.jpg" class="" alt="">
                                </div> -->
                                <div class="d-flex justify-content-start flex-column ps-4">
                                    <a href="#" class="text-dark fw-bolder text-hover-primary mb-1 fs-6">#{{ $order->id }}</a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-5">
                                    <img src="/assets/media/default.png" class="" alt="">
                                </div>
                                <div class="d-flex justify-content-start flex-column">
                                    <a href="#" class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{ isset($order->user) ? $order->user->name : 'Клиент удален' }}</a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="text-dark fw-bolder text-hover-primary d-block mb-1 fs-6">{{ date('H:i d.m.Y', strtotime($order->created_at)) }}</a>
                        </td>
                        <td>
                            <a href="#" class="text-dark fw-bolder text-hover-primary d-block mb-1 fs-6">{{ date('H:i d.m.Y', strtotime($order->updated_at)) }}</a>
                        </td>
                        <td>
                            <a class="text-dark fw-bolder text-hover-primary d-block mb-1 fs-6">{{ $order->amount / 100 }}</a>
                        </td>
                        <td>
                            @switch($order->status)
                            @case('new')
                            <span class="badge fs-7 fw-bold text-uppercase" style="background-color: rgb(13,202,240);">{{ $order->status }}</span>
                            @break
                            @case('collected')
                            <span class="badge fs-7 fw-bold text-uppercase" style="background-color: rgb(13,110,253);">{{ $order->status }}</span>
                            @break
                            @case('on_the_way')
                            <span class="badge fs-7 fw-bold text-uppercase" style="background-color: rgb(225,193,7);">{{ $order->status }}</span>
                            @break
                            @case('returned')
                            <span class="badge fs-7 fw-bold text-uppercase" style="background-color: #ff39f9;">{{ $order->status }}</span>
                            @break
                            @case('done')
                            <span class="badge fs-7 fw-bold text-uppercase" style="background-color: rgb(25,135,84);">{{ $order->status }}</span>
                            @break
                            @case('cancelled')
                            <span class="badge fs-7 fw-bold text-uppercase" style="background-color: rgb(220,53,69);">{{ $order->status }}</span>
                            @break
                            @endswitch
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end">
                                <form action="{{ route('orders.destroy', ['id' => $order->id]) }}" method="post">
                                    @csrf
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
                                <a href="{{ route('orders.show', ['id' => $order->id]) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 ms-4">
                                    <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                            <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
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

{!! $orders->links() !!}

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

@endsection
