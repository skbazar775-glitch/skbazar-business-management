@extends('layouts.admin')

@section('title', 'Manage Managers')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<div class="container-fluid px-4 py-6">
    <!-- Card -->
    <div class="card bg-white shadow-lg rounded-xl overflow-hidden" data-aos="fade-up">
        <div class="card-header bg-gradient-to-r from-blue-600 to-indigo-600 text-white flex justify-between items-center py-4 px-6">
            <h4 class="text-xl font-bold mb-0">Managers</h4>
            <a href="{{ route('admin.managers.create') }}" class="btn bg-gradient-to-r from-teal-500 to-cyan-600 text-white px-4 py-2 rounded-lg hover:shadow-lg hover:scale-105 transition-all">
                <i class="fas fa-plus mr-2"></i> Add New Manager
            </a>
        </div>
        <div class="card-body p-6">
            <div class="overflow-x-auto">
                <table class="w-full table-auto bg-white shadow-md rounded-lg">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left">ID</th>
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($managers as $manager)
                            <tr class="hover:bg-blue-50 hover:translate-x-1 transition-all" data-aos="fade-up" data-aos-delay="{{ 100 * $loop->index }}">
                                <td class="px-4 py-3">{{ $manager->id }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $manager->name }}</td>
                                <td class="px-4 py-3">{{ $manager->email }}</td>
                                <td class="px-4 py-3 flex gap-2">
                                    <a href="{{ route('admin.managers.show', $manager) }}" class="btn bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-3 py-1 rounded-lg hover:shadow-lg hover:scale-105 transition-all">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                    <a href="{{ route('admin.managers.edit', $manager) }}" class="btn bg-gradient-to-r from-yellow-500 to-orange-600 text-white px-3 py-1 rounded-lg hover:shadow-lg hover:scale-105 transition-all">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-center text-gray-500">No managers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $managers->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

<style>
    .swal2-popup {
        border-radius: 0.75rem;
        box-shadow: 0 0.15rem 1.75rem rgba(0, 0, 0, 0.1);
    }
    .swal2-confirm {
        background: linear-gradient(to right, #dc2626, #b91c1c) !important;
        border-radius: 0.5rem;
    }
    .swal2-cancel {
        background: linear-gradient(to right, #6b7280, #4b5563) !important;
        border-radius: 0.5rem;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS animations
        AOS.init({ duration: 800, easing: 'ease-in-out', once: true });

        // Handle delete confirmation with SweetAlert2
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        popup: 'swal2-popup',
                        confirmButton: 'swal2-confirm',
                        cancelButton: 'swal2-cancel'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData(form);
                        formData.append('_method', 'DELETE'); // force DELETE method

                        fetch(form.action, {
                            method: 'POST', // Laravel handles DELETE via _method
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: data.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: data.message || 'Failed to delete manager.',
                                    icon: 'error'
                                });
                            }
                        })
                        .catch(() => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Something went wrong. Please try again.',
                                icon: 'error'
                            });
                        });
                    }
                });
            });
        });
    });
</script>
@endsection
