@extends('layouts.app')

@section('title', 'Create Project')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h2>Create New Project</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.
                <ul class="mt-2 mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('projects.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control"
                       value="{{ old('title') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Manager <span class="text-danger">*</span></label>
                <select name="manager_id" class="form-select" required>
                    <option value="">-- Select Manager --</option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                            {{ $manager->name }} ({{ $manager->getRoleNames()->implode(', ') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="">-- Select Status --</option>
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control"
                           value="{{ old('start_date') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control"
                           value="{{ old('end_date') }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Assign Staff</label>
                <select name="staff[]" class="form-select" multiple>
                    @foreach ($staff as $s)
                        <option value="{{ $s->id }}"
                            {{ (old('staff') && in_array($s->id, old('staff'))) ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">You can select multiple staff.</small>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Save Project
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
