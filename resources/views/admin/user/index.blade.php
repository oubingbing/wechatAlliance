@extends('layouts/admin')
<style>
    [v-cloak] {
        display: none;
    }
    .td-manage{
        width: 100px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .td-manage .btn{
        padding: 5px 5px;
        cursor: pointer;
    }

</style>
@section('content')
    <link rel="stylesheet" href="{{asset('css/element-ui-index.css')}}">
    <style>
    </style>
    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
    <script src="{{asset('js/element-ui-index.js')}}"></script>
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
    <div class="x-body" id="app" v-cloak>
        <div class="layui-row">
            <div class="layui-form layui-col-md12 x-so">
                <input type="text" v-model="username"  placeholder="请输入用户名" autocomplete="off" class="layui-input">
                <button class="layui-btn" v-on:click="searchUser" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
            </div>
        </div>
        <blockquote class="layui-elem-quote">共有数据：@{{total}} 条</blockquote>
        <table class="layui-table">
            <thead>
            <tr>
                <th>头像</th>
                <th>微信昵称</th>
                <th>性别</th>
                <th>国家</th>
                <th>省</th>
                <th>市</th>
                <th>客服</th>
                <th>超管</th>
                <th>创建时间</th>
                <th>操作</th>
            </thead>
            <tbody>
                <tr v-for="user in users">
                    <td><img v-bind:src=user.avatar style="width: 40px;width: 40px"/></td>
                    <td>@{{ user.nickname }}</td>
                    <td>@{{ user.gender }}</td>
                    <td>@{{ user.country }}</td>
                    <td>@{{ user.province }}</td>
                    <td>@{{ user.city }}</td>
                    <td>@{{ user.service?'客服':'' }}</td>
                    <td>@{{ user.type == 2?'超管':''}}</td>
                    <td>@{{ user.created_at }}</td>
                    <td>
                        <div class="td-manage">
                            <div v-if="!user.service" class="btn"  v-on:click="setService(user.id)">设置为客服</div>
                            <div v-if="user.type != 2" class="btn" v-on:click="setSupervise(user.id)">设置超管</div>
                            <div v-else v-on:click="removeSupervise(user.id)" class="btn">取消超管</div>
                            <div class="btn" v-if="!user.blacklist" v-on:click="setBlackList(user.id)">加入黑名单</div>
                            <div class="btn" v-if="user.blacklist" v-on:click="removeBlackList(user.id)">移除黑名单</div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="page">
            <el-pagination
                    background
                    @current-change="handleCurrentChange"
                    layout="prev, pager, next"
                    :page-size="page_size"
                    :current-page="current_page"
                    :total="total">
            </el-pagination>
        </div>
    </div>
    <script>
        new Vue({
            el: '#app',
            data: {
                users:[],
                total:0,
                page_size:20,
                current_page:1,
                username:''
            },
            created:function () {
                this.getUsers();
                console.log('用户首页');
            },
            methods:{
                setSupervise:function (e) {
                    axios.post("{{ asset("admin/set_supervise") }}",{
                        supervise_id:e
                    }).then( response=> {
                        var res = response.data;
                        if(res.error_code === 200){
                            layer.msg(res.error_message);
                            this.getUsers();
                        }else{
                            console.log('error:'+res);
                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                },

                removeBlackList:function (userId) {
                    axios.delete("blacklist/"+userId,{
                        black_id:userId
                    }).then( response=> {
                        var res = response.data;
                        if(res.error_code === 200){
                            layer.msg(res.error_message);
                            console.log(res)
                            this.users.map(item=>{
                                if(item.id == userId){
                                    item.blacklist = '';
                                }
                                return item;
                            });
                        }else{
                            console.log('error:'+res);
                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                },

                setBlackList:function (userId) {
                    axios.post("blacklist",{
                        black_id:userId
                    }).then( response=> {
                        var res = response.data;
                        if(res.error_code === 200){
                            layer.msg(res.error_message);
                            console.log(res)
                            this.users.map(item=>{
                                if(item.id == userId){
                                    item.blacklist = res.data;
                                }
                                return item;
                            });
                        }else{
                            console.log('error:'+res);
                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                },

                /**
                 * 移除超管
                 * @param e
                 */
                removeSupervise:function (e) {
                    axios.post("{{ asset("admin/remove_service") }}",{
                        supervise_id:e
                    }).then( response=> {
                        var res = response.data;
                        if(res.error_code === 200){
                            layer.msg(res.error_message);
                            this.getUsers();
                        }else{
                            console.log('error:'+res);
                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                },
                setService:function (e) {
                    axios.post("{{ asset("admin/set_service") }}",{
                        service_id:e
                    }).then( response=> {
                        var res = response.data;
                        if(res.error_code === 200){
                            layer.msg(res.error_message);
                            this.getUsers();
                        }else{
                            console.log('error:'+res);
                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                },
                handleCurrentChange:function (e) {
                    this.current_page = e;
                    this.getUsers();
                },

                searchUser:function () {
                    this.current_page=1;
                    this.getUsers();
                },

                getUsers:function () {
                    var url = "{{ asset("admin/wechat_users") }}";
                    axios.get(url+"?page_size="+this.page_size+'&page_number='+this.current_page+'&order_by=created_at&sort_by=desc&username='+this.username)
                            .then( response=> {
                                var res = response.data;
                                if(res.error_code === 200){
                                    this.users = res.data.page_data;
                                    this.total = res.data.page.total_items;
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