@extends('layouts.app')

@section('title', 'CONTENT MANAGERS')

@section('breadcrumb')

@include('app.components.breadcrumb', [
'items' => [
[
'title' => 'home',
'route' => 'dashboard'
],
[
'title' => 'content managers'
]
]
])

@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <!--begin::Navbar-->
            <div class="card mb-5 mb-xl-10">
                <div class="card-body pt-9 pb-0">
                    <!--begin::Details-->
                    <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                        <!--begin: Pic-->
                        <div class="me-7 mb-4">
                            <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                <img src="{{ $user->img ? asset($user->img) : '/assets/media/avatars/300-1.jpg' }}" alt="image" />
                            </div>
                        </div>
                        <!--end::Pic-->
                        <!--begin::Info-->
                        <div class="flex-grow-1">
                            <!--begin::Title-->
                            <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                <!--begin::User-->
                                <div class="d-flex flex-column">
                                    <!--begin::Name-->
                                    <div class="d-flex align-items-center mb-5">
                                        <a class="text-gray-900 text-hover-primary fs-2 fw-bolder me-1">{{ $user->name ?? 'No name' }}</a>
                                    </div>
                                    <!--end::Name-->
                                    <!--begin::Info-->
                                    <div class="d-flex flex-wrap flex-column fw-bold fs-6 mb-4 pe-2">
                                        <a class="d-flex align-items-center text-gray-400 text-hover-primary mb-2 me-6">
                                            Username:<span class="text-gray-800 ps-2">{{ $user->username }}</span>
                                        </a>
                                        <a class="d-flex align-items-center text-gray-400 text-hover-primary mb-2 me-6">
                                            Phone number:<span class="text-gray-800 ps-2">{{ $user->phone_number ?? '--' }}</span>
                                        </a>
                                        <a class="d-flex align-items-center text-gray-400 text-hover-primary mb-2 me-6">
                                            Role:<span class="text-gray-800 ps-2">Content manager</span>
                                        </a>
                                        <a class="d-flex align-items-center text-gray-400 text-hover-primary mb-2 me-6">
                                            Date added:<span class="text-gray-800 ps-2">{{ date('d/m/Y', strtotime($user->created_at)) }}</span>
                                        </a>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Info-->
                    </div>
                    <!--end::Details-->
                </div>
            </div>
            <!--end::Navbar-->
        </div>
        <!--end::Container-->
    </div>
</div>

<!--begin::Card-->
<div class="card pt-4">
    <!--begin::Card header-->
    <div class="card-header border-0">
        <!--begin::Card title-->
        <div class="card-title">
            <h2>Logs</h2>
        </div>
        <!--end::Card title-->
        <!--begin::Card toolbar-->
        <div class="card-toolbar">
            <!--begin::Button-->
            <!-- <button type="button" class="btn btn-sm btn-light-primary">
                <span class="svg-icon svg-icon-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path opacity="0.3" d="M19 15C20.7 15 22 13.7 22 12C22 10.3 20.7 9 19 9C18.9 9 18.9 9 18.8 9C18.9 8.7 19 8.3 19 8C19 6.3 17.7 5 16 5C15.4 5 14.8 5.2 14.3 5.5C13.4 4 11.8 3 10 3C7.2 3 5 5.2 5 8C5 8.3 5 8.7 5.1 9H5C3.3 9 2 10.3 2 12C2 13.7 3.3 15 5 15H19Z" fill="currentColor" />
                        <path d="M13 17.4V12C13 11.4 12.6 11 12 11C11.4 11 11 11.4 11 12V17.4H13Z" fill="currentColor" />
                        <path opacity="0.3" d="M8 17.4H16L12.7 20.7C12.3 21.1 11.7 21.1 11.3 20.7L8 17.4Z" fill="currentColor" />
                    </svg>
                </span>
            </button> -->
            <!--end::Button-->
        </div>
        <!--end::Card toolbar-->
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body py-0">
        <!--begin::Table wrapper-->
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fw-bold text-gray-600 fs-6 gy-5" id="kt_table_customers_logs">
                <!--begin::Table body-->
                <tbody>
                    <tr class="fw-bolder text-muted bg-light">
                        <th class="rounded-start ps-2">Log id</th>
                        <th>Action</th>
                        <th class="text-end rounded-end pe-2">Datetime</th>
                    </tr>
                    <!--begin::Table row-->
                    @foreach($user->logs()->latest()->paginate(12) as $log)
                    <tr>
                        <!--begin::Badge=-->
                        <td class="min-w-70px">
                            <div>#{{ $log->id }}</div>
                        </td>
                        <!--end::Badge=-->
                        <!--begin::Status=-->
                        <td><a>{{ $log->action }} {{ $log->model }} with id #{{ $log->item_id }}</a></td>
                        <!--end::Status=-->
                        <!--begin::Timestamp=-->
                        <td class="pe-0 text-end min-w-200px">{{ date('H:i d/m/Y', strtotime($log->created_at)) }}</td>
                        <!--end::Timestamp=-->
                    </tr>
                    @endforeach
                    <!--end::Table row-->
                </tbody>
                <!--end::Table body-->
            </table>
            <!--end::Table-->
        </div>
        <!--end::Table wrapper-->
    </div>
    <!--end::Card body-->
</div>
<!--end::Card-->

<div class="mt-8">
    {{ $user->logs()->paginate(12)->links() }}
</div>

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