<x-layout>
    <div class="max-w-5xl mx-auto p-6 mt-10">
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8">

                <!-- Product Image Section -->
                <div class="flex items-center justify-center bg-gray-100 rounded-lg p-8">
                    <div class="text-center">
                        <div class="text-6xl text-gray-400 mb-4">📦</div>
                        <p class="text-gray-600">{{ $product->name }}</p>
                    </div>
                </div>

                <!-- Product Details Section -->
                <div>
                    <h1 class="text-4xl font-bold text-blue-600 mb-4">{{ $product->name }}</h1>

                    <!-- Price -->
                    <div class="mb-6">
                        <p class="text-gray-600 text-sm font-semibold mb-2">PRICE</p>
                        <p class="text-4xl font-bold text-green-600">${{ number_format($product->price, 2) }}</p>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <p class="text-gray-600 text-sm font-semibold mb-2">DESCRIPTION</p>
                        <p class="text-gray-700 text-lg leading-relaxed">{{ $product->description ?? 'No description available' }}</p>
                    </div>

                    <!-- Size -->
                    <div class="mb-8">
                        <p class="text-gray-600 text-sm font-semibold mb-2">SIZE</p>
                        <p class="text-gray-700 text-lg">{{ $product->size ?? 'N/A' }}</p>
                    </div>

                    <!-- Quantity Selector with +/- Buttons -->
                    <div class="mb-6">
                        <p class="text-gray-600 text-sm font-semibold mb-3">QUANTITY</p>
                        <div class="flex items-center space-x-4">
                            <!-- Quantity Control -->
                            <div class="flex items-center border-2 border-gray-300 rounded-lg overflow-hidden">
                                <button type="button" onclick="decreaseQuantity()"
                                        class="px-4 py-3 bg-gray-200 hover:bg-gray-300 font-bold text-lg">
                                    −
                                </button>
                                <input type="number"
                                       id="quantity"
                                       name="quantity"
                                       value="1"
                                       min="1"
                                       max="100"
                                       class="w-16 px-4 py-3 border-0 text-lg font-semibold text-center focus:outline-none"
                                       required>
                                <button type="button" onclick="increaseQuantity()"
                                        class="px-4 py-3 bg-gray-200 hover:bg-gray-300 font-bold text-lg">
                                    +
                                </button>
                            </div>
                            <span class="text-gray-600">Available in stock</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 mb-6">
                        <!-- Add to Cart Form -->
                        <form method="POST" action="{{ route('cart.add', $product) }}" class="flex-1">
                            @csrf
                            <input type="hidden" id="quantityInput" name="quantity" value="1">
                            <button type="submit" class="w-full px-8 py-4 bg-green-600 text-white text-lg font-bold rounded-lg hover:bg-green-700 transition duration-300 shadow-md"
                                    onclick="document.getElementById('quantityInput').value = document.getElementById('quantity').value;">
                                🛒 Add to Cart
                            </button>
                        </form>

                        <!-- Buy Now Button -->
                        <form method="POST" action="{{ route('cart.add', $product) }}" class="flex-1">
                            @csrf
                            <input type="hidden" id="quantityInput2" name="quantity" value="1">
                            <button type="submit" name="buy_now" value="true" class="w-full px-8 py-4 bg-blue-600 text-white text-lg font-bold rounded-lg hover:bg-blue-700 transition duration-300 shadow-md"
                                    onclick="document.getElementById('quantityInput2').value = document.getElementById('quantity').value;">
                                Buy Now
                            </button>
                        </form>
                    </div>

                    <!-- Back to Products Link -->
                    <a href="{{ route('products.index') }}"
                       class="block w-full px-8 py-3 bg-gray-200 text-gray-800 text-center font-semibold rounded-lg hover:bg-gray-300 transition duration-300">
                        Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Admin Actions (Edit/Delete) -->
            <div class="border-t bg-gray-50 p-6">
                <div class="flex gap-4">
                    <a href="{{ route('products.edit', $product->id) }}"
                       class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Edit Product
                    </a>

                    <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('Are you sure you want to delete this product?')"
                                class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            Delete Product
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize quantity from localStorage on page load
        document.addEventListener('DOMContentLoaded', function() {
            const productId = {{ $product->id }};
            const savedQuantity = localStorage.getItem(`product_${productId}_quantity`);

            if (savedQuantity) {
                document.getElementById('quantity').value = parseInt(savedQuantity);
            }
        });

        function increaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            const currentValue = parseInt(quantityInput.value) || 1;
            const newValue = currentValue + 1;
            quantityInput.value = newValue;
            saveQuantity();
        }

        function decreaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            const currentValue = parseInt(quantityInput.value) || 1;
            if (currentValue > 1) {
                const newValue = currentValue - 1;
                quantityInput.value = newValue;
                saveQuantity();
            }
        }

        function saveQuantity() {
            const productId = {{ $product->id }};
            const quantity = document.getElementById('quantity').value;
            localStorage.setItem(`product_${productId}_quantity`, quantity);
        }

        // Save quantity when user manually types
        document.getElementById('quantity').addEventListener('change', saveQuantity);
    </script>
</x-layout>
