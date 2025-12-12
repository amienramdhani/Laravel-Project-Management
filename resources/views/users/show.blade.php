@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h4>User Details</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">Name</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge bg-primary">{{ $role->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>Permissions</th>
                        <td>
                            @if($user->getAllPermissions()->count() > 0)
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($user->getAllPermissions() as $permission)
                                        <span class="badge bg-secondary">{{ $permission->name }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted">No permissions</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Email Verified</th>
                        <td>
                            @if($user->email_verified_at)
                                <span class="badge bg-success">Yes</span>
                                <small class="text-muted">({{ $user->email_verified_at->format('d M Y H:i') }})</small>
                            @else
                                <span class="badge bg-warning">No</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $user->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>

                @if($user->hasRole('Manager'))
                    <h5 class="mt-4">Managed Projects ({{ $user->managedProjects->count() }})</h5>
                    @if($user->managedProjects->count() > 0)
                        <ul class="list-group">
                            @foreach($user->managedProjects as $project)
                                <li class="list-group-item">
                                    <a href="{{ route('projects.show', $project) }}">{{ $project->title }}</a>
                                    <span class="badge bg-{{ $project->status === 'completed' ? 'success' : ($project->status === 'in_progress' ? 'info' : 'warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endif

                @if($user->hasRole('Staff'))
                    <h5 class="mt-4">Assigned Projects ({{ $user->assignedProjects->count() }})</h5>
                    @if($user->assignedProjects->count() > 0)
                        <ul class="list-group">
                            @foreach($user->assignedProjects as $project)
                                <li class="list-group-item">
                                    <a href="{{ route('projects.show', $project) }}">{{ $project->title }}</a>
                                    <span class="badge bg-{{ $project->status === 'completed' ? 'success' : ($project->status === 'in_progress' ? 'info' : 'warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <h5 class="mt-4">Assigned Tasks ({{ $user->tasks->count() }})</h5>
                    @if($user->tasks->count() > 0)
                        <ul class="list-group">
                            @foreach($user->tasks as $task)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a>
                                        <br>
                                        <small class="text-muted">Project: {{ $task->project->title }}</small>
                                    </div>
                                    <span class="badge bg-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'info' : 'warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endif

                <div class="mt-3">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    @can('update', $user)
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
