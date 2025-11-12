<div class="form-group mb-3">
    <label for="title" class="form-label fw-bold">Title</label>
    <input 
        type="text" 
        name="title" 
        id="title" 
        class="form-control form-control-lg rounded-3 shadow-sm" 
        placeholder="Enter category title"
        value="{{ old('title', isset($category) ? $category->title : '') }}" 
        required
    >
</div>

<div class="form-group mb-3">
    <label for="description" class="form-label fw-bold">Description</label>
    <textarea 
        name="description" 
        id="description" 
        class="form-control form-control-lg rounded-3 shadow-sm" 
        rows="4"
        placeholder="Write category description..."
    >{{ old('description', isset($category) ? $category->description : '') }}</textarea>
</div>

<div class="form-group mb-3">
    <label for="image" class="form-label fw-bold">Image</label>
    <input 
        type="file" 
        name="image" 
        id="image" 
        class="form-control form-control-lg rounded-3 shadow-sm"
    >
    @if (isset($category) && $category->image)
        <div class="mt-3">
            <img 
                src="{{ asset('category/' . $category->image) }}" 
                alt="{{ $category->title }}" 
                class="img-thumbnail rounded shadow-sm"
                width="120"
            >
        </div>
    @endif
</div>

<div class="form-group mb-3">
    <label for="slug" class="form-label fw-bold">Slug (optional)</label>
    <input 
        type="text" 
        name="slug" 
        id="slug" 
        class="form-control form-control-lg rounded-3 shadow-sm" 
        placeholder="Auto-generated if left empty"
        value="{{ old('slug', isset($category) ? $category->slug : '') }}"
    >
</div>
