@extends('layouts.app')

@section('title', 'Интеграции')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'Главная',
'route' => 'dashboard'
],
[
'title' => 'Бот',
'route' => 'integrations.bot.index'
],
[
'title' => 'Рассылка',
'route' => 'integrations.bot.messages'
],
[
'title' => 'Сделать пост'
],
]
])

@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
    <!--begin::Body-->
    <div class="card-body py-5">
        <div class="card-title">
            <h2 class="h2">Рассылка</h2>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <div class="row">
                    <div class="col-8">
                        <form action="{{route('integrations.bot.messages_send')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="text" class="form-label">Текст сообщение</label>
                                <textarea name="text" required class="form-control form-control-solid" id="text" rows="12">{{old('text')}}</textarea>
                            </div>
                            <div class="mb-5">
                                <div class="form-label" for="files">Файлы</div>
                                <input type="file" name="files[]" id="files" multiple accept="image/png, image/jpeg, image/jpg" class="form-control">
                            </div>
                            <button class="btn btn-info" type="submit">Отправить</button>
                        </form>
                    </div>
                    <div class="col-4">
                        <p>*жирный текст*</p>
                        <p>_курсивный текст_</p>
                        <p>[строковая ссылка](http://www.example.com/)</p>
                        <p>`моноширинный текст`</p>
                        <p>```
                        строковая блок кода
                        ```</p>
                        <p>```предварительно отформатированный блок кода фиксированной ширины, написанный на языке программирования php
                        ```</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--begin::Body-->
</div>

@endsection