<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FinalCut') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@600;700&family=Inter:wght@400;500;600&family=Indie+Flower&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="page-container font-body">
    <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-12">
        <!-- Logo -->
        <a href="/" class="flex items-center gap-2 mb-8">
            <span class="font-display font-bold text-2xl text-ink">Final<span class="text-blue-text">Cut</span></span>
            <span class="font-handwriting text-sm text-ink-secondary">film & ticket</span>
        </a>

        <!-- Auth Card -->
        <div class="w-full max-w-md mx-auto px-6">
            <div class="card p-8">
                {{ $slot }}
            </div>

            <!-- Footer link -->
            <p class="text-center mt-6 text-sm text-ink-secondary">
                &copy; {{ date('Y') }} FinalCut
            </p>
        </div>
    </div>
</body>
</html>
