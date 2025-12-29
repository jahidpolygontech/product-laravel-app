<x-layout>
    <section class="min-h-screen flex items-center justify-center">
        <div class="bg-white shadow-lg rounded-xl p-10 text-center w-full max-w-md">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                Welcome 👋
            </h1>

            <p class="text-gray-600 mb-8">
                Explore our products and find what you need.
            </p>

            <a href="{{ route('products.index') }}"
               class="inline-block px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg
                      hover:bg-blue-700 transition">
                View Products
            </a>
        </div>
    </section>
</x-layout>
