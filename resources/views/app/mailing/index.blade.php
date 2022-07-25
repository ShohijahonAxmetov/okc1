@extends('layouts.app')

@section('title', 'ПОЧТОВЫЕ ОТПРАВЛЕНИЯ')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'Главная',
'route' => 'dashboard'
],
[
'title' => 'Почтовые отправления'
]
]
])

@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
    <!--begin::Body-->
    <div class="card-body py-3">
        <form action="{{ route('mailing.send') }}" method="post">
            @csrf
            <div class="container">
                <div class="my-10">
                    <label for="subject" class="required form-label">Тема</label>
                    <input name="subject" id="subject" class="form-control form-control-solid" required placeholder="Тема">
                </div>
                <div class="my-10">
                    <label for="message" class="required form-label">Сообщение</label>
                    <textarea name="message" id="message" cols="30" rows="10" required class="form-control form-control-solid" placeholder="Введите текст"></textarea>
                </div>
                <div class="fv-row mb-4">
                    <label for="users" class="fs-6 fw-bold mb-2">Пользователи</label>
                    <select class="form-select" multiple name="users[]" data-control="select2" data-hide-search="false">
                        @foreach($users as $user)
                        <option value="{{ $user->email }}">{{ $user->email }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex align-items-center mb-8">
                    <!--begin::Option-->
                    <label class="form-check-solid me-5  fw-bold">Отправить всем пользователям
                        <input class="form-check-input" name="all_users" type="checkbox" value="1">
                    </label>
                    <!--end::Options-->
                </div>
            </div>
            <div class="d-flex justify-content-end mt-8">
                <button class="btn btn-success me-2 mb-2 px-8">Отправить</button>
            </div>
        </form>

    </div>
    <!--begin::Body-->
</div>

@endsection
