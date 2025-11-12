@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="my-4">Add New Expense</h1>

    @include('admin.expenses._form', ['action' => route('admin.expenses.store'), 'method' => 'POST', 'expense' => new \App\Models\Expense])

    <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary mt-3">Back to Expenses</a>
</div>
@endsection