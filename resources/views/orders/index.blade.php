@extends('layouts.app')

@section('title', 'Orders')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h2>Orders Management</h2>
    </div>
    <div class="col-md-6 text-end">
        @can('order-create')
        <a href="{{ route('orders.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Order
        </a>
        @endcan
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="orders-table">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Order Number</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th width="20%">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#orders-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('orders.index') }}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'order_number', name: 'order_number'},
            {data: 'customer_name', name: 'customer.name'},
            {data: 'formatted_amount', name: 'total_amount'},
            {data: 'status', name: 'status'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});
</script>
@endpush
