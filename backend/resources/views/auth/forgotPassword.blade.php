<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col items-center justify-center bg-gray-50">

    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h1 class="text-xl font-bold mb-2">Forgot Password</h1>
        <p class="text-gray-500 mb-6">
            Enter your email address to reset your password
        </p>

        <form method="POST" action="">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email" required autofocus placeholder="email@example.com"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-[0.2rem] ring-purple-600 outline-gray-200">
            </div>

            <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded hover:bg-purple-700 transition">
                Send Instructions
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-500">
            Do you remember your password?
            <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-500">
                Sign in
            </a>
        </p>
    </div>
</body>

</html>
