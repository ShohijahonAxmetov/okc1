<ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
    @if(isset($items))
        @foreach($items as $item)
            @if(!$loop->last)
                <!--begin::Item-->
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route($item['route'] ?? '') }}" class="text-muted text-hover-primary text-capitalize">{{ $item['title'] }}</a>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-300 w-5px h-2px"></span>
                </li>
                <!--end::Item-->
            @else
                <!--begin::Item-->
                <li class="breadcrumb-item text-dark text-capitalize">{{ $item['title'] }}</li>
                <!--end::Item-->
            @endif
        @endforeach
    @endif
</ul>