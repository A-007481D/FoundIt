<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FoundIt!')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {DEFAULT: "#8a2be2", foreground: "#ffffff"},
                        secondary: {DEFAULT: "#10b981", foreground: "#ffffff"},
                        muted: {DEFAULT: "#f1f5f9", foreground: "#64748b"},
                        mutedrecent: { DEFAULT: "#f1f5f9", foreground: "#64748b" },
                        background: "#ffffff",
                        foreground: "#0f172a",
                        border: "#e2e8f0"
                    }
                }
            }
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    @stack('styles')
</head>

<body class="@yield('body-class', 'flex min-h-screen flex-col bg-muted/30')">
<x-layout.navbar/>

<main class="flex-1">
    @yield('content')
</main>

@include('layout.footer')

@stack('scripts')
</body>
</html>
