<x-layout>
    <div class="max-w-7xl mx-auto p-6">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-blue-600">Products</h1>

            <a href="{{ route('products.create') }}"
               class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-md">
                + New Product
            </a>
        </div>

        {{-- Top 3 Products --}}
        <div class="mb-12 p-8 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-lg border-2 border-orange-200">
            <h2 class="text-3xl font-bold text-orange-600 mb-6 flex items-center">
                <span class="mr-2">🏆</span> Top 3 Best Sellers
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($topProducts as $index => $product)
                    <a href="{{ route('products.show', $product->id) }}" class="block">
                        <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-2xl transition transform hover:scale-105 duration-300 h-full flex flex-col relative">

                            {{-- Rank Badge --}}
                            <div class="absolute top-3 right-3 bg-gradient-to-r from-orange-500 to-yellow-500 text-white rounded-full w-12 h-12 flex items-center justify-center font-bold text-lg">
                                #{{ $index + 1 }}
                            </div>

                            {{-- Image --}}
                            <div class="bg-gradient-to-br from-orange-100 to-yellow-100 h-48 flex items-center justify-center">
                                <div class="text-6xl">📦</div>
                            </div>

                            {{-- Info --}}
                            <div class="p-6 flex-1 flex flex-col">
                                <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">
                                    {{ $product->name }}
                                </h3>

                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ $product->description ?? 'No description' }}
                                </p>

                                <div class="mt-auto space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-sm font-semibold text-gray-600">Price</span>
                                        <span class="text-2xl font-bold text-green-600">
                                            ${{ number_format($product->price, 2) }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-sm font-semibold text-gray-600">Size</span>
                                        <span class="font-medium text-gray-700">
                                            {{ $product->size }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between bg-orange-50 p-2 rounded">
                                        <span class="text-sm font-semibold text-gray-600">Units Sold</span>
                                        <span class="text-lg font-bold text-orange-600">
                                            {{ $product->total_quantity ?? 0 }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="col-span-3 text-center text-gray-500">
                        No top-selling products yet.
                    </p>
                @endforelse
            </div>
        </div>

        {{-- All Products --}}
        <h2 class="text-3xl font-bold text-gray-800 mb-6">All Products</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($products as $product)
                <a href="{{ route('products.show', $product->id) }}" class="block">
                    <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-xl transition transform hover:scale-105 duration-300 h-full flex flex-col">

                        {{-- Image --}}
                        <div class="bg-gradient-to-br from-blue-100 to-blue-200 h-48 flex items-center justify-center">
                            <div class="text-6xl">📦</div>
                        </div>

                        {{-- Info --}}
                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">
                                {{ $product->name }}
                            </h3>

                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ $product->description ?? 'No description' }}
                            </p>

                            <div class="mt-auto space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-semibold text-gray-600">Price</span>
                                    <span class="text-2xl font-bold text-green-600">
                                        ${{ number_format($product->price, 2) }}
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-sm font-semibold text-gray-600">Size</span>
                                    <span class="font-medium text-gray-700">
                                        {{ $product->size }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <p class="col-span-3 text-center text-gray-500">
                    No products available.
                </p>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $products->links('/vendor/pagination/simple-default') }}
        </div>

    </div>
</x-layout>
