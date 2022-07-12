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
            <!-- <a href="{{ route('products.upload_from') }}" class="btn btn-sm btn-light-primary d-flex align-items-center" title="Download updates">
                <span class="svg-icon svg-icon-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M14.5 20.7259C14.6 21.2259 14.2 21.826 13.7 21.926C13.2 22.026 12.6 22.0259 12.1 22.0259C9.5 22.0259 6.9 21.0259 5 19.1259C1.4 15.5259 1.09998 9.72592 4.29998 5.82592L5.70001 7.22595C3.30001 10.3259 3.59999 14.8259 6.39999 17.7259C8.19999 19.5259 10.8 20.426 13.4 19.926C13.9 19.826 14.4 20.2259 14.5 20.7259ZM18.4 16.8259L19.8 18.2259C22.9 14.3259 22.7 8.52593 19 4.92593C16.7 2.62593 13.5 1.62594 10.3 2.12594C9.79998 2.22594 9.4 2.72595 9.5 3.22595C9.6 3.72595 10.1 4.12594 10.6 4.02594C13.1 3.62594 15.7 4.42595 17.6 6.22595C20.5 9.22595 20.7 13.7259 18.4 16.8259Z" fill="currentColor"/>
                        <path opacity="0.3" d="M2 3.62592H7C7.6 3.62592 8 4.02592 8 4.62592V9.62589L2 3.62592ZM16 14.4259V19.4259C16 20.0259 16.4 20.4259 17 20.4259H22L16 14.4259Z" fill="currentColor"/>
                    </svg>
                </span>
                Download updates
            </a> -->
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

                                    <img src="{{ isset($user->img) ? $user->img : '/assets/media/stock/600x400/img-26.jpg' }}" class="" alt="" style="object-fit:cover">
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
                                <a href="{{ route('users.show', ['id' => $user->id]) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-4" title="Edit status">
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