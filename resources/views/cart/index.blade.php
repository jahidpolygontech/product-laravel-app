<x-layout>
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-4xl font-bold text-blue-600 mb-8">Shopping Cart</h1>

        {{-- Empty cart --}}
        @if(empty($cartItems))
            <div class="bg-white shadow-md rounded-lg p-8 text-center">
                <p class="text-gray-600 text-lg mb-4">Your cart is empty</p>
                <a href="{{ route('products.index') }}"
                   class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Continue Shopping
                </a>.
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Cart Items --}}
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Product</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Price</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Quantity</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Subtotal</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cartItems as $item)
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
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Clear cart --}}
                    <div class="mt-4">
                        <form method="POST"
                              action="{{ route('cart.clear') }}"
                              onsubmit="return confirm('This will clear your entire cart. Are you sure?');">
                            @csrf
                            <button type="submit"
                                    class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                                Clear Cart
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="bg-white shadow-md rounded-lg p-6 h-fit">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Order Summary</h2>

                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-gray-700">
                            <span>Subtotal:</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>Shipping:</span>
                            <span>$0.00</span>
                        </div>
                        <div class="border-t pt-4 flex justify-between text-lg font-bold text-gray-900">
                            <span>Total:</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout') }}"
                       class="block w-full mb-3 px-4 py-3 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition text-center">
                        Proceed to Checkout
                    </a>

                    <a href="{{ route('products.index') }}"
                       class="block w-full px-4 py-3 bg-gray-200 text-gray-800 font-semibold rounded hover:bg-gray-300 transition text-center">
                        Continue Shopping
                    </a>
                </div>

            </div>
        @endif
    </div>

    <script>
        function increaseCartQuantity(button) {
            const input = button.parentElement.querySelector('input[type="number"]');
            const currentValue = parseInt(input.value);
            input.value = currentValue + 1;
        }

        function decreaseCartQuantity(button) {
            const input = button.parentElement.querySelector('input[type="number"]');
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        }
    </script>
</x-layout>
