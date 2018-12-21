@extends('layouts/admin')

@section('content')
    <body class="login-bg">

    <div class="login layui-anim layui-anim-up">
        <div class="message"><a href="{{ asset('/home') }}" style="color: white;">校园小情书 - 后台登录</a></div>
        <div id="darkbannerwrap"></div>

        <form method="post" class="layui-form">
            {{ csrf_field() }}
            <input name="email" placeholder="邮箱"  type="text" lay-verify="required" class="layui-input" >
            <hr class="hr15">
            <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
            <hr class="hr15">
            <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
            <hr class="hr20" >
        </form>
        <div><a href="{{ asset('register') }}">没有账号？快去注册吧</a></div>
    </div>

    <script>
        $(function  () {
            layui.use('form', function(){
                var form = layui.form;
                form.on('submit(login)', function(data){
                    var fields = data.field;
                    $.post("/login",fields,function(res){
                        if(res.error_code === 200){
                            layer.msg(res.error_message);
                            setTimeout(function () {
                                window.location.href = '/admin';
                            },1000)
                        }else{
                            layer.msg(res.error_message)
                        }
                    });

                    return false;
                });
            });
        })


    </script>
    </body>
@stop