@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h4>Order Details</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">Order Number</th>
                        <td><strong>{{ $order->order_number }}</strong></td>
                    </tr>
                    <tr>
                        <th>Customer</th>
                        <td>
                            <a href="{{ route('customers.show', $order->customer) }}">
                                {{ $order->customer->name }}
                            </a>
                            <br>
                            <small class="text-muted">{{ $order->customer->email }}</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Total Amount</th>
                        <td><strong class="text-success">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : ($order->status === 'processing' ? 'info' : 'warning')) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Notes</th>
                        <td>{{ $order->notes ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $order->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>

                @if($order->finance)
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle"></i>
                    <strong>Finance Record:</strong> This order has a linked finance income record of
                    <strong>Rp {{ number_format($order->finance->amount, 0, ',', '.') }}</strong>
                    created on {{ $order->finance->date->format('d M Y') }}.
                    @can('finance-list')
                        <a href="{{ route('finances.show', $order->finance) }}" class="alert-link">View Finance Record</a>
                    @endcan
                </div>
                @endif

                <div class="mt-3">
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    @can('update', $order)
                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
