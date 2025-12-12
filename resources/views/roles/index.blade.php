@extends('layouts.app')

@section('title', 'Roles')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h2>Roles & Permissions Management</h2>
    </div>
    <div class="col-md-6 text-end">
        @can('role-create')
        <a href="{{ route('roles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Role
        </a>
        @endcan
    </div>
</div>

<div class="row">
    @foreach($roles as $role)
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">{{ $role->name }}</h5>
            </div>
            <div class="card-body">
                <h6>Permissions ({{ $role->permissions->count() }})</h6>
                @if($role->permissions->count() > 0)
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($role->permissions as $permission)
                            <span class="badge bg-secondary">{{ $permission->name }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No permissions assigned</p>
                @endif

                <hr>

                <h6>Users with this role ({{ $role->users->count() }})</h6>
                @if($role->users->count() > 0)
                    <ul class="list-unstyled">
                        @foreach($role->users as $user)
                            <li><i class="fas fa-user"></i> {{ $user->name }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No users with this role</p>
                @endif

                <div class="mt-3">
                    @can('role-edit')
                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    @endcan
                    @can('role-delete')
                    @if($role->users->count() === 0)
                    <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                    @endif
                    @endcan
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
