@extends('layouts/admin')
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <style>
    </style>
    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
    <script src="https://unpkg.com/element-ui/lib/index.js"></script>
    <div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">演示</a>
        <a>
          <cite>导航元素</cite></a>
      </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
            <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="x-body" id="app">
        <div class="layui-row" v-if="templates.length == 0">
            <button class="layui-btn layui-btn-danger" v-on:click="initTemplate()" style="margin-bottom: 10px;">初始化模板</button>
        </div>
        <blockquote class="layui-elem-quote">共有数据@{{templates.length}}条</blockquote>
        <table class="layui-table">
            <thead>
            <tr>
                <th>模板</th>
                <th>模板ID</th>
            </thead>
            <tbody>
            <tr v-for="template in templates">
                <td>@{{ template.title }}</td>
                <td>@{{ template.template_id }}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <script>
        new Vue({
            el: '#app',
            data: {
                templates:[],
                disabled:true
            },
            created:function () {
                this.getUsers();
                console.log('用户首页');
            },
            methods:{
                initTemplate:function (e) {

                    layer.msg('初始化中，请稍后...');
                    axios.post("{{ asset("admin/templates") }}",{}).then( response=> {
                        var res = response.data;
                        if(res.code === 200){
                            layer.msg(res.message);
                            this.templates = res.data;
                        }else{
                            console.log('error:'+res);
                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                },
                getUsers:function () {
                    var url = "{{ asset("admin/templates") }}";
                    axios.get(url)
                            .then( response=> {
                                var res = response.data;
                                if(res.code === 200){
                                    console.log(res);
                                    this.templates = res.data;
                                }else{
                                    console.log('error:'+res);
                                }
                            }).catch(function (error) {
                        console.log(error);
                    });
                }
            }
        })
    </script>

@endsection