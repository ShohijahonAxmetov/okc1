@extends('layouts.app')

@section('title', 'SMS рассылка')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'Главная',
'route' => 'dashboard'
],
[
'title' => 'SMS рассылка'
]
]
])

@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
    <!--begin::Body-->
    <div class="card-body py-3">
        <form action="{{ route('mailing.sms.send') }}" method="post">
            @csrf
            <div class="container">
                <!-- <div class="my-10">
                    <label for="subject" class="required form-label">Тема</label>
                    <input name="subject" id="subject" class="form-control form-control-solid" required placeholder="Тема">
                </div> -->
                <div class="my-10">
                    <label for="message" class="required form-label">Сообщение</label>
                    <textarea name="message" id="message" cols="30" rows="10" required class="form-control form-control-solid" placeholder="Введите текст">{{ old('message') }}</textarea>
                </div>
                <div class="fv-row mb-4">
                    <label for="users" class="fs-6 fw-bold mb-2">Пользователи</label>
                    <select class="form-select" multiple name="users[]" data-control="select2" data-hide-search="false">
                        @foreach($users as $user)
                        <option value="{{ $user->phone_number }}">{{ $user->name.' - '.$user->phone_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex align-items-center mb-8 mt-8">
                    <select class="form-select" name="type" data-control="select2" data-hide-search="true">
                        <option value="">Выберите из списка</option>
                        <option value="all">Отправить всем пользователям</option>
                        <option value="female">Только женщинам</option>
                        <option value="male">Только мужчинам</option>
                    </select>
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
