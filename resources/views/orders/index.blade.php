<x-layout>
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-blue-600">Order History</h1>
            <a href="{{ route('products.index') }}"
               class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-md">
                ← Back to Products
            </a>
        </div>

        @if($orders->count() > 0)
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 border-b border-blue-200">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-800">Order #{{ $order->id }}</h2>
                                    <p class="text-gray-600 text-sm mt-2">
                                        <strong>Date:</strong> {{ $order->created_at->format('F j, Y g:i A') }}
                                    </p>
                                    <p class="text-gray-600 text-sm">
                                        <strong>Address:</strong> {{ $order->shipping_address }}
                                    </p>
                                    <p class="text-gray-600 text-sm">
                                        <strong>Phone:</strong> {{ $order->phone }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-gray-600 text-sm mb-2">Total Amount</p>
                                    <p class="text-3xl font-bold text-green-600">${{ number_format($order->total_amount, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Order Items</h3>
                            <div class="space-y-4">
                                @foreach($order->items as $item)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                                        <div class="flex items-center space-x-4 flex-1">
                                            <div class="text-4xl">📦</div>
                                            <div>
                                                <a href="{{ route('products.show', $item->product_id) }}" class="text-lg font-semibold text-blue-600 hover:underline">
                                                    {{ $item->product->name }}
                                                </a>
                                                <p class="text-gray-600 text-sm">{{ $item->product->description ?? 'No description' }}</p>
                                                <p class="text-gray-600 text-sm mt-1">
                                                    <strong>Size:</strong> {{ $item->product->size }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <div class="text-sm text-gray-600 mb-1">
                                                <strong>Price:</strong> ${{ number_format($item->price, 2) }}
                                            </div>
                                            <div class="text-sm text-gray-600 mb-1">
                                                <strong>Quantity:</strong> {{ $item->quantity }}
                                            </div>
                                            <div class="text-lg font-bold text-green-600">
                                                ${{ number_format($item->price * $item->quantity, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Order Details Footer -->
                        <div class="bg-gray-50 p-6 border-t border-gray-200">
                            <div class="flex justify-end">
                                <a href="{{ route('orders.show', $order->id) }}"
                                   class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->links('/vendor/pagination/simple-default') }}
            </div>
        @else
            <div class="bg-white shadow-lg rounded-lg p-12 text-center">
                <div class="text-6xl mb-4">📦</div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">No Orders Yet</h2>
                <p class="text-gray-600 mb-6">You haven't placed any orders yet. Start shopping now!</p>
                <a href="{{ route('products.index') }}"
                   class="inline-block px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Start Shopping
                </a>
            </div>
        @endif
    </div>
</x-layout>

