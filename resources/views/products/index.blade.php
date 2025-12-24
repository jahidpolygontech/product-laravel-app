<x-layout>
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-blue-600">Products</h1>
            <a href="{{ route('products.create') }}"
               class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-md">
                + New Product
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
                <a href="{{ route('products.show', $product->id) }}" class="block">
                    <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-xl transition transform hover:scale-105 duration-300 h-full flex flex-col">
                        <!-- Product Image Placeholder -->
                        <div class="bg-gradient-to-br from-blue-100 to-blue-200 h-48 flex items-center justify-center">
                            <div class="text-6xl">📦</div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-6 flex-1 flex flex-col">
                            <h2 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">{{ $product->name }}</h2>

                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $product->description ?? 'No description' }}</p>

                            <div class="mt-auto space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 text-sm font-semibold">Price:</span>
                                    <span class="text-2xl font-bold text-green-600">${{ number_format($product->price, 2) }}</span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 text-sm font-semibold">Size:</span>
                                    <span class="text-gray-700 font-medium">{{ $product->size }}</span>
                                </div>


                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->links('/vendor/pagination/simple-default') }}
        </div>
    </div>
</x-layout>
