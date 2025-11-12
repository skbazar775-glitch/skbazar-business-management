{{-- <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Top Categories</h2>

    <div class="grid grid-cols-2 gap-4 mb-6">
        @foreach ($categories as $category)
            <div class="bg-blue-50 p-4 rounded-lg">
                <p class="text-xs text-blue-600 font-medium mb-1">Title</p>
                <p class="text-xl font-bold text-blue-800">{{ $category->title }}</p>
                <p class="text-xs text-blue-600 mt-1">Slug: {{ $category->slug }}</p>
            </div>
        @endforeach
    </div>

    <div class="space-y-3">
        @foreach ($categories as $category)
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-600 font-semibold">{{ $category->title }}</p>
                    <p class="text-xs text-gray-500">{{ Str::limit($category->description, 60) }}</p>
                </div>
                <span class="text-xs text-gray-400">{{ $category->created_at->format('d M Y') }}</span>
            </div>
        @endforeach
    </div>

    <div class="mt-6 pt-4 border-t border-gray-100">
        <a href="{{ route('admin.category.index') }}" class="inline-block text-blue-500 text-sm font-medium">View All Categories →</a>
    </div>
</div> --}}
