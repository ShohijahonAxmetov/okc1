<div class="card mb-3">
    <div class="card-body">
        <div class="links d-flex flex-wrap">
            <a href="{{ route('orders.new') }}" class="btn d-flex {{ $active == 'new' ? 'bg-secondary' : '' }}">
                <span class="d-flex rounded-circle me-1" style="width: 20px;height: 20px;background-color: rgb(13,202,240);"></span>
                Новые ({{ $orders_count['new'] }})
            </a>
            <a href="{{ route('orders.accepted') }}" class="btn d-flex {{ $active == 'accepted' ? 'bg-secondary' : '' }}">
                <span class="d-flex rounded-circle me-1" style="width: 20px;height: 20px;background-color: #181c32;"></span>
                Принятые ({{ $orders_count['accepted'] }})
            </a>
            <a href="{{ route('orders.collected') }}" class="btn d-flex {{ $active == 'collected' ? 'bg-secondary' : '' }}">
                <span class="d-flex rounded-circle me-1" style="width: 20px;height: 20px;background-color: rgb(13,110,253);"></span>
                Готовые к отправке ({{ $orders_count['collected'] }})
            </a>
            <a href="{{ route('orders.on_the_way') }}" class="btn d-flex {{ $active == 'on_the_way' ? 'bg-secondary' : '' }}">
                <span class="d-flex rounded-circle me-1" style="width: 20px;height: 20px;background-color: rgb(225,193,7);"></span>
                В доставке ({{ $orders_count['on_the_way'] }})
            </a>
            <a href="{{ route('orders.returned') }}" class="btn d-flex {{ $active == 'returned' ? 'bg-secondary' : '' }}">
                <span class="d-flex rounded-circle me-1" style="width: 20px;height: 20px;background-color: #ff39f9;"></span>
                Возврат ({{ $orders_count['returned'] }})
            </a>
            <a href="{{ route('orders.done') }}" class="btn d-flex {{ $active == 'done' ? 'bg-secondary' : '' }}">
                <span class="d-flex rounded-circle me-1" style="width: 20px;height: 20px;background-color: rgb(25,135,84);"></span>
                Доставленные ({{ $orders_count['done'] }})
            </a>
            <a href="{{ route('orders.cancelled') }}" class="btn d-flex {{ $active == 'cancelled' ? 'bg-secondary' : '' }}">
                <span class="d-flex rounded-circle me-1" style="width: 20px;height: 20px;background-color: rgb(220,53,69);"></span>
                Отмененные ({{ $orders_count['cancelled'] }})
            </a>
        </div>
    </div>
</div>