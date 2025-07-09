<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Landing Page')</title>
    <link rel="icon" href="/assets/img/futsal.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50">

    <!-- Main content section -->
    <main>
        @yield('content')
    </main>

</body>
</html>
