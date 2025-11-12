@extends('layouts.admin')

@section('content')
<!-- Font Awesome + SweetAlert -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* ====== Full-page Windows 7 Aero Blue Glass ====== */
:root{
  --aero-blue: rgba(65,118,182,0.65);
  --aero-glow: rgba(75,108,183,0.22);
  --card-bg: rgba(255,255,255,0.6);
  --text-color: #0b2a66;
}

/* page background with wallpaper-like feel */
body {
  margin: 0;
  min-height: 100vh;
  font-family: "Segoe UI", "Inter", system-ui, -apple-system, "Helvetica Neue", Arial;
  background: linear-gradient(180deg, rgba(21,38,74,0.45), rgba(6,16,34,0.45)), url('/images/win7-glass-bg.jpg') center/cover fixed no-repeat;
  color: var(--text-color);
  -webkit-font-smoothing:antialiased;
}

/* light sweep animation */
.aero-sweep {
  position: fixed;
  inset: -40% -40%;
  pointer-events: none;
  opacity: 0.12;
  background: linear-gradient(120deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.25) 50%, rgba(255,255,255,0.05) 100%);
  transform: translateX(-100%) rotate(-12deg);
  filter: blur(30px);
  animation: sweep 10s linear infinite;
  z-index: 0;
}
@keyframes sweep {
  0% { transform: translateX(-120%) rotate(-12deg); }
  100% { transform: translateX(120%) rotate(-12deg); }
}

/* container */
.admin-shell {
  position: relative;
  z-index: 1; /* above sweep */
  padding: 28px;
  box-sizing: border-box;
}

/* Glass card surfaces (product card, search, modals) */
.glass {
  background: linear-gradient(180deg, rgba(255,255,255,0.55), rgba(255,255,255,0.42));
  backdrop-filter: blur(14px) saturate(130%);
  -webkit-backdrop-filter: blur(14px) saturate(130%);
  border: 1px solid rgba(255,255,255,0.45);
  box-shadow: 0 8px 30px rgba(10,25,60,0.18), 0 1px 0 rgba(255,255,255,0.06) inset;
  border-radius: 12px;
  color: var(--text-color);
}

/* Accent header gradient (keeps Windows 7 blue feel) */
.card-header {
  background: linear-gradient(120deg, #4b6cb7 0%, #182848 100%);
  color: #fff;
  padding: 18px 22px;
  border-top-left-radius: 12px;
  border-top-right-radius: 12px;
}

/* Product card */
.product-card {
  max-width: 1300px;
  margin: 0 auto;
  overflow: hidden;
}

/* Body */
.card-body {
  padding: 20px;
}

/* Search / filters */
.search-section {
  display:flex;
  justify-content:space-between;
  gap:12px;
  align-items:center;
  margin-bottom:14px;
}
.search-row { display:flex; gap:12px; align-items:center; width:100%; flex-wrap:wrap; }
.search-box {
  display:flex; align-items:center; gap:8px;
  background: rgba(255,255,255,0.9);
  border-radius:8px; padding:6px 10px; border:1px solid rgba(11,42,102,0.06);
  box-shadow: 0 2px 8px rgba(11,42,102,0.04);
}
.search-box input { border: none; outline:none; padding:8px; min-width:220px; }
.search-box button { background: #1f4fb3; color:#fff; border:none; padding:8px 10px; border-radius:6px; cursor:pointer; }

/* Filter actions */
.filter-actions { display:flex; gap:8px; align-items:center; }
.filter-actions select, .filter-actions button { padding:8px 10px; border-radius:8px; border:1px solid rgba(11,42,102,0.06); background: rgba(255,255,255,0.95); }

/* Table */
.table-container {
  overflow-x:auto;
  border-radius:8px;
  margin-top:6px;
}
.products-table {
  width:100%;
  border-collapse:collapse;
  background: rgba(255,255,255,0.6);
}
.products-table th, .products-table td {
  padding:12px 14px;
  border-bottom:1px solid rgba(11,42,102,0.06);
  text-align:left;
  vertical-align:middle;
}
.products-table th {
  font-weight:700;
  background: rgba(255,255,255,0.72);
  border-bottom: 2px solid rgba(11,42,102,0.08);
}

/* Row hover */
.products-table tr:hover { background: rgba(11,42,102,0.03); }

/* Status badges & stock dot */
.status-badge { padding:6px 10px; border-radius:12px; font-weight:700; font-size:13px; display:inline-block; margin-right:8px; }
.status-active { background: rgba(21, 199, 132, 0.14); color:#0c6d3b; }
.status-inactive { background: rgba(255, 99, 71, 0.12); color:#a12a2a; }
.stock-dot { display:inline-block; width:10px; height:10px; border-radius:50%; vertical-align:middle; margin-left:6px; box-shadow: 0 1px 2px rgba(0,0,0,0.12); }
.stock-low { background:#ffb4a2; }
.stock-normal { background:#ffd78f; }
.stock-over { background:#b5f5c6; }

/* Images */
.image img { width:60px; height:60px; object-fit:cover; border-radius:8px; }

/* Actions / buttons */
.actions { display:flex; gap:8px; align-items:center; }
.btn { padding:8px; border-radius:8px; border:none; cursor:pointer; }
.btn.edit { background: #e8f4ff; color:var(--text-color); }
.btn.delete { background: #ffecec; color:var(--text-color); }
.btn.primary { background: linear-gradient(90deg,#4b6cb7,#182848); color:#fff; }

/* Toggle switch */
.switch { position:relative; display:inline-block; width:48px; height:24px; }
.switch input { display:none; }
.slider { position:absolute; inset:0; background:#ff3b30; border-radius:24px; transition:all .3s; box-shadow:0 2px 6px rgba(0,0,0,0.12); }
.slider:before { content:""; position:absolute; height:20px; width:20px; left:2px; bottom:2px; background:#fff; border-radius:50%; transition:all .3s; box-shadow:0 1px 2px rgba(0,0,0,0.15); }
input:checked + .slider { background:#34c759; }
input:checked + .slider:before { transform:translateX(24px); }

/* Spinner */
.spinner { display:none; position:absolute; width:16px; height:16px; border-radius:50%; border:2px solid rgba(255,255,255,0.6); border-top-color:rgba(0,0,0,0.18); top:4px; left:4px; }
.switch.loading .spinner { display:block; animation:spin 1s linear infinite; }
@keyframes spin { from{transform:rotate(0)} to{transform:rotate(360deg)} }

/* Pagination */
.pagination-container { display:flex; justify-content:center; margin-top:12px; }

/* Empty state */
.empty-state { text-align:center; padding:30px; color:rgba(11,42,102,0.6); }
.empty-state i { font-size:48px; margin-bottom:12px; color:#c7d2fe; }

/* Modal form grid */
.form-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:12px; }
.form-group { display:flex; flex-direction:column; gap:6px; }
.form-control { padding:8px; border-radius:8px; border:1px solid rgba(11,42,102,0.06); }

/* Dark mode overrides */
.dark-mode body { background: linear-gradient(180deg, rgba(6,16,34,0.82), rgba(2,6,18,0.9)); color:#dbeafe; }
.dark-mode .glass { background: linear-gradient(180deg, rgba(18,25,42,0.54), rgba(12,18,30,0.6)); border:1px solid rgba(255,255,255,0.03); color:#e6eef6; }
.dark-mode .products-table { background: rgba(10,12,20,0.55); }
.dark-mode .products-table th { background: rgba(255,255,255,0.02); color:#e6eef6; }
.dark-mode .search-box { background: rgba(255,255,255,0.03); }

/* responsive */
@media (max-width:1024px){
  .form-grid { grid-template-columns: 1fr; }
  .products-table { min-width:900px; }
}
@media (max-width:768px){
  .search-section { flex-direction:column; align-items:stretch; }
  .products-table { min-width:720px; }
}
</style>

<div class="aero-sweep" aria-hidden="true"></div> <!-- animated light sweep -->

<div class="admin-shell">
    <div class="product-card glass">
        <div class="card-header">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:12px;">
                <h1 style="display:flex;align-items:center;gap:12px;margin:0;"><i class="fas fa-box"></i> Product Management</h1>
                <div>
                    <button class="btn primary" data-bs-toggle="modal" data-bs-target="#addProductModal"><i class="fas fa-plus"></i> Add Product</button>
                </div>
            </div>
            <p style="margin:6px 0 0 0; opacity:0.9;">Manage your product inventory efficiently</p>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert success" style="margin-bottom:14px; padding:10px; border-radius:8px; background:rgba(230,247,238,0.95); color:#0c6d3b;">
                    <i class="fas fa-check-circle" style="margin-right:8px;"></i>{{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert error" style="margin-bottom:14px; padding:10px; border-radius:8px; background:rgba(255,230,230,0.95); color:#a12a2a;">
                    <i class="fas fa-exclamation-circle" style="margin-right:8px;"></i>{{ session('error') }}
                </div>
            @endif

            <!-- Search / Filters -->
            <div class="search-section">
                <form id="searchForm" method="GET" action="{{ route('admin.products.index') }}" class="search-form" onsubmit="return false;">
                    <div class="search-row">
                        <div class="search-box" role="search" aria-label="product search">
                            <i class="fas fa-search" style="color:#6b7280;"></i>
                            <input type="text" id="liveSearch" name="q" placeholder="Search products by name" value="{{ request('q') }}" aria-label="Search products">
                            <button type="button" id="searchBtn">Search</button>
                        </div>

                        <div class="filter-actions">
                            <select id="statusFilter" name="status" aria-label="Status filter">
                                <option value="">All status</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>

                            <select id="stockFilter" name="stock" aria-label="Stock filter">
                                <option value="">All stock</option>
                                <option value="low">Low stock</option>
                                <option value="normal">Normal</option>
                                <option value="over">Overstock</option>
                            </select>

                            <button class="btn" id="clearFilters" title="Clear filters">Clear</button>

                            <!-- Export + Theme -->
                            <button class="btn" id="exportCSV" title="Export current rows to CSV"><i class="fas fa-file-csv"></i></button>
                           
                            <button class="btn" id="themeToggle" title="Toggle Dark/Light"><i class="fas fa-moon"></i></button>
                        </div>
                    </div>
                </form>

                <div class="results-count" id="resultsCount" style="font-size:13px; margin-top:6px;">{{ $products->total() }} products found</div>
            </div>

            @if($products->count() > 0)
            <div class="table-container" id="tableContainer">
                <table class="products-table" id="productsTable" role="table" aria-describedby="resultsCount">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Extended Price</th>
                            <th>MRP</th>
                            <th>Buying Price</th>
                            <th>Profit</th>
                            <th>Status</th>
                            <th>Quantity</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productsTbody">
                        @foreach ($products as $product)
                            <tr data-id="{{ $product->id }}">
                                <td class="name">{{ $product->name }}</td>
                                <td class="category">{{ $product->category ? $product->category->title : 'N/A' }}</td>
                                <td class="extended-price"><span class="strikethrough">₹{{ $product->price_e }}</span></td>
                                <td class="mrp">₹{{ $product->price_s }}</td>
                                <td class="buying-price">₹{{ $product->price_b }}</td>
                                <td class="profit">₹{{ $product->price_p }}</td>
                                <td class="status">
                                    @php
                                        $statusClass = $product->status == 1 ? 'status-active' : 'status-inactive';
                                        $stockClass = 'stock-normal';
                                        if (isset($product->sellin_quantity)) {
                                            if ($product->sellin_quantity <= 5) $stockClass = 'stock-low';
                                            elseif ($product->sellin_quantity >= 100) $stockClass = 'stock-over';
                                        }
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">{{ $product->status_text }}</span>
                                    <span class="stock-dot {{ $stockClass }}" title="Stock level"></span>
                                </td>
                                <td class="quantity">{{ $product->sellin_quantity ?? 0 }} {{ $product->sellin_quantity_unit ?? '' }}</td>
                                <td class="image">
                                    @if ($product->image)
                                        <img src="{{ asset('uploaded/products/' . $product->image) }}" alt="{{ $product->name }}">
                                    @else
                                        <div class="no-image"><i class="fas fa-image"></i><span style="display:block;font-size:12px;color:rgba(11,42,102,0.45)">No Image</span></div>
                                    @endif
                                </td>
                                <td class="actions">
                                    <label class="switch" style="position:relative;">
                                        <input type="checkbox" class="toggle-status" data-id="{{ $product->id }}" {{ $product->status == 1 ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                        <span class="spinner"></span>
                                    </label>

                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn edit" title="Edit"><i class="fas fa-edit"></i></a>

                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-delete" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn delete" title="Delete"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-container" id="paginationContainer">
                {{ $products->links() }}
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h3>No Products Found</h3>
                <p>There are no products to display at the moment.</p>
                <a href="{{ route('admin.products.create') }}" class="btn primary"><i class="fas fa-plus"></i> Create Your First Product</a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Product Modal (Bootstrap 5) -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content glass">
      <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" id="addProductForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addProductLabel"><i class="fas fa-plus"></i> Add Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-grid">
            <div class="form-group">
              <label for="p_name">Name</label>
              <input type="text" name="name" id="p_name" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="p_category">Category</label>
              <select name="category_id" id="p_category" class="form-control">
                <option value="">Select category</option>
                @foreach(\App\Models\Category::all() as $cat)
                  <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="p_price_s">MRP (price_s)</label>
              <input type="number" name="price_s" id="p_price_s" class="form-control" step="0.01">
            </div>
            <div class="form-group">
              <label for="p_price_e">Extended Price (price_e)</label>
              <input type="number" name="price_e" id="p_price_e" class="form-control" step="0.01">
            </div>
            <div class="form-group">
              <label for="p_price_b">Buying Price (price_b)</label>
              <input type="number" name="price_b" id="p_price_b" class="form-control" step="0.01">
            </div>
            <div class="form-group">
              <label for="p_quantity">Quantity</label>
              <input type="number" name="sellin_quantity" id="p_quantity" class="form-control">
            </div>
            <div class="form-group">
              <label for="p_unit">Quantity Unit</label>
              <input type="text" name="sellin_quantity_unit" id="p_unit" class="form-control">
            </div>
            <div class="form-group">
              <label for="p_image">Image</label>
              <input type="file" name="image" id="p_image" class="form-control">
            </div>
            <div class="form-group">
              <label for="p_status">Status</label>
              <select name="status" id="p_status" class="form-control">
                <option value="1" selected>Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn primary">Create Product</button>
        </div>
      </form>
    </div>
  </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const liveSearch = document.getElementById('liveSearch');
    const searchBtn = document.getElementById('searchBtn');
    const resultsCount = document.getElementById('resultsCount');
    const productsTbody = document.getElementById('productsTbody');
    const paginationContainer = document.getElementById('paginationContainer');
    const tableContainer = document.getElementById('tableContainer');
    const statusFilter = document.getElementById('statusFilter');
    const stockFilter = document.getElementById('stockFilter');
    const clearFilters = document.getElementById('clearFilters');
    const exportCSV = document.getElementById('exportCSV');
    const exportXLS = document.getElementById('exportXLS');
    const themeToggle = document.getElementById('themeToggle');
    const csrfToken = '{{ csrf_token() }}';

    // Debounce util
    function debounce(fn, delay=400){
        let t;
        return function(...args){
            clearTimeout(t);
            t = setTimeout(()=>fn.apply(this,args), delay);
        }
    }

    // Fetch products via AJAX (controller should return JSON when ajax=1)
    async function fetchProductsAjax(q='', status='', stock='') {
        const params = new URLSearchParams({ q, status, stock, ajax: 1 }).toString();
        try {
            const res = await fetch(`{{ route('admin.products.index') }}?${params}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error('Network not ok');
            const data = await res.json();
            if (data.html !== undefined) {
                productsTbody.innerHTML = data.html;
                resultsCount.textContent = `${data.total} products found`;
                if (data.pagination !== undefined) paginationContainer.innerHTML = data.pagination;
                attachRowEventListeners();
            } else {
                console.warn('Unexpected AJAX response', data);
            }
        } catch (err) {
            console.error('AJAX fetch error:', err);
        }
    }

    const debouncedSearch = debounce(function() {
        fetchProductsAjax(liveSearch.value.trim(), statusFilter.value, stockFilter.value);
    }, 500);

    liveSearch && liveSearch.addEventListener('input', debouncedSearch);
    statusFilter && statusFilter.addEventListener('change', debouncedSearch);
    stockFilter && stockFilter.addEventListener('change', debouncedSearch);
    searchBtn && searchBtn.addEventListener('click', debouncedSearch);

    clearFilters && clearFilters.addEventListener('click', function(e){
        e.preventDefault();
        liveSearch.value = '';
        statusFilter.value = '';
        stockFilter.value = '';
        fetchProductsAjax('', '', '');
    });

    // Toggle status (AJAX)
    async function toggleStatusRequest(productId, newStatus) {
        const res = await fetch(`/admin/products/${productId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status: newStatus })
        });
        if (!res.ok) throw new Error('Network error toggling status');
        return await res.json();
    }

    function attachRowEventListeners(){
        document.querySelectorAll('.toggle-status').forEach(checkbox => {
            if (checkbox.dataset.bound) return;
            checkbox.dataset.bound = true;

            checkbox.addEventListener('change', async function() {
                const productId = this.dataset.id;
                const switchEl = this.closest('.switch');
                const isChecked = this.checked;
                switchEl.classList.add('loading');

                try {
                    const data = await toggleStatusRequest(productId, isChecked ? 1 : 0);
                    if (data.success) {
                        const badge = this.closest('tr').querySelector('.status-badge');
                        if (badge) {
                            badge.textContent = data.status_text || (isChecked ? 'Active' : 'Inactive');
                            badge.className = 'status-badge ' + (data.status_class || (isChecked ? 'status-active' : 'status-inactive'));
                        }
                        Swal.fire({ icon:'success', title:'Status Updated', text:`Product is now ${data.status_text || (isChecked ? 'Active' : 'Inactive')}`, timer:1200, showConfirmButton:false });
                    } else {
                        this.checked = !isChecked;
                        Swal.fire({ icon:'error', title:'Error', text: data.message || 'Could not update status' });
                    }
                } catch (err) {
                    console.error(err);
                    this.checked = !isChecked;
                    Swal.fire({ icon:'error', title:'Error', text:'Network error updating status' });
                } finally {
                    switchEl.classList.remove('loading');
                }
            });
        });

        // Confirm delete
        document.querySelectorAll('.inline-delete').forEach(form => {
            if (form.dataset.boundDelete) return;
            form.dataset.boundDelete = true;
            form.addEventListener('submit', function(e){
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete the product.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    }

    // initial attach
    attachRowEventListeners();

    // Handle AJAX pagination (links)
    document.addEventListener('click', function(e) {
        const el = e.target.closest('.pagination a');
        if (!el) return;
        e.preventDefault();
        const href = el.getAttribute('href');
        const url = new URL(href, window.location.origin);
        const q = url.searchParams.get('q') || liveSearch.value;
        const status = url.searchParams.get('status') || statusFilter.value;
        const stock = url.searchParams.get('stock') || stockFilter.value;
        fetchProductsAjax(q, status, stock);
    });

    // Export CSV (client-side from current table rows)
    exportCSV && exportCSV.addEventListener('click', function() {
        const rows = Array.from(document.querySelectorAll("#productsTbody tr"));
        if (rows.length === 0) { Swal.fire({icon:'info', title:'No rows', text:'No products to export.'}); return; }

        const csvRows = [];
        // header
        csvRows.push(['Name','Category','Extended Price','MRP','Buying Price','Profit','Status','Quantity'].join(','));
        rows.forEach(r => {
            const cols = Array.from(r.querySelectorAll('td')).slice(0,8).map(td => `"${td.innerText.replace(/"/g,'""').trim()}"`);
            csvRows.push(cols.join(','));
        });

        const blob = new Blob([csvRows.join('\n')], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `products_export_${new Date().toISOString().slice(0,10)}.csv`;
        a.click();
        URL.revokeObjectURL(url);
    });

    

    // Theme toggle (persist)
    function applyTheme(isDark) {
        if (isDark) {
            document.documentElement.classList.add('dark-mode');
            localStorage.setItem('adminTheme','dark');
        } else {
            document.documentElement.classList.remove('dark-mode');
            localStorage.setItem('adminTheme','light');
        }
    }
    const savedTheme = localStorage.getItem('adminTheme') || 'light';
    applyTheme(savedTheme === 'dark');

    themeToggle && themeToggle.addEventListener('click', function(){
        const isDark = document.documentElement.classList.toggle('dark-mode');
        applyTheme(isDark);
    });

    // Add product form UX - nothing special; server handles creation
});
</script>
@endsection

@endsection
