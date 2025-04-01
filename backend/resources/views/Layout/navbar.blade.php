<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Foundit! - Find your belongings!</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
  <nav class="sticky top-0 z-50 bg-white/70 backdrop-blur-md border-b border-gray-200 flex items-center justify-between py-3 px-28">
    <div class="flex items-center space-x-2">
      <a href="#" class="flex items-center space-x-1">
        <img src="" alt="Logo" class="h-8 w-8 object-cover">
        <span class="font-bold text-xl text-purple-600">Foundit!</span>
      </a>
    </div>
    <ul class="flex space-x-6 text-gray-700 font-medium">
      <li><a href="#" class="hover:text-purple-600 hover:border-purple-500 hover:border-b-2">Home</a></li>
      <li><a href="#" class="hover:text-purple-600 hover:border-purple-500 hover:border-b-2">How it works</a></li>
      <li><a href="#" class="hover:text-purple-600 hover:border-purple-500 hover:border-b-2">Community Forum</a></li>
      <li><a href="#" class="hover:text-purple-600 hover:border-purple-500 hover:border-b-2">Contact Us</a></li>
      <li><a href="#" class="hover:text-purple-600 hover:border-purple-500 hover:border-b-2">FAQ</a></li>
    </ul>
    <div class="flex items-center space-x-4">
      <a href="{{ route('login') }}" class="rounded-md font-medium bg-white border-2 border-purple-600 text-purple-600 px-4 py-2 hover:bg-purple-50">
        Log in
      </a>
      <a href="{{ route('register') }}" class="rounded-md text-white font-medium bg-purple-600 hover:bg-purple-700 border-2 border-purple-600 px-4 py-2">
        Sign up
      </a>
    </div>
  </nav>
</body>
</html>
