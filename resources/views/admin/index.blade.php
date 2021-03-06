<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- CSRF --}}
        {{-- Cross-Site Request Forgery (CSRF) is an attack that forces
        an end user to execute unwanted actions on a web application
        in which they're currently authenticated. --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Admin Site | APU Car Parking System</title>

        <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">

        <!-- Styles -->
        <style>
            #app {
                position: fixed;
                max-height: 100vh;
                height: 100vh;
                max-width: 100vw;
                width: 100vw;
            }
        </style>
    </head>
    <body>
        <div id="app">
            <admin-landing-page></admin-landing-page>
        </div>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
