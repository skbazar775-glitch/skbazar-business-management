@extends('layouts.admin')
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Create Product</h4>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back to Products</a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: '{{ session('success') }}',
                        confirmButtonColor: '#198754'
                    });
                </script>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please check the form for errors.',
                        confirmButtonColor: '#dc3545'
                    });
                </script>
            @endif

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.inventory.products._form')
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Create Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const priceS = document.getElementById('price_s');
            const priceB = document.getElementById('price_b');
            const priceP = document.getElementById('price_p');

            function calculateProfit() {
                const s = parseFloat(priceS.value) || 0;
                const b = parseFloat(priceB.value) || 0;
                priceP.value = (s - b).toFixed(2);
            }

            priceS.addEventListener('input', calculateProfit);
            priceB.addEventListener('input', calculateProfit);
        });
    </script>
@endsection