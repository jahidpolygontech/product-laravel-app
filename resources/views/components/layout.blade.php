<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
@if(session('status'))
    <div class="max-w-7xl mx-auto my-4 p-4
        {{ str_contains(session('status'), 'deleted') ? 'bg-red-100 border-red-400 text-red-800' : 'bg-green-100 border-green-400 text-green-800' }}
        border rounded-lg shadow-md">
        {{ session('status') }}
    </div>
@endif


  {{$slot}}
</body>
</html>
