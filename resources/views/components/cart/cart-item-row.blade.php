<tr class="border-b">

    {{-- Product --}}
    <td class="px-6 py-4">
        <a href="{{ route('products.show', $item['product']->id) }}"
           class="text-blue-600 hover:underline font-medium">
            {{ $item['product']->name }}
        </a>
    </td>

    {{-- Price --}}
    <td class="px-6 py-4 text-gray-800">
        ${{ number_format($item['price'], 2) }}
    </td>

    {{-- Quantity --}}
    <td class="px-6 py-4">
        <form method="POST"
              action="{{ route('cart.update', $item['id']) }}"
              class="flex items-center gap-2">
            @csrf
            @method('PATCH')

            <div class="flex items-center border-2 border-gray-300 rounded overflow-hidden">
                <button type="button"
                        onclick="decreaseCartQuantity(this)"
                        class="px-3 py-1 bg-gray-200 hover:bg-gray-300 font-bold">
                    −
                </button>
                <input type="number"
                       name="quantity"
                       value="{{ $item['quantity'] }}"
                       min="1"
                       class="w-12 py-1 border-0 text-center font-semibold focus:outline-none"
                       required>
                <button type="button"
                        onclick="increaseCartQuantity(this)"
                        class="px-3 py-1 bg-gray-200 hover:bg-gray-300 font-bold">
                    +
                </button>
            </div>

            <button type="submit"
                    class="ml-2 px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                Update
            </button>
        </form>
    </td>

    {{-- Subtotal --}}
    <td class="px-6 py-4 text-gray-800">
        ${{ number_format($item['subtotal'], 2) }}
    </td>

    {{-- Remove --}}
    <td class="px-6 py-4">
        <form method="POST"
              action="{{ route('cart.remove', $item['id']) }}"
              onsubmit="return confirm('Are you sure?');">
            @csrf
            @method('DELETE')

            <button type="submit"
                    class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition">
                Remove
            </button>
        </form>
    </td>

</tr>

