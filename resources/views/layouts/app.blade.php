<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ Auth::user()->id }}">
    <meta name="user-permissions" content="{{ json_encode(array_column(json_decode(json_encode(Auth::user()->allPermissions()), true), 'name')) }}">
    <meta name="user-roles" content="{{ json_encode(array_column(json_decode(json_encode(Auth::user()->roles), true), 'name')) }}">


    <title>Shurl</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/dashboard/theme.css') }}">

    <!-- Scripts -->
    @routes
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{ mix('js/dashboard/app.js') }}" defer></script>
</head>
<body class="font-sans antialiased">
@inertia
</body>
</html>
