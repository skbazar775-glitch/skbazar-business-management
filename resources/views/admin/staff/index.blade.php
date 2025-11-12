@extends('layouts.admin')

@section('title', 'Manage Staff')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<div class="container-fluid px-4 py-6">
    <!-- Card -->
    <div class="card bg-white shadow-lg rounded-xl overflow-hidden" data-aos="fade-up">
        <!-- Header -->
        <div class="card-header bg-gradient-to-r from-indigo-600 to-purple-600 text-white flex justify-between items-center py-4 px-6">
            <h4 class="text-xl font-bold mb-0">Staff</h4>
            <a href="{{ route('admin.staff.create') }}" 
               class="btn bg-gradient-to-r from-teal-500 to-cyan-600 text-white px-4 py-2 rounded-lg hover:shadow-lg hover:scale-105 transition-all">
                <i class="fas fa-plus mr-2"></i> Add New Staff
            </a>
        </div>

        <!-- Table -->
        <div class="card-body p-6">
            <div class="overflow-x-auto">
                <table class="w-full table-auto bg-white shadow-md rounded-lg">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left">ID</th>
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-left">Salary</th>
                            <th class="px-4 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($staff as $staffMember)
                            <tr class="hover:bg-indigo-50 hover:translate-x-1 transition-all" 
                                data-aos="fade-up" 
                                data-aos-delay="{{ 100 * $loop->index }}">
                                <td class="px-4 py-3">{{ $staffMember->id }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $staffMember->name }}</td>
                                <td class="px-4 py-3">{{ $staffMember->email }}</td>
                                <td class="px-4 py-3 text-green-600 font-medium">₹{{ number_format($staffMember->salary, 2) }}</td>
                                <td class="px-4 py-3 flex gap-2">
                                    <a href="{{ route('admin.staff.show', $staffMember) }}" 
                                       class="btn bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-3 py-1 rounded-lg hover:shadow-lg hover:scale-105 transition-all">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                    <a href="{{ route('admin.staff.edit', $staffMember) }}" 
                                       class="btn bg-gradient-to-r from-yellow-500 to-orange-600 text-white px-3 py-1 rounded-lg hover:shadow-lg hover:scale-105 transition-all">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
  
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-gray-500">No staff found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $staff->links('pagination::tailwind') }}
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
        // Init AOS animations
        AOS.init({ duration: 800, easing: 'ease-in-out', once: true });

        // Handle delete with SweetAlert2
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
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
                        form.submit();
                    }
                });
            });
        });

        // Show success flash
        @if (session('success'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
        @endif
    });
</script>
@endsection
