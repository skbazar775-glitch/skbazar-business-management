@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="my-4">Expenses</h1>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                confirmButtonColor: '#198754'
            });
        </script>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonColor: '#dc3545'
            });
        </script>
    @endif

    <!-- Search Form -->
    <form method="GET" action="{{ route('admin.expenses.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search by title..." value="{{ request('q') }}">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.expenses.create') }}" class="btn btn-success">Add New Expense</a>
            </div>
        </div>
    </form>

    <!-- Expenses Table -->
<!-- Expenses Table -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Title</th>
            <th>Amount</th>
            <th>Expense Date</th>
            <th>Notes</th>
            <th>Status</th> {{-- ✅ NEW --}}
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($expenses as $expense)
            <tr>
                <td>{{ $expense->title }}</td>
                <td>{{ number_format($expense->amount, 2) }}</td>
                <td>{{ $expense->expense_date->format('Y-m-d') }}</td>
                <td>{{ $expense->notes ?? '-' }}</td>
                <td>
                    {{-- ✅ Status Badge --}}
                    @if ($expense->status === 'normal')
                        <span class="badge bg-success">Normal</span>
                    @elseif ($expense->status === 'loss')
                        <span class="badge bg-danger">Loss</span>
                    @else
                        <span class="badge bg-secondary">{{ ucfirst($expense->status) }}</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.expenses.edit', $expense->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('admin.expenses.destroy', $expense->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No expenses found.</td> {{-- updated colspan to 6 --}}
            </tr>
        @endforelse
    </tbody>
</table>


    <!-- Pagination -->
</div>
@endsection