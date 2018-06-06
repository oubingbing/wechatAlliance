@extends('layouts/admin')
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <style>
    </style>
    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
    <script src="https://unpkg.com/element-ui/lib/index.js"></script>
    <style>
        .image-container{
            display: flex;
            flex-direction: row;
        }
        .image-container .image{
            margin-right: 10px;
        }
    </style>
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
        <div class="layui-row">
            <form class="layui-form layui-col-md12 x-so">
                <input class="layui-input" placeholder="开始日" name="start" id="start">
                <input class="layui-input" placeholder="截止日" name="end" id="end">
                <input type="text" name="username"  placeholder="请输入用户名" autocomplete="off" class="layui-input">
                <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
            </form>
        </div>
        <xblock>
            <button class="layui-btn" onclick="x_admin_show('新建话题','/admin/topic/create')"><i class="layui-icon"></i>新建</button>
            <span class="x-right" style="line-height:40px">共有数据：@{{ total }} 条</span>
        </xblock>

        <table class="layui-table">
            <thead>
            <tr>
                <th>标题</th>
                <th>内容</th>
                <th>图片</th>
                <th>点赞数</th>
                <th>浏览数</th>
                <th>评论数</th>
                <th>状态</th>
                <th>创建时间</th>
                <th>操作</th>
            </thead>
            <tbody>
            <tr v-for="topic in topics">
                <td>@{{ topic.title }}</td>
                <td>@{{ topic.content }}</td>
                <td>
                    <div class="image-container">
                        <div class="image-item" v-for="image in topic.attachments">
                            <div class="image"><img v-bind:src="image" alt="" style="width: 80px;height: 80px"></div>
                        </div>
                    </div>
                </td>
                <td>@{{ topic.prase }}</td>
                <td>@{{ topic.view_number }}</td>
                <td>@{{ topic.comment_number }}</td>
                <td>@{{ topic.status == 1 ?'已下架':'上架中'}}</td>
                <td>@{{ topic.created_at }}</td>
                <td class="td-manage" style="float: left">
                    <button v-if="topic.status == 1" class="layui-btn layui-btn-danger" v-on:click="setUp(topic.id)">去上架</button>
                    <button v-if="topic.status == 2" class="layui-btn" v-on:click="setDown(topic.id)">去下架</button>
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
                topics:[],
                total:0,
                page_size:20,
                current_page:1
            },
            created:function () {
                console.log('用户首页');

                this.getTopic();
            },
            methods:{
                /**
                 * 获取话题列表
                 * */
                getTopic:function () {
                    var url = "{{ asset("admin/topic/list") }}";
                    axios.get(url+"?page_size="+this.page_size+'&page_number='+this.current_page+'&order_by=created_at&sort_by=desc')
                        .then( response=> {
                            var res = response.data;
                            if(res.code === 200){
                                this.topics = res.data.page_data;
                                this.total = res.data.page.total_items;
                                console.log('总数'+this.total);
                            }else{
                                console.log('error:'+res);
                            }
                        }).catch(function (error) {
                        console.log(error);
                    });
                },
                /**
                 * 监听分页
                 **/
                handleCurrentChange:function (e) {
                    console.log(e);
                    this.current_page = e;
                    this.getTopic();
                },
                /**
                 * 上架话题
                 * */
                setUp:function (id) {
                    axios.patch('/admin/topic/'+id+'/up')
                        .then( response=> {
                            var res = response.data;
                            if(res.code === 200){
                                layer.msg(res.message);
                                this.getTopic();
                            }else{
                                console.log('error:'+res);
                            }
                        }).catch(function (error) {
                        console.log(error);
                    });
                },
                /**
                 * 下架话题
                 *
                 * @param id
                 */
                setDown:function (id) {
                    axios.patch('/admin/topic/'+id+'/down')
                        .then( response=> {
                            var res = response.data;
                            if(res.code === 200){
                                layer.msg(res.message);
                                this.getTopic();
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