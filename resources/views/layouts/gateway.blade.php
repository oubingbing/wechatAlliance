<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '校园小情书') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/logo.jfif') }}" type="image/x-icon">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        .footer{
            background: #EEEEEE;
            text-align: center;
            padding: 5px;
        }
    </style>
</head>
<body>
<div id="app">

    <nav class="navbar navbar-inverse">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="/">{{ config('app.name', '校园小情书') }}</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{ url('contact') }}">联系</a></li>
                    <li><a href="{{ url('about') }}">关于我</a></li>
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')

    <footer class="footer navbar-fixed-bottom">
        <div class="container footer">
            @2016-2018 校园小情书 | 粤ICP备16004706号-1
        </div>
    </footer>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>