<x-layout>
    <div class="max-w-2xl mx-auto p-6 bg-gray-300 shadow-md rounded-lg mt-8">
        <h1 class="text-3xl font-bold text-blue-600 mb-6">Edit Product</h1>
        <x-errors/>
        <form method="post" action="{{route('products.update',$product)}}" class="space-y-4">
            @method('PATCH')
            <x-products.form :product="$product"/>
        </form>
    </div>
</x-layout>
