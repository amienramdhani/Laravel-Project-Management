@extends('layouts.app')

@section('title', 'Project Details')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Project Details</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">Title</th>
                        <td><strong>{{ $project->title }}</strong></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $project->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Manager</th>
                        <td>
                            {{ $project->manager->name }}
                            <span class="badge bg-info">Manager</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-{{ $project->status === 'completed' ? 'success' : ($project->status === 'in_progress' ? 'info' : 'warning') }}">
                                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Start Date</th>
                        <td>{{ $project->start_date ? $project->start_date->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>End Date</th>
                        <td>{{ $project->end_date ? $project->end_date->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Assigned Staff</th>
                        <td>
                            @if($project->staff->count() > 0)
                                @foreach($project->staff as $staff)
                                    <span class="badge bg-secondary me-1">{{ $staff->name }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">No staff assigned</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $project->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $project->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>

                @if($project->tasks->count() > 0)
                <h5 class="mt-4">Tasks ({{ $project->tasks->count() }})</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Assigned To</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($project->tasks as $task)
                            <tr>
                                <td>{{ $task->title }}</td>
                                <td>{{ $task->assignedTo->name ?? 'Unassigned' }}</td>
                                <td>
                                    <span class="badge bg-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'info' : 'warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </td>
                                <td>{{ $task->due_date ? $task->due_date->format('d M Y') : '-' }}</td>
                                <td>
                                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle"></i> No tasks created yet for this project.
                </div>
                @endif

                <div class="mt-3">
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    @can('update', $project)
                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    @endcan
                    @can('task-create')
                    <a href="{{ route('tasks.create') }}?project_id={{ $project->id }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Add Task
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
