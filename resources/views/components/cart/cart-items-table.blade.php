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
                <x-cart.cart-item-row :item="$item" />
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

