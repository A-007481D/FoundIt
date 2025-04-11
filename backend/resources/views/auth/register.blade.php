<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

</head>
<body class="min-h-screen flex flex-col items-center justify-center bg-gray-50">

<div data-aos="fade-up"  class="bg-white p-8 rounded shadow-md w-full max-w-md mx-4">
    <h1 class="text-2xl text-center font-bold mb-2">Create Account</h1>
    <p class="text-gray-500 mb-6 text-center">
        Sign up to start using Foundit!
    </p>

    @if(session('status'))
        <div class="mb-4 text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <!-- Display Validation Errors -->
    @if($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('register') }}">
        @csrf


        <div class="w-full max-w-md mx-auto mt-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('auth.google') }}"
                   class="flex items-center justify-center w-full px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google"
                         class="h-5 w-5 mr-2">
                    <span class="text-sm font-medium text-gray-700">Google</span>
                </a>
                <a href="{{ route('auth.facebook') }}"
                   class="flex items-center justify-center w-full px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" alt="Facebook"
                         class="h-5 w-5 mr-2">
                    <span class="text-sm font-medium text-gray-700">Facebook</span>
                </a>
            </div>
        </div>



        <div class="flex items-center my-4">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="mx-2 text-[0.8em] text-gray-500">OR CONTINUE WITH</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>

        <div class="flex gap-2 mb-4">
            <div class="mb-4">
                <label for="first-name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                <input type="text" name="firstname" id="first-name" required placeholder="Abdelmalek"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 ring-purple-600 outline-none">
            </div>
            <div class="mb-4">
                <label for="last-name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                <input type="text" name="lastname" id="last-name" required placeholder="Labid"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 ring-purple-600 outline-none">
            </div>

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
            <label for="remember" class="text-sm text-black">I accept the terms of service and privace policy</label>
        </div>

        <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded hover:bg-purple-700 transition">
            Sign in
        </button>
    </form>
    @if(session('status'))
        <div class="mb-4 text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <!-- Display Validation Errors -->
    @if($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p class="mt-4 text-center text-sm text-gray-500">
        Already registered?
        <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-500">
            Log in
        </a>
    </p>
</div>
</body>
</html>
