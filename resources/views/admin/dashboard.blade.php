@extends('layouts/admin')
@section('content')
    <div class="x-body layui-anim layui-anim-up" id="app">
        <blockquote class="layui-elem-quote">你好：{{$user->username}}</blockquote>
        <fieldset class="layui-elem-field">
            <legend>用户数据统计</legend>
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
        <fieldset class="layui-elem-field">
            <legend>小程序信息</legend>
            <div class="layui-field-box">
                <table class="layui-table">
                    <tbody>
                    <tr>
                        <th>名称</th>
                        <td>@{{ app_name }}</td></tr>
                    <tr>
                        <th>状态</th>
                        <td style="color: orangered">
                            @{{ app_status_string }} <button class="layui-btn layui-btn-danger" v-if="open_audit_status" v-on:click="openAudit">开启审核模式</button><button class="layui-btn" v-if="close_audit_status" v-on:click="closeAudit">关闭审核模式</button>
                        </td></tr>
                    <tr>
                        <th>学校</th>
                        <td>@{{ college }}</td></tr>
                    <tr>
                        <th>alliance_key</th>
                        <td>@{{  alliance_key }}</td></tr>
                    <tr>
                        <th>app_key</th>
                        <td>@{{ app_key }}</td></tr>
                    <tr>
                        <th>app_secret</th>
                        <td>@{{ app_secret }}</td></tr>
                    <tr>
                        <th>接口域名</th>
                        <td>@{{ domain }}</td></tr>
                    </tbody>
                </table>
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
                all_user:'-',
                app_name:'',
                app_status_string:'',
                app_status:'',
                app_key:'',
                app_secret:'',
                alliance_key:'',
                domain:'',
                college:'',
                open_audit_status:false,
                close_audit_status:false
            },
            created:function () {
                this.getUserInfo();
                this.getAppInfo();
                console.log('我是数据'+this.new_user);
            },
            methods:{
                /**
                 * 获取用户信息
                 *
                 * @author 叶子
                 */
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
                /**
                 * 获取APP信息
                 *
                 * @author 叶子
                 */
                getAppInfo:function () {
                    axios.get("{{ asset('admin/app') }}").then( response=> {
                        var res = response.data;
                        if(res.code === 200){
                            this.app_name = res.data.name;
                            this.app_status_string = res.data.status_string;
                            this.app_key = res.data.app_key;
                            this.app_secret = res.data.app_secret;
                            this.alliance_key = res.data.alliance_key;
                            this.domain = res.data.domain;
                            this.college = res.data.college;

                            if(res.data.status === 2){
                                this.open_audit_status = true;
                            }else{
                                if(res.data.status === 3) {
                                    this.close_audit_status = true;
                                }
                            }

                        }else{
                            console.log('error:'+res);
                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                },
                openAudit:function () {
                    axios.patch("{{ asset('admin/open_audit') }}").then( response=> {
                        var res = response.data;
                        if(res.code === 200){
                            layer.msg(res.message);
                            this.open_audit_status = false;
                            this.close_audit_status = true;
                            this.app_status_string = '微信审核中';
                        }else{
                            layer.msg(res.message);
                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                },
                closeAudit:function () {
                    axios.patch("{{ asset('admin/close_audit') }}").then( response=> {
                        var res = response.data;
                        if(res.code === 200){
                            layer.msg(res.message);
                            this.open_audit_status = true;
                            this.close_audit_status = false;
                            this.app_status_string = '运行中';
                        }else{
                            layer.msg(res.message);
                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                }
            },
        });
    </script>
    @endsection