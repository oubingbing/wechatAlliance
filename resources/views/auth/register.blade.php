@extends('layouts/admin')

@section('content')
    <body class="login-bg">

    <div class="login layui-anim layui-anim-up">
        <div class="message"><a href="{{ asset('/home') }}" style="color: white;">湛江市赤坎区古卡饮品店 - 注册</a></div>
        <div id="darkbannerwrap"></div>

        <form method="POST" class="layui-form">
            {{ csrf_field() }}
            <input name="username" placeholder="姓名（必填）"  type="text" lay-verify="required" class="layui-input" >
            <hr class="hr15">
            <input name="email" placeholder="邮箱（必填）"  type="text" lay-verify="required" class="layui-input email" >
            <hr class="hr15">
            <input name="password" lay-verify="required" placeholder="密码（必填）"  type="password" class="layui-input">
            <hr class="hr15">
            <input name="password_confirmation" lay-verify="required" placeholder="确认密码（必填）"  type="password" class="layui-input">
            <hr class="hr15">
            <input value="注册" lay-submit lay-filter="login" style="width:100%;" type="submit">
            <hr class="hr20" >
        </form>
        <div><a href="{{ asset('login') }}">已有账号？快去登录吧</a></div>
    </div>
    <!--<script src="https://cdn.bootcss.com/blueimp-md5/2.10.0/js/md5.min.js"></script>-->
    <script>
        $(function  () {
            layui.use('form', function(){
                var form = layui.form;
                //监听提交
                form.on('submit(login)', function(data){
                    var fields = data.field;

                    if(fields.password_confirmation !== fields.password){
                        layer.msg('两次输入密码不一致！');
                        return false;
                    }

                    $.post("{{route('register')}}",fields,function(res){
                        if(res.code === 500){
                            layer.msg(res.message)
                        }else{
                            if(res.code === 201){
                                layer.msg(res.message,function () {
                                    window.location.href = "{{route('login')}}";
                                })
                            }else{
                                layer.msg(res.message)
                            }
                        }
                    });

                    return false;
                 });
            });
        })


    </script>
    </body>
@endsection
