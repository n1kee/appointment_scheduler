<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <link href="https://fonts.googleapis.com/css?family=Material+Icons" rel="stylesheet" type="text/css">

        <link href="{{ mix('/css/app.css') }}" rel="stylesheet">

        <style>
        </style>
    </head>
    <body class="antialiased">
        <div id="app">
            <ul class="nav">
                <li>
                    <router-link to="/patients/new">Создать пациента</router-link>
                </li>
                <li>
                    <router-link to="/doctors/new">Создать доктора</router-link>
                </li>
                <li>
                    <router-link to="/appointments/new">Записаться на прием</router-link>
                </li>
                <li>
                    <router-link to="/appointments">Список записей на прием</router-link>
                </li>
            </ul>
            <div class="content">
                <router-view/>
            </div>
        </div>
        <script src="{{ mix('/js/app.js') }}"></script>
    
    </body>
</html>
