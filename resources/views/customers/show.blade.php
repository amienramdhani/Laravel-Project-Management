@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h4>Customer Details</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">Name</th>
                        <td>{{ $customer->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $customer->email }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $customer->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $customer->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Total Orders</th>
                        <td>{{ $customer->orders->count() }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $customer->created_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>

                @if($customer->orders->count() > 0)
                <h5 class="mt-4">Orders History</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Order Number</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->orders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('orders.show', $order) }}">{{ $order->order_number }}</a>
                                </td>
                                <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <div class="mt-3">
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    @can('update', $customer)
                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
