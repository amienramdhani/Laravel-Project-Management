@extends('layouts.app')

@section('title', 'Tasks')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h2>Tasks Management</h2>
    </div>
    <div class="col-md-6 text-end">
        @can('task-create')
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Task
        </a>
        @endcan
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="tasks-table">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Title</th>
                        <th>Project</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th>Due Date</th>
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
    $('#tasks-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('tasks.index') }}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'title', name: 'title'},
            {data: 'project_title', name: 'project.title'},
            {data: 'assigned_to', name: 'assignedTo.name'},
            {data: 'status', name: 'status'},
            {data: 'due_date', name: 'due_date'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});
</script>
@endpush
