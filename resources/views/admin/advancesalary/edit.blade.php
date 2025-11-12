@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Advance Salary</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.advancesalary.update', $advanceSalary->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="staff_id">Staff</label>
            <select name="staff_id" id="staff_id" class="form-control" required>
                <option value="">Select Staff</option>
                @foreach ($staffs as $staff)
                    <option value="{{ $staff->id }}" {{ $advanceSalary->staff_id == $staff->id ? 'selected' : '' }}>
                        {{ $staff->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" name="amount" id="amount" class="form-control" value="{{ $advanceSalary->amount }}" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="total_emi">Total EMI</label>
            <input type="number" name="total_emi" id="total_emi" class="form-control" value="{{ $advanceSalary->total_emi }}" required>
        </div>

        <div class="form-group">
            <label for="advance_date">Advance Date</label>
            <input type="date" name="advance_date" id="advance_date" class="form-control" value="{{ $advanceSalary->advance_date }}" required>
        </div>

        <div class="form-group">
            <label for="note">Note</label>
            <textarea name="note" id="note" class="form-control">{{ $advanceSalary->note }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.advancesalary.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection