@extends('layouts.app')

@section('title', 'MAILING')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'home',
'route' => 'dashboard'
],
[
'title' => 'mailing'
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
                    <label for="subject" class="required form-label">Subject</label>
                    <input name="subject" id="subject" class="form-control form-control-solid" placeholder="subject">
                </div>
                <div class="my-10">
                    <label for="message" class="required form-label">Message</label>
                    <textarea name="message" id="message" cols="30" rows="10" class="form-control form-control-solid" placeholder="enter message..."></textarea>
                </div>
                <div class="fv-row mb-4">
                    <label for="users" class="fs-6 fw-bold mb-2">Users</label>
                    <select class="form-select" multiple name="users[]" data-control="select2" data-hide-search="false">
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->email }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex align-items-center mb-8">
                    <!--begin::Option-->
                    <label class="form-check-solid me-5  fw-bold">Send all users
                        <input class="form-check-input" name="all_users" type="checkbox" value="1">
                    </label>
                    <!--end::Options-->
                </div>
            </div>
            <div class="d-flex justify-content-end mt-8">
                <button class="btn btn-success me-2 mb-2 px-8">Send</button>
            </div>
        </form>

    </div>
    <!--begin::Body-->
</div>

@endsection