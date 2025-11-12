@extends('layouts.admin')

@section('title', 'Staff Details')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Staff Details</h4>
        </div>
        <div class="card-body">
            <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary mb-3">Back</a>
            <dl class="row">
                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">{{ $staff->id }}</dd>
                <dt class="col-sm-3">Name</dt>
                <dd class="col-sm-9">{{ $staff->name }}</dd>
                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9">{{ $staff->email }}</dd>
                <dt class="col-sm-3">Salary</dt>
                <dd class="col-sm-9">{{ $staff->salary }}</dd>
                <dt class="col-sm-3">Created At</dt>
                <dd class="col-sm-9">{{ $staff->created_at->format('d/m/Y H:i') }}</dd>
                <dt class="col-sm-3">Updated At</dt>
                <dd class="col-sm-9">{{ $staff->updated_at->format('d/m/Y H:i') }}</dd>
            </dl>
        </div>
    </div>
@endsection

@section('scripts')
    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
        </script>
    @endif
@endsection