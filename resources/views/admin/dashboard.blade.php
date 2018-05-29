@extends('layouts/admin')
@section('content')
    <div class="x-body layui-anim layui-anim-up" id="app">
        <blockquote class="layui-elem-quote">你好：{{$user->username}} 今日是个好日子</blockquote>
        <fieldset class="layui-elem-field">
            <legend>数据统计</legend>
            <div class="layui-field-box">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body">
                            <div class="layui-carousel x-admin-carousel x-admin-backlog" lay-anim="" lay-indicator="inside" lay-arrow="none" style="width: 100%; height: 90px;">
                                <div carousel-item="">
                                    <ul class="layui-row layui-col-space10 layui-this">
                                        <li class="layui-col-xs2">
                                            <a href="javascript:;" class="x-admin-backlog-body">
                                                <h3>新增人数</h3>
                                                <p>
                                                    <cite>@{{new_user}}</cite></p>
                                            </a>
                                        </li>
                                        <li class="layui-col-xs2">
                                            <a href="javascript:;" class="x-admin-backlog-body">
                                                <h3>浏览人数</h3>
                                                <p>
                                                    <cite>@{{ visit_user }}</cite></p>
                                            </a>
                                        </li>
                                        <li class="layui-col-xs2">
                                            <a href="javascript:;" class="x-admin-backlog-body">
                                                <h3>总人数</h3>
                                                <p>
                                                    <cite>@{{ all_user }}</cite></p>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
    <script src="https://cdn.bootcss.com/axios/0.17.1/axios.min.js"></script>
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                new_user:'-',
                visit_user:'-',
                all_user:'-'
            },
            created:function () {
                this.getUserInfo();
                console.log('我是数据'+this.new_user);
            },
            methods:{
                getUserInfo:function () {
                    axios.get("{{ asset('admin/user_statistics') }}").then( response=> {
                        var res = response.data;
                        if(res.code === 200){
                            this.new_user = res.data.new_user;
                            this.visit_user = res.data.visit_user;
                            this.all_user = res.data.all_user;
                        }else{
                            console.log('error:'+res);
                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                },
            },
        });
    </script>
    @endsection