<!DOCTYPE html>
<html class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    {{-- Inertia --}}
    <script src="https://cdnjs.cloudflare.com/polyfill/v3/polyfill.min.js?features=smoothscroll,NodeList.prototype.forEach,Promise,Object.values,Object.assign" defer></script>

    {{-- Ping CRM --}}
    <script src="https://cdnjs.cloudflare.com/polyfill/v3/polyfill.min.js?features=String.prototype.startsWith" defer></script>

    @vite('resources/js/app.js')
    @inertiaHead
    @routes
</head>
<body class="font-sans leading-none text-gray-700 antialiased">
    @inertia
</body>
</html>
