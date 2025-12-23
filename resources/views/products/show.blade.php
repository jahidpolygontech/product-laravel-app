<x-layout>
    <div class="max-w-3xl mx-auto p-6 bg-white shadow-lg rounded-xl mt-10">

        <h1 class="text-3xl font-bold text-blue-600 mb-6">{{ $product->name }}</h1>

        <p class="text-gray-800 mb-4">
            <span class="font-semibold">Description:</span> {{ $product->description ?? 'N/A' }}
        </p>

        <p class="text-gray-800 mb-6">
            <span class="font-semibold">Size:</span> {{ $product->size ?? 'N/A' }}
        </p>

        <div class="flex space-x-4">

            <a href="{{ route('products.edit', $product->id) }}"
               class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Edit Product
            </a>

            <form method="POST" action="{{ route('products.destroy', $product) }}">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Delete
                </button>
            </form>
        </div>
    </div>
</x-layout>
