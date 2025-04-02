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
'title' => 'Рассылка #'.$message->id
],
]
])

@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
    <!--begin::Body-->
    <div class="card-body py-5">
        <div class="card-title">
            <h2 class="h2">Рассылка #{{$message->id}}</h2>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <div class="row">
                    <div class="col-8">
                        <div>
                            <div class="mb-3">
                                <label for="text" class="form-label">Текст сообщение</label>
                                <textarea name="text" required class="form-control form-control-solid" id="text" rows="16" disabled>{{old('text', $message->text)}}</textarea>
                            </div>
                            <div class="mb-5">
                                @if(isset($message->photos[0]))
                                <div class="form-label" for="files">Файлы</div>
                                <div class="row">
                                    @foreach($message->photos as $photo)
                                    <div class="col-3">
                                        <img src="{{$photo->path}}" alt="#" class="w-100">
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--begin::Body-->
</div>

@endsection