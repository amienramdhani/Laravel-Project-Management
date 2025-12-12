@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Dashboard</h2>
        <p>Welcome, <strong>{{ auth()->user()->name }}</strong>!
           Role: <span class="badge bg-primary">{{ auth()->user()->roles->first()->name ?? 'No Role' }}</span>
        </p>
    </div>
</div>

<div class="row mt-4">
    @can('user-list')
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Users</h6>
                        <h3 class="mb-0">{{ $totalUsers }}</h3>
                    </div>
                    <div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('users.index') }}" class="text-white text-decoration-none">
                    View Details <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    @endcan

    @can('customer-list')
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Customers</h6>
                        <h3 class="mb-0">{{ $totalCustomers }}</h3>
                    </div>
                    <div>
                        <i class="fas fa-user-friends fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('customers.index') }}" class="text-white text-decoration-none">
                    View Details <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    @endcan

    @can('project-list')
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Projects</h6>
                        <h3 class="mb-0">{{ $totalProjects }}</h3>
                    </div>
                    <div>
                        <i class="fas fa-project-diagram fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('projects.index') }}" class="text-white text-decoration-none">
                    View Details <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    @endcan

    @can('order-list')
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Orders</h6>
                        <h3 class="mb-0">{{ $totalOrders }}</h3>
                    </div>
                    <div>
                        <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('orders.index') }}" class="text-white text-decoration-none">
                    View Details <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    @endcan
</div>

<div class="row mt-3">
    @can('finance-list')
    <div class="col-md-6 mb-3">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <i class="fas fa-arrow-up"></i> Total Income
            </div>
            <div class="card-body">
                <h2 class="text-success">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h2>
                <a href="{{ route('finances.index') }}" class="btn btn-success btn-sm mt-2">View Finance</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <i class="fas fa-arrow-down"></i> Total Expense
            </div>
            <div class="card-body">
                <h2 class="text-danger">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h2>
                <a href="{{ route('finances.index') }}" class="btn btn-danger btn-sm mt-2">View Finance</a>
            </div>
        </div>
    </div>
    @endcan
</div>

@if(auth()->user()->hasRole('Staff'))
<div class="row mt-3">
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-folder"></i> My Projects
            </div>
            <div class="card-body">
                <h3>{{ $myProjects }}</h3>
                <p class="text-muted">Projects assigned to you</p>
                <a href="{{ route('projects.index') }}" class="btn btn-primary btn-sm">View Projects</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-tasks"></i> My Tasks
            </div>
            <div class="card-body">
                <h3>{{ $myTasks }}</h3>
                <p class="text-muted">Tasks assigned to you</p>
                <a href="{{ route('tasks.index') }}" class="btn btn-primary btn-sm">View Tasks</a>
            </div>
        </div>
    </div>
</div>
@endif

@can('report-view')
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-line"></i> Quick Reports Access
            </div>
            <div class="card-body">
                <p>View comprehensive financial reports and analytics</p>
                <a href="{{ route('reports.index') }}" class="btn btn-primary">
                    <i class="fas fa-file-alt"></i> View Reports
                </a>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection
