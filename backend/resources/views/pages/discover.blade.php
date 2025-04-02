<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover Items - Lost & Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#9a75eb',
                        // 'primary-dark': '#4f46e5',
                        border: '#e5e7eb',
                        muted: {
                            DEFAULT: '#6b7280',
                            foreground: '#6b7280'
                        },
                        foreground: '#031a52',
                        background: '#ffffff',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white text-gray-800">
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

    <main class="max-w-[90rem] mx-auto px-6 py-12 md:py-20 flex flex-col-reverse md:flex-row items-center">
        <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <h1 class="text-3xl font-bold tracking-tight">Discover Items</h1>
                <p class="text-muted-foreground">Browse lost and found items in your area</p>
            </div>
            <div class="w-full">
                <div class="flex items-center justify-between">
                    <div class="inline-flex h-10 items-center justify-center rounded-md bg-gray-200 p-1 text-muted">
                        <button class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm bg-background text-foreground shadow-sm" data-state="active" data-tab="all">All Items</button>
                        <button class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm" data-tab="lost">Lost Items</button>
                        <button class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm" data-tab="found">Found Items</button>
                    </div>
                </div>       
            </div>
        </div>
    </main>
    </body>
</html>
