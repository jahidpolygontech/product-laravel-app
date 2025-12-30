<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>JIH Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
{{-- Navigation Bar --}}
<nav class="bg-blue-600 text-white shadow-md">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <a href="{{ route('products.index') }}" class="text-2xl font-bold">Store</a>

        <div class="flex space-x-6 items-center">
            <a href="{{ route('products.index') }}" class="hover:text-blue-100 transition">
                Products
            </a>

            <a href="{{ route('cart.index') }}" class="hover:text-blue-100 transition">
                Cart
            </a>
        </div>
    </div>
</nav>


{{-- Flash Status Message --}}
@if (session('status'))
    <div id="flash-message"
         class="max-w-7xl mx-auto my-4 p-4 border rounded-lg shadow-md
             {{ str_contains(session('status'), 'deleted')
                ? 'bg-red-100 border-red-400 text-red-800'
                : 'bg-green-100 border-green-400 text-green-800' }}
             transition-opacity duration-500">
        {{ session('status') }}
    </div>

    <script>
        setTimeout(() => {
            const message = document.getElementById('flash-message');
            if (message) {
                message.classList.add('opacity-0');
                setTimeout(() => message.remove(), 500);
            }
        }, 3000);
    </script>
@endif

{{-- Page Content --}}
<main class="max-w-7xl mx-auto p-6">
    {{ $slot }}
</main>
</body>
</html>
