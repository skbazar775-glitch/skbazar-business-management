@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">HSN Codes</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.invoice.hsn.create') }}" class="btn btn-primary">Add New HSN Code</a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>GST Rate (%)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hsnCodes as $hsnCode)
                                <tr>
                                    <td>{{ $hsnCode->id }}</td>
                                    <td>{{ $hsnCode->code }}</td>
                                    <td>{{ $hsnCode->description }}</td>
                                    <td>{{ $hsnCode->gst_rate }}</td>
                                    <td>
                                        <a href="{{ route('admin.invoice.hsn.edit', $hsnCode->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection