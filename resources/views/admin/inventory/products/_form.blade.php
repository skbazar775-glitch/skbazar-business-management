<div class="space-y-4">
    <!-- Category -->
    <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
        <select name="category_id" id="category_id" required
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
            <option value="">Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', isset($product) ? $product->category_id : '') == $category->id ? 'selected' : '' }}>
                    {{ $category->title }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Name -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" name="name" id="name" required
            value="{{ old('name', isset($product) ? $product->name : '') }}"
            placeholder="Enter product name"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-300 focus:ring-opacity-50 p-2">
    </div>

    <!-- Description -->
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" id="description" rows="3"
            placeholder="Enter product description"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-300 focus:ring-opacity-50 p-2">{{ old('description', isset($product) ? $product->description : '') }}</textarea>
    </div>

    <!-- Image -->
    <div>
        <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
        <input type="file" name="image" id="image"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-300 focus:ring-opacity-50 p-2">
        @if (isset($product) && $product->image)
            <img src="{{ asset('uploaded/products/' . $product->image) }}" alt="{{ $product->name }}"
                 class="mt-2 w-24 h-24 object-cover rounded-lg border">
        @endif
    </div>

    <!-- Prices -->
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="price_e" class="block text-sm font-medium text-gray-700">Extended Price</label>
            <input type="number" name="price_e" id="price_e" step="0.01"
                value="{{ old('price_e', isset($product) ? $product->price_e : '') }}"
                placeholder="0.00"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-300 focus:ring-opacity-50 p-2">
        </div>

        <div>
            <label for="price_s" class="block text-sm font-medium text-gray-700">MRP Price</label>
            <input type="number" name="price_s" id="price_s" step="0.01" required
                value="{{ old('price_s', isset($product) ? $product->price_s : '') }}"
                placeholder="0.00"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-300 focus:ring-opacity-50 p-2">
        </div>

        <div>
            <label for="price_b" class="block text-sm font-medium text-gray-700">Buy Price</label>
            <input type="number" name="price_b" id="price_b" step="0.01" required
                value="{{ old('price_b', isset($product) ? $product->price_b : '') }}"
                placeholder="0.00"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-300 focus:ring-opacity-50 p-2">
        </div>

        <div>
            <label for="price_p" class="block text-sm font-medium text-gray-700">Profit Price</label>
            <input type="number" name="price_p" id="price_p" step="0.01" readonly
                value="{{ old('price_p', isset($product) ? $product->price_p : '') }}"
                class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-100 shadow-sm p-2">
        </div>
    </div>

    <!-- Status -->
    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status" id="status" required
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
            <option value="0" {{ old('status', isset($product) ? $product->status : '') == 0 ? 'selected' : '' }}>Active</option>
            <option value="1" {{ old('status', isset($product) ? $product->status : '') == 1 ? 'selected' : '' }}>Inactive</option>
            <option value="2" {{ old('status', isset($product) ? $product->status : '') == 2 ? 'selected' : '' }}>Out of Stock</option>
            <option value="3" {{ old('status', isset($product) ? $product->status : '') == 3 ? 'selected' : '' }}>Bestseller</option>
            <option value="4" {{ old('status', isset($product) ? $product->status : '') == 4 ? 'selected' : '' }}>Offer</option>
            <option value="5" {{ old('status', isset($product) ? $product->status : '') == 5 ? 'selected' : '' }}>New</option>
        </select>
    </div>

    <!-- Selling Quantity -->
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="sellin_quantity" class="block text-sm font-medium text-gray-700">Selling Quantity</label>
            <input type="number" name="sellin_quantity" id="sellin_quantity" step="0.01"
                value="{{ old('sellin_quantity', isset($product) ? $product->sellin_quantity : '') }}"
                placeholder="0.00"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-300 focus:ring-opacity-50 p-2">
        </div>

        <div>
            <label for="sellin_quantity_unit" class="block text-sm font-medium text-gray-700">Quantity Unit</label>
            <select name="sellin_quantity_unit" id="sellin_quantity_unit" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                @foreach (['g', 'kg', 'lit', 'ml', 'piece', 'inch', 'm', 'dozen', 'packet', 'box', 'unit'] as $unit)
                    <option value="{{ $unit }}" {{ old('sellin_quantity_unit', isset($product) ? $product->sellin_quantity_unit : 'piece') == $unit ? 'selected' : '' }}>
                        {{ ucfirst($unit) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

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
            calculateProfit(); // Initial calculation on page load
        });
    </script>
@endsection
