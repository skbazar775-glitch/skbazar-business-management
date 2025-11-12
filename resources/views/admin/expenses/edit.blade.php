@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="my-4">Edit Expense</h1>

    @include('admin.expenses._form', ['action' => route('admin.expenses.update', $expense->id), 'method' => 'PUT', 'expense' => $expense])

    <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary mt-3">Back to Expenses</a>
</div>
@endsection