@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h2>Projects Management</h2>
    </div>
    <div class="col-md-6 text-end">
        @can('create', App\Models\Project::class)
        <a href="{{ route('projects.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Project
        </a>
        @endcan
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="projects-table">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Project Name</th>
                        <th>Manager</th>
                        <th>Staff Count</th>
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
    $('#projects-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('projects.index') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'manager_name', name: 'manager.name' },
            { data: 'staff_count', name: 'staff_count', searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });
});
</script>
@endpush
