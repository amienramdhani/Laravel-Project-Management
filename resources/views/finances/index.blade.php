@extends('layouts.app')

@section('title', 'Finance')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h2>Finance Management</h2>
    </div>
    <div class="col-md-6 text-end">
        @can('finance-create')
        <a href="{{ route('finances.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Record
        </a>
        @endcan
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="finances-table">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Amount</th>
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
    $('#finances-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('finances.index') }}',
        order: [[1, 'desc']],
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'date', name: 'date'},
            {data: 'type_badge', name: 'type'},
            {data: 'category', name: 'category'},
            {data: 'description', name: 'description'},
            {data: 'formatted_amount', name: 'amount'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});
</script>
@endpush
