@extends('layouts.app')

@section('title', 'Clients')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'home',
'route' => 'dashboard'
],
[
'title' => 'clients'
]
]
])

@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">Clients</span>
            <!-- <span class="text-muted mt-1 fw-bold fs-7">Last 12 products</span> -->
        </h3>
        <div class="card-toolbar">
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
                        <th class="ps-4 min-w-325px rounded-start">Name</th>
                        <th class="min-w-125px">Phone number</th>
                        <th class="min-w-125px">Email</th>
                        <th class="min-w-125px">Username</th>
                        <th class="min-w-200px text-end rounded-end">Actions</th>
                    </tr>
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-5">

                                    <img src="{{ isset($user->img) ? $user->img : '/assets/media/default.png' }}" class="" alt="" style="object-fit:cover">
                                </div>
                                <div class="d-flex justify-content-start flex-column">
                                    <a href="#" class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{ $user->name ?? '--' }}</a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a class="text-dark fw-bolder text-hover-primary d-block mb-1 fs-6">{{ $user->phone_number ?? '--' }}</a>
                        </td>
                        <td>
                            <a class="text-dark fw-bolder text-hover-primary d-block mb-1 fs-6">{{ $user->email ?? '--' }}</a>
                        </td>
                        <td>
                            <a class="text-dark fw-bolder text-hover-primary d-block mb-1 fs-6">{{ $user->username }}</a>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('users.show', ['id' => $user->id]) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-4">
                                    <!--begin::Svg Icon | path: assets/media/icons/duotune/communication/com006.svg-->
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z" fill="currentColor" />
                                            <path d="M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                </a>
                                <form action="{{ route('users.destroy', ['id' => $user->id]) }}" method="post">
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

{!! $users->links() !!}

@endsection

@section('scripts')

<script>
    function confirmation(item) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                item.parentNode.submit();
            }
        });
    }
</script>

@endsection