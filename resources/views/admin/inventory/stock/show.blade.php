@extends('layouts.admin')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Stock Details</h6>
                    <a href="{{ route('admin.stock.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Stocks
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                            <td class="text-xs font-weight-bold mb-0">{{ $stock->id }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Product Name</th>
                                            <td class="text-xs font-weight-bold mb-0">
                                                {{ $stock->product->name ?? 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Stock Quantity</th>
                                            <td class="text-xs font-weight-bold mb-0">
                                                {{ number_format($stock->stock_quantity, 2) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit</th>
                                            <td class="text-xs font-weight-bold mb-0">{{ $stock->stock_quantity_unit }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created At</th>
                                            <td class="text-xs font-weight-bold mb-0">{{ $stock->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Updated At</th>
                                            <td class="text-xs font-weight-bold mb-0">{{ $stock->updated_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 d-flex justify-content-end">
                        <a href="{{ route('admin.stock.edit', $stock->id) }}" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-edit me-1"></i> Edit Stock
                        </a>
                        <form action="{{ route('admin.stock.destroy', $stock->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this stock?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash me-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection