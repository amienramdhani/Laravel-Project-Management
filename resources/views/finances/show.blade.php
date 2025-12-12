@extends('layouts.app')

@section('title', 'Finance Record Details')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h4>Finance Record Details</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">Type</th>
                        <td>
                            <span class="badge bg-{{ $finance->type === 'income' ? 'success' : 'danger' }}">
                                {{ ucfirst($finance->type) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Amount</th>
                        <td>
                            <strong class="text-{{ $finance->type === 'income' ? 'success' : 'danger' }}">
                                Rp {{ number_format($finance->amount, 0, ',', '.') }}
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td>{{ $finance->category ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $finance->description }}</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ $finance->date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Related To</th>
                        <td>
                            @if($finance->financeable_type)
                                {{ class_basename($finance->financeable_type) }}
                                @if($finance->financeable)
                                    -
                                    @if($finance->financeable_type === 'App\Models\Order')
                                        <a href="{{ route('orders.show', $finance->financeable) }}">
                                            {{ $finance->financeable->order_number }}
                                        </a>
                                    @endif
                                @endif
                            @else
                                <span class="text-muted">Manual Entry</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $finance->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $finance->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>

                <div class="mt-3">
                    <a href="{{ route('finances.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    @can('update', $finance)
                    <a href="{{ route('finances.edit', $finance) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
