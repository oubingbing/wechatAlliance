@extends('layouts/admin')
@section('content')
    <body class="login-bg">

    <div class="login layui-anim layui-anim-up" id="app">
        <div class="message" style="background: #EE7600">小情书 - 创建小程序</div>
        <div id="darkbannerwrap"></div>

        <form method="POST" class="layui-form">
            {{ csrf_field() }}
            <input name="app_name" v-model="app_name" placeholder="小程序名字（必须和你的小程序名字一致）"  type="text" lay-verify="required" class="layui-input" >
            <hr class="hr15">
            <input name="app_key" v-model="app_key" placeholder="app_id（必填）"  type="text" lay-verify="required" class="layui-input email" >
            <hr class="hr15">
            <input name="app_secret" v-model="app_secret" lay-verify="required" placeholder="app_secret（必填）"  type="text" class="layui-input">
            <hr class="hr15">
            <input name="mobile" v-model="mobile" lay-verify="required" placeholder="管理员手机号码（必填）"  type="text" class="layui-input">
            <hr class="hr15">
            <div class="layui-input-inline" style="width: 100%">
                <v-select label="name" ref="select" :options="colleges" name="college" v-model="selected"></v-select>
            </div>
            <hr class="hr15">
            <input value="提交" lay-submit lay-filter="login" style="width:100%;background: #EE7600" @click="createApp" type="submit">
            <hr class="hr20" >
        </form>
        <div><span>我们将保护您的小程序信息不被泄露</span></div>
    </div>
    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
    <script src="https://cdn.bootcss.com/axios/0.17.1/axios.min.js"></script>
    <script src="https://cdn.bootcss.com/vue-select/2.4.0/vue-select.js"></script>
    <script>
        $(function  () {
            layui.use('form', function(){
                var form = layui.form;
                //监听提交
                form.on('submit(login)', function(data){
                    return false;
                });
            });
        })
    </script>
    <script>
        Vue.component('v-select', VueSelect.VueSelect);

        var app = new Vue({
            el: '#app',
            data: {
                colleges:[],
                college_id:null,
                selected:{id:0,'name':"请选择学校"},
                app_name:null,
                app_key:null,
                app_secret:null,
                mobile:null
            },
            created:function () {
                this.getColleges();
            },
            methods: {
                /**
                 * 获取学校数据
                 *
                 * @author yezi
                 */
                getColleges:function () {
                    axios.get("{{ asset('colleges') }}").then( response=> {
                        response.data.data.map(item=>{
                            this.colleges.push(item);
                        });
                    }).catch(function (error) {
                        console.log(error);
                    });
                },
                /**
                 * 提交数据
                 *
                 * @author 叶子
                 */
                createApp:function () {
                    this.college_id = this.selected.id;
                    console.log(this.selected.id);

                    var college_id = this.college_id;
                    if(college_id == null || college_id === 0){
                        layer.msg('学校不能为空');
                        return false;
                    }

                    axios.post("{{ asset('admin/create_app') }}",{
                        app_name:this.app_name,
                        app_key:this.app_key,
                        app_secret:this.app_secret,
                        mobile:this.mobile,
                        college_id:college_id
                    }).then( res=> {

                        console.log(res.data);
                        var resData = res.data;

                        if(resData.code === 200){
                            layer.msg(resData.message);
                            setTimeout(function () {
                                window.location.href = resData.data;
                            },1500);
                        }else{
                            layer.msg(resData.message)
                        }

                    }).catch(function (error) {
                        console.log(error);
                    });
                }
            },
        })
    </script>
    </body>
@endsection
