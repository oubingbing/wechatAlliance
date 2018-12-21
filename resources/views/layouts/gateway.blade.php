<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="baidu_union_verify" content="98d08a1e6a234cbf644d940d26fdf911">

    <title>校园小情书</title>
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
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
                <a class="navbar-brand" href="/">校园小情书</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{ url('/home') }}">首页</a></li>
                    @if (session('admin_id'))
                    <li><a href="{{ url('/admin') }}">控制台</a></li>
                    @else
                        <li><a href="{{ url('login') }}">登录</a></li>
                        <li><a href="{{ url('register') }}">注册</a></li>
                    @endif
                    <li><a href="{{ url('contact') }}">联系</a></li>
                    <li><a href="{{ url('about') }}">公众号</a></li>
                    <!--<li><a href="https://www.jianshu.com/p/6f3091d4193c" target="_blank">部署教程</a></li>-->
                        @if (session('admin_id'))
                            <li><a href="{{ asset('/logout') }}">退出</a></li>
                        @endif
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')

    <footer class="footer navbar-fixed-bottom">
        <div class="container footer">
            <a href="http://www.miitbeian.gov.cn/">@2016-2018 校园小情书 | 粤ICP备16004706号-1</a>
        </div>
    </footer>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>