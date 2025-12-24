<x-layout>
    <div class="max-w-2xl mx-auto p-6 mt-16">
        <div class="bg-white shadow-lg rounded-lg p-8 text-center">
            <!-- Success Icon -->
            <div class="mb-6">
                <div class="inline-block bg-green-100 rounded-full p-4">
                    <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Success Message -->
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Order Placed Successfully!</h1>

            <p class="text-xl text-gray-600 mb-8">
                Thank you for your purchase. Your order has been confirmed and will be shipped soon.
            </p>

            <!-- Order Details -->
            <div class="bg-blue-50 rounded-lg p-6 mb-8 text-left">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Order Details</h2>
                <div class="space-y-3 text-gray-700">
                    <div class="flex justify-between">
                        <span>Order Number:</span>
                        <span class="font-semibold">#{{ time() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Order Date:</span>
                        <span class="font-semibold">{{ now()->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Status:</span>
                        <span class="font-semibold text-green-600">Confirmed</span>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4">What's Next?</h3>
                <p class="text-gray-600 mb-4">
                    You will receive a confirmation email shortly with tracking information. You can check your order status anytime from your account.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 justify-center">
                <a href="{{ route('products.index') }}"
                   class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Continue Shopping
                </a>
                <a href="{{ route('cart.index') }}"
                   class="px-8 py-3 bg-gray-400 text-white font-semibold rounded-lg hover:bg-gray-500 transition">
                    Back to Cart
                </a>
            </div>
        </div>
    </div>
</x-layout>

