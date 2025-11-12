<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accountant Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-md p-8 w-full max-w-md">
            <h1 class="text-2xl font-bold mb-6 text-center">Accountant Login</h1>
            @if (session('error'))
                <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <form method="POST" action="{{ route('accountant.login') }}">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="mt-1 block w-full border rounded p-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('email') }}"
                        required
                    >
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="mt-1 block w-full border rounded p-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                    >
                </div>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded font-semibold transition">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>