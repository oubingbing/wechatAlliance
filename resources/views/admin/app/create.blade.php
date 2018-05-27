@extends('layouts/admin')
<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
<link href="{{ asset('css/jquery-editable-select.css') }}" rel="stylesheet">
@section('content')
    <body class="login-bg">

    <div class="login layui-anim layui-anim-up" id="app">
        <div class="message" style="background: #EE7600">小情书 - 创建小程序</div>
        <div id="darkbannerwrap"></div>

        <form method="POST" class="layui-form">
            {{ csrf_field() }}
            <input name="app_name" placeholder="小程序名字（必须和你的小程序名字一致）"  type="text" lay-verify="required" class="layui-input" >
            <hr class="hr15">
            <input name="app_key" placeholder="APP_KEY（必填）"  type="text" lay-verify="required" class="layui-input email" >
            <hr class="hr15">
            <input name="app_secret" lay-verify="required" placeholder="App_secret（必填）"  type="text" class="layui-input">
            <hr class="hr15">
            <input name="mobile" lay-verify="required" placeholder="管理员手机号码（必填）"  type="text" class="layui-input">
            <hr class="hr15">
            <div class="layui-input-inline" style="width: 100%">
                <select name="college" id="select-college">
                    @foreach($colleges as $college)
                        <option >{{ $college->name }}</option>
                    @endforeach
                </select>
            </div>
            <hr class="hr15">
            <input value="新建" lay-submit lay-filter="login" style="width:100%;background: #EE7600" type="submit">
            <hr class="hr20" >
        </form>
        <div><span>我们将保护您的小程序信息不被泄露</span></div>
    </div>
    <script src="{{ asset('js/jquery-editable-select.js') }}"></script>
    <script>
        $(function  () {

            $("#select-college").change(function(){
                console.log('log');
            });

            $('#select-college').editableSelect({
                effects: 'slide'
            });

            layui.use('form', function(){
                var form = layui.form;
                //监听提交
                form.on('submit(login)', function(data){
                    var fields = data.field;

                    if(fields.password_confirmation !== fields.password){
                        layer.msg('两次输入密码不一致！');
                        return false;
                    }

                    $.post("{{ asset('admin/create_app') }}",fields,function(res){
                        if(res.code === 500){
                            layer.msg(res.message)
                        }else{
                            if(res.code === 201){
                                layer.msg(res.message,function (res) {
                                    window.location.href = res.data;
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
