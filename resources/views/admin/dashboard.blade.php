@extends('layouts/admin')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <div class="x-body layui-anim layui-anim-up" id="app">
        <blockquote class="layui-elem-quote">你好：{{$user->username}}，小程序注册之后还需要经过叶子的审核才能用，请加叶子微信：13425144866</blockquote>
        <fieldset class="layui-elem-field">
            <legend>用户数据统计</legend>
            <div class="layui-field-box">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body">
                            <div class="layui-carousel x-admin-carousel x-admin-backlog" lay-anim="" lay-indicator="inside" lay-arrow="none" style="width: 100%; height: 90px;">
                                <div carousel-item="">
                                    <ul class="layui-row layui-col-space10 layui-this">
                                        <li class="layui-col-xs2" style="text-align: center">
                                            <a href="javascript:;" class="x-admin-backlog-body">
                                                <h3>今日新增人数</h3>
                                                <p>
                                                    <cite>@{{new_user}}</cite></p>
                                            </a>
                                        </li>
                                        <li class="layui-col-xs2" style="text-align: center">
                                            <a href="javascript:;" class="x-admin-backlog-body">
                                                <h3>今日浏览人数</h3>
                                                <p>
                                                    <cite>@{{ visit_user }}</cite></p>
                                            </a>
                                        </li>
                                        <li class="layui-col-xs2" style="text-align: center">
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
                        <td class="name">@{{ app_name }}
                            <div class="layui-input-inline" style="display: none">
                                {{ csrf_field() }}
                                <input type="text" lay-verify="required" class="layui-input" placeholder="名字" name="name" style="width: 200px;float: left">
                                <button class="layui-btn layui-btn-blue update-button" style="float: left">提交</button>
                            </div>
                            <a title="编辑" class="edit-app" hidden href="javascript:;">
                                <i class="layui-icon">&#xe642;</i>
                            </a>
                        </td></tr>
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
                        <th>app_id</th>
                        <td class="name">@{{ app_key }}
                            <div class="layui-input-inline" style="display: none">
                                <input type="text" lay-verify="required" class="layui-input" placeholder="app_id" name="app_key" style="width: 250px;float: left">
                                <button class="layui-btn layui-btn-blue update-button" style="float: left">提交</button>
                            </div>
                            <a title="编辑" class="edit-app" hidden href="javascript:;">
                                <i class="layui-icon">&#xe642;</i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>app_secret</th>
                        <td class="name">@{{ app_secret }}
                            <div class="layui-input-inline" style="display: none">
                                <input type="text" lay-verify="required" class="layui-input" placeholder="app_secret" name="app_secret" style="width: 250px;float: left">
                                <button class="layui-btn layui-btn-blue update-button" style="float: left">提交</button>
                            </div>
                            <a title="编辑" class="edit-app" hidden href="javascript:;">
                                <i class="layui-icon">&#xe642;</i>
                            </a>
                        </td></tr>
                    <tr>
                        <th>接口域名</th>
                        <td>@{{ domain }}</td></tr>
                    <tr>
                        <th>小程序二维码</th>
                        <td>
                            <div v-if="attachments"><img v-bind:src="imageUrl+attachments.qr_code" alt="" style="width: 90px;height: 90px;margin-bottom: 10px"></div>
                            <el-upload
                                    v-if="attachments"
                                    :action="upLoadDomain"
                                    class="upload-demo"
                                    :on-remove="handleRemove"
                                    :on-success="uploadSuccess"
                                    list-type="picture">
                                <el-button size="small" type="primary">点击上传</el-button>
                            </el-upload>
                            <el-upload
                                    v-else
                                    :action="upLoadDomain"
                                    class="upload-demo"
                                    :on-remove="handleRemove"
                                    :on-success="uploadSuccess"
                                    list-type="picture">
                                <el-button size="small" type="primary">修改二维吗</el-button>
                            </el-upload>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </fieldset>
    </div>
    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
    <script src="https://cdn.bootcss.com/axios/0.17.1/axios.min.js"></script>
    <script src="https://unpkg.com/element-ui/lib/index.js"></script>
    <script>
        $(document).ready(function(){
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

            $(".name").mouseover(function(){
                $(this).children('.edit-app').show();
            });

            $(".name").mouseleave(function(){
                $(this).children('.edit-app').hide();
                $(this).children('.edit-app').siblings(".layui-input-inline").css("display", "none");
            });

            $(".edit-app").on("click",function(){
                $(this).siblings(".layui-input-inline").css("display", "");
            });

            $(".update-button").on("click",function () {
                var value = $(this).siblings(".layui-input").val();
                var name = $(this).siblings(".layui-input").attr("name");
                var token = $("input[name='_token']").val();

                $.post("{{ asset('admin/update_app_info') }}",{
                    name:name,
                    value:value
                },function(res){
                    console.log(res);
                    layer.msg(res.message);
                    if(res.code == 200){
                        setTimeout(function () {
                            window.location.href = '';
                        },1500)
                    }
                });
            })
        });
    </script>
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
                close_audit_status:false,
                upLoadDomain:'https://up-z2.qbox.me',
                appImageUrl:'',
                attachments:[],
                imageUrl:'http://image.kucaroom.com/'
            },
            created:function () {
                this.getUploadToken();
                this.getUserInfo();
                this.getAppInfo();
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
                            this.attachments = res.data.attachments;

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
                },
                /**
                 * 移除图片
                 */
                handleRemove:function (file) {
                    this.appImageUrl = '';
                },
                /**
                 * 监听上传成功回调
                 * @param res
                 */
                uploadSuccess:function (res) {
                    this.appImageUrl = res.key;

                    axios.patch('/admin/update_qr_code',{image:this.appImageUrl})
                        .then( response=> {
                            var res = response.data;
                            console.log(res);
                            if(res.code == 200){
                                this.attachments = res.data.attachments;
                                layer.msg('修改成功！');
                            }else{
                                layer.msg('修改失败！');
                            }

                        }).catch(function (error) {
                        console.log(error);
                    });
                },
                /**
                 * 获取七牛token
                 */
                getUploadToken:function () {
                    axios.get("{{ asset('/admin/upload_token') }}")
                        .then( response=> {
                            this.upLoadDomain = this.upLoadDomain+'?token='+response.data.data;
                            console.log(this.upLoadDomain);
                        }).catch(function (error) {
                        console.log(error);
                    });
                },
            },
        });
    </script>
    @endsection