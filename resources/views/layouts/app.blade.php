<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Laravel App</title>

    {{-- Vite for CSS/JS --}}
    @vite('resources/css/app.css')
</head>
<body style="font-family: sans-serif; margin: 0;" class="bg-orange-50">

    {{-- Navbar Component --}}
    <x-navbar />
    <div style="padding: 20px;">
        @yield('content')
    </div>
    <x-footer />

</body>
</html>
