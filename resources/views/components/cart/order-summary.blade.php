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

