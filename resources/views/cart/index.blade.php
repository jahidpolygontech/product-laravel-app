<x-layout>
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-4xl font-bold text-blue-600 mb-8">Shopping Cart</h1>

        {{-- Empty cart --}}
        @if(empty($cartItems))
            <x-cart.empty-cart />
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Cart Items --}}
                <x-cart.cart-items-table :cartItems="$cartItems" />

                {{-- Order Summary --}}
                <x-cart.order-summary :total="$total" />
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
