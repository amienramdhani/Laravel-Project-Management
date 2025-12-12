@extends('layouts.app')

@section('title', 'Financial Reports')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <h2>Financial Reports</h2>
        <p class="text-muted">View comprehensive financial reports and analytics</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-filter"></i> Filter by Date Range</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('reports.index') }}" method="GET" class="row g-3">
            <div class="col-md-5">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date"
                       value="{{ $startDate }}" required>
            </div>
            <div class="col-md-5">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date"
                       value="{{ $endDate }}" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block w-100">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card border-success">
            <div class="card-body text-center">
                <h6 class="text-muted mb-2">Total Income</h6>
                <h2 class="text-success mb-0">
                    <i class="fas fa-arrow-up"></i>
                    Rp {{ number_format($income, 0, ',', '.') }}
                </h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card border-danger">
            <div class="card-body text-center">
                <h6 class="text-muted mb-2">Total Expense</h6>
                <h2 class="text-danger mb-0">
                    <i class="fas fa-arrow-down"></i>
                    Rp {{ number_format($expense, 0, ',', '.') }}
                </h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card border-{{ $netProfit >= 0 ? 'primary' : 'warning' }}">
            <div class="card-body text-center">
                <h6 class="text-muted mb-2">Net Profit/Loss</h6>
                <h2 class="text-{{ $netProfit >= 0 ? 'primary' : 'warning' }} mb-0">
                    <i class="fas fa-{{ $netProfit >= 0 ? 'chart-line' : 'exclamation-triangle' }}"></i>
                    Rp {{ number_format($netProfit, 0, ',', '.') }}
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Income by Category</h5>
            </div>
            <div class="card-body">
                @if($incomeByCategory->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($incomeByCategory as $item)
                                <tr>
                                    <td>{{ $item->category ?? 'Uncategorized' }}</td>
                                    <td class="text-end">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center mb-0">No income data in this period</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Expense by Category</h5>
            </div>
            <div class="card-body">
                @if($expenseByCategory->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenseByCategory as $item)
                                <tr>
                                    <td>{{ $item->category ?? 'Uncategorized' }}</td>
                                    <td class="text-end">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center mb-0">No expense data in this period</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>Transaction Details</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="transactions-table">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->date->format('d M Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $transaction->type === 'income' ? 'success' : 'danger' }}">
                                {{ ucfirst($transaction->type) }}
                            </span>
                        </td>
                        <td>{{ $transaction->category ?? '-' }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td class="text-end">
                            <span class="text-{{ $transaction->type === 'income' ? 'success' : 'danger' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }}
                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No transactions in this period</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#transactions-table').DataTable({
        order: [[0, 'desc']],
        pageLength: 25
    });
});
</script>
@endpush
