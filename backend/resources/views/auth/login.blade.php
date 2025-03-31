<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

</head>
<body class="min-h-screen flex flex-col items-center justify-center bg-gray-50">

<div data-aos="fade-up"  class="bg-white p-8 rounded shadow-md w-full max-w-md mx-4">
    <h1 class="text-2xl text-center font-bold mb-2">Login</h1>
    <p class="text-gray-500 mb-6 text-center">
        Enter your credentials to access your account
    </p>

    <form method="POST" action="">
        @csrf


        <div class="flex gap-2 mb-4">
            <button type="button" class="flex-1 border py-2 px-3 text-center rounded">
                Facebook
            </button>
            <button type="button" class="flex-1 border py-2 px-3 text-center rounded">
                Google
            </button>
        </div>

        <div class="flex items-center my-4">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="mx-2 text-[0.8em] text-gray-500">OR CONTINUE WITH</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>


        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" id="email" required autofocus placeholder="email@gmail.com"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 ring-purple-600 outline-none">
        </div>

        <div class="mb-4">
            <div class="flex justify-between">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <a href="{{ route('forgot-password') }}" class="text-sm text-purple-600">Forgot Password?</a>
            </div>
            <input type="password" name="password" id="password" required placeholder="••••••••"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 ring-purple-600 outline-none">
        </div>

        <div class="flex items-center space-x-2 my-2">
            <input type="checkbox" id="remember">
            <label for="remember" class="text-sm text-black">Remember me</label>
        </div>

        <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded hover:bg-purple-700 transition">
            Sign in
        </button>
    </form>

    <p class="mt-4 text-center text-sm text-gray-500">
        Don't have an account yet?
        <a href="{{ route('register') }}" class="text-purple-600 hover:text-purple-500">
            Create account
        </a>
    </p>
</div>
</body>
</html>
