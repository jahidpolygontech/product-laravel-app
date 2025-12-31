<x-layout>
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-4xl font-bold text-blue-600 mb-8">Checkout</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Checkout Form -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-md rounded-lg p-8">
                    <form method="POST" action="{{ route('checkout.process') }}">
                        @csrf

                        @if(!$user)
                            <!-- Guest Information -->
                            <div class="mb-6">
                                <h2 class="text-2xl font-bold text-gray-800 mb-4">Your Information</h2>

                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600"
                                           required>
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600"
                                           required>
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <!-- Logged in user info -->
                            <div class="mb-6">
                                <h2 class="text-2xl font-bold text-gray-800 mb-4">Your Information</h2>
                                <p class="text-gray-700 mb-2"><span class="font-semibold">Name:</span> {{ $user->name }}</p>
                                <p class="text-gray-700"><span class="font-semibold">Email:</span> {{ $user->email }}</p>
                            </div>
                        @endif

                        <!-- Shipping Address -->
                        <div class="mb-6 pt-6 border-t">
                            <h2 class="text-2xl font-bold text-gray-800 mb-4">Shipping Address</h2>

                            <div class="mb-4">
                                <label for="shipping_address" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Address <span class="text-red-500">*</span>
                                </label>
                                <textarea id="shipping_address" name="shipping_address" rows="4"
                                          class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600"
                                          required>{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-600"
                                       required>
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-4 pt-6 border-t">
                            <a href="{{ route('cart.index') }}"
                               class="flex-1 px-4 py-3 bg-gray-400 text-white font-semibold rounded hover:bg-gray-500 transition text-center">
                                Back to Cart
                            </a>
                            <button type="submit"
                                    class="flex-1 px-4 py-3 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition">
                                Place Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-white shadow-md rounded-lg p-6 h-fit">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Order Summary</h2>

                <div class="space-y-4 mb-6">
                    @foreach($cartItems as $item)
                        <div class="border-b pb-4">
                            <div class="flex justify-between text-gray-700 mb-2">
                                <span class="font-semibold">{{ $item->product_name }}</span>
                                <span>${{ number_format($item->price, 2) }}</span>
                            </div>
                            <div class="text-sm text-gray-600">
                                Qty: {{ $item->quantity }}
                            </div>
                        </div>
                    @endforeach
                </div>

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
            </div>
        </div>
    </div>
</x-layout>

