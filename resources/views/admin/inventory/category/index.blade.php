@extends('layouts.admin')

@section('content')
<div class="admin-container">
    <div class="category-card">
        <div class="card-header">
            <div class="header-content">
                <h1><i class="fas fa-tags"></i> Category Management</h1>
                <a href="{{ route('admin.category.create') }}" class="add-category-btn">
                    <i class="fas fa-plus"></i> Add New Category
                </a>
            </div>
            <p>Manage your product categories efficiently</p>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="alert error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="search-section">
                <form method="GET" action="{{ route('admin.category.index') }}" class="search-form">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="q" placeholder="Search categories..." value="{{ request('q') }}">
                        <button type="submit">Search</button>
                    </div>
                </form>
                <div class="results-count">{{ $categories->count() }} categories found</div>
            </div>

            @if($categories->count() > 0)
            <div class="table-container">
                <table class="categories-table">
                    <thead>
                        <tr>
                            <th class="title-col">Title</th>
                            <th class="desc-col">Description</th>
                            <th class="image-col">Image</th>
                            <th class="actions-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td class="title">{{ $category->title }}</td>
                                <td class="description">
                                    <div class="desc-text">{{ $category->description }}</div>
                                </td>
                                <td class="image">
                                    @if ($category->image)
                                        <img src="{{ asset('category/' . $category->image) }}" alt="{{ $category->title }}">
                                    @else
                                        <div class="no-image">
                                            <i class="fas fa-image"></i>
                                            <span>No Image</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="actions">
                                    <a href="{{ route('admin.category.edit', $category->id) }}" class="btn edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.category.destroy', $category->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn delete" onclick="return confirm('Are you sure you want to delete this category?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <h3>No Categories Found</h3>
                <p>There are no categories to display at the moment.</p>
                <a href="{{ route('admin.category.create') }}" class="add-category-btn">
                    <i class="fas fa-plus"></i> Create Your First Category
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Base Styles */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }
    
    .admin-container {
        padding: 20px;
        background: #f6f9fc;
        min-height: 100vh;
    }
    
    .category-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        margin: 0 auto;
        max-width: 1200px;
    }
    
    /* Card Header */
    .card-header {
        background: linear-gradient(120deg, #4b6cb7 0%, #182848 100%);
        color: white;
        padding: 24px 32px;
    }
    
    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    
    .card-header h1 {
        font-size: 24px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .card-header p {
        opacity: 0.9;
        font-size: 14px;
    }
    
    .add-category-btn {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 10px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    
    .add-category-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }
    
    /* Card Body */
    .card-body {
        padding: 32px;
    }
    
    /* Alerts */
    .alert {
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .alert.success {
        background: #e6f7ee;
        color: #0c622e;
        border-left: 4px solid #0c622e;
    }
    
    .alert.error {
        background: #fee;
        color: #d00;
        border-left: 4px solid #d00;
    }
    
    /* Search Section */
    .search-section {
        margin-bottom: 32px;
    }
    
    .search-form {
        margin-bottom: 16px;
    }
    
    .search-box {
        display: flex;
        align-items: center;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    
    .search-box i {
        padding: 0 16px;
        color: #777;
    }
    
    .search-box input {
        flex: 1;
        border: none;
        padding: 14px 0;
        font-size: 16px;
        outline: none;
                color:#000000;

    }
    
    .search-box button {
        background: #4b6cb7;
        color: white;
        border: none;
        padding: 14px 24px;
        cursor: pointer;
        font-weight: 500;
        transition: background 0.3s;
    }
    
    .search-box button:hover {
        background: #3a5ea3;
                color:#000000;

    }
    
    .results-count {
        color: #666;
        font-size: 14px;
    }
    
    /* Table */
    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #eee;
    }
    
    .categories-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .categories-table th {
        background: #f8f9fa;
        padding: 16px;
        text-align: left;
        font-weight: 600;
        color: #444;
        border-bottom: 2px solid #e9ecef;
    }
    
    .categories-table td {
        padding: 16px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }
    
    .categories-table tr:hover {
        background: #f8f9fa;
    }
    
    .title {
        font-weight: 600;
        color: #333;
    }
    
    .description {
        max-width: 300px;
        color:#000000;
    }
    
    .desc-text {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .image img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .no-image {
        display: flex;
        flex-direction: column;
        align-items: center;
        color: #999;
        gap: 4px;
    }
    
    .actions {
        display: flex;
        gap: 8px;
    }
    
    .btn {
        padding: 10px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn.edit {
        background: #e8f4ff;
        color: #0066cc;
        text-decoration: none;
    }
    
    .btn.edit:hover {
        background: #d1e9ff;
    }
    
    .btn.delete {
        background: #ffe8e8;
        color: #cc0000;
        border: none;
    }
    
    .btn.delete:hover {
        background: #ffd1d1;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }
    
    .empty-state i {
        font-size: 64px;
        margin-bottom: 16px;
        color: #ccc;
    }
    
    .empty-state h3 {
        font-size: 24px;
        margin-bottom: 12px;
        color: #444;
    }
    
    .empty-state p {
        margin-bottom: 24px;
        font-size: 16px;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .admin-container {
            padding: 10px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        
        .categories-table {
            min-width: 600px;
        }
        
        .search-box {
            flex-direction: column;
            overflow: visible;
        }
        
        .search-box i {
            display: none;
        }
        
        .search-box input {
            padding: 12px;
            border-bottom: 1px solid #eee;
            width: 100%;
        }
        
        .search-box button {
            width: 100%;
            padding: 12px;
        }
    }
</style>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
    // Simple confirmation for delete actions
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('form[action*="destroy"]');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to delete this category?')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endsection