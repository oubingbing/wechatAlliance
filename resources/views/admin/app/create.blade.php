@extends('layouts/admin')
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
                        <option v-for="(item,index) in colleges">@{{ item }}</option>
                </select>
            </div>
            <hr class="hr15">
            <input value="新建" lay-submit lay-filter="login" style="width:100%;background: #EE7600" type="submit">
            <hr class="hr20" >
        </form>
        <div><span>我们将保护您的小程序信息不被泄露</span></div>
    </div>
    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
    <script src="https://cdn.bootcss.com/axios/0.17.1/axios.min.js"></script>
    <script src="{{ asset('js/jquery-editable-select.js') }}"></script>
    <script>
        $(function  () {
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
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                colleges:[],
                name:'yezi'
            },
            mounted:function () {
                this.getColleges();
            },
            methods: {
                getColleges:function () {
                    //this.colleges = [1,2,3,4]//可以更新视图
                    var _this = this;

                    axios.get("{{ asset('colleges') }}").then( response=> {

                        //this.colleges = [1,2,3,4]//不可以更新视图
                        _this.colleges = [1,2,3,4];//不可以更新视图

                    }).catch(function (error) {
                        console.log(error);
                    });
                }
            }
        })
    </script>
    </body>
@endsection
