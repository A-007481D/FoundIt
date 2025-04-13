<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email Address</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
<div class="bg-white p-6 rounded shadow-md">
    <h1 class="text-2xl font-bold mb-4">Verify Your Email</h1>
    <p class="mb-4">
        Thanks for signing up! Please check your email for a verification link.
        If you did not receive the email, click below to request another.
    </p>
    @if (session('message'))
        <p class="mb-4 text-green-600">{{ session('message') }}</p>
    @endif
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
            Resend Verification Email
        </button>
    </form>
</div>
</body>
</html>
