<x-layout>
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-4xl font-bold text-blue-600">Products</h1>
            <a href="{{ route('products.create') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                New Product
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
                <a href="{{ route('products.show', $product->id) }}">
                    <div class="bg-white shadow-md rounded-lg p-5 hover:shadow-xl transition">
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $product->name }}
                        </h2>
                        <p class="text-gray-600 mb-3">{{ $product->description }}</p>
                        <p class="text-gray-500 font-medium">Size: {{ $product->size }}</p>

                    </div>
                </a>
            @endforeach
        </div>


        <div class="mt-6">
            {{ $products->links('/vendor/pagination/simple-default') }}
        </div>
    </div>
</x-layout>
