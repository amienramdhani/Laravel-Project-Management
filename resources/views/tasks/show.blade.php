@extends('layouts.app')

@section('title', 'Task Details')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h4>Task Details</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">Title</th>
                        <td><strong>{{ $task->title }}</strong></td>
                    </tr>
                    <tr>
                        <th>Project</th>
                        <td>
                            <a href="{{ route('projects.show', $task->project) }}">
                                {{ $task->project->title }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $task->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Assigned To</th>
                        <td>
                            @if($task->assignedTo)
                                {{ $task->assignedTo->name }}
                                <span class="badge bg-secondary">{{ $task->assignedTo->roles->first()->name ?? '' }}</span>
                            @else
                                <span class="text-muted">Unassigned</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'info' : 'warning') }}">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Due Date</th>
                        <td>
                            @if($task->due_date)
                                {{ $task->due_date->format('d M Y') }}
                                @if($task->due_date->isPast() && $task->status !== 'completed')
                                    <span class="badge bg-danger">Overdue</span>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $task->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $task->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>

                <div class="mt-3">
                    <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    @can('update', $task)
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
