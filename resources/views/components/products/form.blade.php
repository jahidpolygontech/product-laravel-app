@csrf <!-- Include CSRF token -->

<div>
    <label for="name" class="block text-gray-700 font-medium mb-1">Name</label>

    <input
        type="text"
        name="name"
        id="name"
        value="{{ old('name', $product->name ?? '') }}"
        class="w-full border border-gray-300 rounded px-3 py-2
               focus:outline-none focus:ring-2 focus:ring-blue-400
               @error('name') border-red-500 @enderror"
    >

    @error('name')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>


<div>
    <label for="description" class="block text-gray-700 font-medium mb-1">Description</label>

    <textarea
        name="description"
        id="description"
        rows="4"
        class="w-full border border-gray-300 rounded px-3 py-2
               focus:outline-none focus:ring-2 focus:ring-blue-400
               @error('description') border-red-500 @enderror"
    >{{ old('description', $product->description ?? '') }}</textarea>

    @error('description')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>


<div>
    <label for="price" class="block text-gray-700 font-medium mb-1">Price</label>

    <input
        type="number"
        name="price"
        id="price"
        value="{{ old('price', $product->price ?? '') }}"
        class="w-full border border-gray-300 rounded px-3 py-2
               focus:outline-none focus:ring-2 focus:ring-blue-400
               @error('price') border-red-500 @enderror"
    >

    @error('price')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>


<div>
    <label for="size" class="block text-gray-700 font-medium mb-1">Size</label>

    <input
        type="text"
        name="size"
        id="size"
        value="{{ old('size', $product->size ?? '') }}"
        class="w-full border border-gray-300 rounded px-3 py-2
               focus:outline-none focus:ring-2 focus:ring-blue-400
               @error('size') border-red-500 @enderror"
    >

    @error('size')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>


<button type="submit"
        class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
    Save
</button>
