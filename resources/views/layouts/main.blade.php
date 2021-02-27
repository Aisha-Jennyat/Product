<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Almatin login') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="main" class="app-container-main">
        @yield('navbar')
        <main class="app-content-main">
            @yield('content')
        </main>
        @yield('footer')
    </div>
<script>
function getForm(value) {
    document.getElementById('input-search').value = value;
    event.preventDefault();
    document.getElementById('search-form').submit()
}
</script>
</body>
</html>