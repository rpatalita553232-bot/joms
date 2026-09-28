<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 · Forbidden</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 antialiased">
    <main class="flex min-h-screen items-center justify-center px-6 py-12">
        <div class="w-full max-w-lg text-center">
            <p class="text-sm font-semibold uppercase tracking-widest text-red-700">403 · Forbidden</p>
            <h1 class="mt-4 text-3xl font-semibold">You can't access this page</h1>
            <p class="mt-3 text-sm leading-6 text-gray-600">Your account doesn't have permission to view this resource.</p>
            <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800">
                    <i class="ti ti-arrow-left mr-2"></i> Go back
                </a>
                @guest
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">Sign in</a>
                @endguest
            </div>
        </div>
    </main>
</body>
</html>