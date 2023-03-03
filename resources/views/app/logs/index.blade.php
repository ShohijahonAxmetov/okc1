@extends('layouts.app')

@section('title', 'Логи')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'Главная',
'route' => 'dashboard'
],
[
'title' => 'Логи'
]
]
])

@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">Логи</span>
            <!-- <span class="text-muted mt-1 fw-bold fs-7">Last 12 products</span> -->
        </h3>
        <div class="card-toolbar">
            <button type="button" id="clear_btn" class="d-flex justify-content-center align-items-center btn rounded-circle me-2 p-0" style="width: 28px;height: 28px">
                <span class="svg-icon svg-icon-muted svg-icon-2hx">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                        <rect x="7" y="15.3137" width="12" height="2" rx="1" transform="rotate(-45 7 15.3137)" fill="currentColor" />
                        <rect x="8.41422" y="7" width="12" height="2" rx="1" transform="rotate(45 8.41422 7)" fill="currentColor" />
                    </svg>
                </span>
            </button>
            <form action="{{ route('logs.index') }}" class="d-flex align-items-center">
                <div class="d-flex align-items-center position-relative my-1">
                    <select class="form-control form-select form-control-solid w-150px" name="user_id" data-control="select2" data-hide-search="false">
                        <option value="">Выберите модератора</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
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
                        <th class="ps-4 min-w-75px rounded-start">ID</th>
                        <th class="min-w-200px">Пользователь</th>
                        <th class="min-w-125px">Действия</th>
                        <th class="min-w-125px">Модель</th>
                        <th class="min-w-125px">Запись</th>
                        <th class="min-w-125px">Время</th>
                        <th class="min-w-200px text-end rounded-end pe-4">Действия</th>
                    </tr>
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td class="ps-4">#{{$log->id}}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-5">

                                    <img src="{{ isset($log->admin->img_url) ? $log->admin->img_url : '/assets/media/default.png' }}" class="" alt="" style="object-fit:cover">
                                </div>
                                <div class="d-flex justify-content-start flex-column">
                                    <a class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{ $log->admin->name ?? '--' }}</a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a class="text-dark text-hover-primary d-block mb-1 fs-6">
                                @if($log->action == 'created')
                                создал
                                @elseif($log->action == 'updated')
                                редактировал
                                @elseif($log->action == 'deleted')
                                удалил
                                @endif
                            </a>
                        </td>
                        <td>
                            <a class="text-dark text-hover-primary d-block mb-1 fs-6">
                                @switch($log->model)
                                    @case('Product')
                                        Продукт
                                        @break
                                    @case('Post')
                                        Пост
                                        @break
                                    @case('Comment')
                                        Отзыв
                                        @break
                                    @case('Order')
                                        Заказ
                                        @break
                                    @case('Application')
                                        Запрос на оптом
                                        @break
                                    @case('Banner')
                                        Баннер
                                        @break
                                    @case('Category')
                                        Категория
                                        @break
                                    @case('Product')
                                        Продукт
                                        @break
                                    @default
                                        {{$log->model ?? '--'}}
                                @endswitch
                            </a>
                        </td>
                        <td>
                            <a class="text-dar text-hover-primary d-block mb-1 fs-6">{{ $log->item_id }}</a>
                        </td>
                        <td class="">{{date('H:i d/m/Y', strtotime($log->created_at))}}</td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end">
                                <form action="{{ route('logs.destroy', ['id' => $log->id]) }}" method="post">
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

{!! $logs->links() !!}

@endsection

@section('scripts')

<script>
    function confirmation(item) {
        Swal.fire({
            title: 'Вы уверены?',
            text: "Вы не сможете отменить это!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Да, удалить!'
        }).then((result) => {
            if (result.value) {
                item.parentNode.submit();
            }
        });
    }

    document.getElementById('clear_btn').addEventListener('click', function() {
        document.getElementsByClassName('select2-selection__rendered').forEach((element, index) => {
            console.log(element);
            if (index == 0) {
                element.innerText = 'Выберите модератора';
            }
        });
        document.querySelector("[name='user_id']").value = '';
    });
</script>

@endsection