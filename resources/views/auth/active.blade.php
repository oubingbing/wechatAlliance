@extends('layouts/admin')

@section('content')
    <body class="login-bg">
        <div style="width: 50%;background: white;margin: 100px auto;border-radius: 20px">
            <h2 style="text-align: center;padding: 50px">
                激活账号成功，快去<a style="color: blue" href="{{route('login')}}">登录</a>吧！<span id="time" style="color: red">1</span>
            </h2>
        </div>
    <script>
        var time = 0;
        self.setInterval(function () {
            time += 1;
            document.getElementById("time").innerHTML=time;
            console.log(time);
        },1000);
        setTimeout(function () {
            window.location.href = "{{route('login')}}";
        },5000)
    </script>
    </body>
@endsection
