@extends('layouts/admin')
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
    <script src="https://unpkg.com/element-ui/lib/index.js"></script>
    <style>
        .post-container{
            width: 100%;
            background: #F5F5F5;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }
        .post-item{
            width: 35%;
            background: white;
            display: flex;
            flex-direction: row;
            padding: 10px;
        }
        .post-item .item-left{
            width: 15%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .item-left img{
            width: 50px;
            height: 50px;
            border-radius: 25px;
        }

        .post-item .item-right{
            width: 80%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .item-right .nickname{
            width: 100%;
        }

        .item-right .content{
            width: 100%;
            display: flex;
            flex-direction: row;
        }

        .item-right .img-container{
            width: 100%;
        }

        .item-right .item-right-sub{
            padding-bottom: 10px;
        }

        .img-container .single-image{
            width: 100%;
        }

        .single-image img{
            width: 300px;
        }

        .item-right .created-time{
            width: 100%;
        }

        .item-right .comment-container{
            background: #F5F5F5;
            width: 100%;
            display: flex;
            flex-direction: column;
            border-radius: 3px;
            padding: 5px;
        }

        .comment-container .praise{
            width: 100%;
            display: flex;
            flex-direction: row;
            padding: 3px;
        }

        .praise .praise-item{
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
        }

        .praise-nickname{
            margin-right: 20px;
        }

        .praise-avatar img{
            width: 30px;
            height: 30px;
            border-radius: 15px;
            margin-right: 5px;
        }

        .comment-container .comment{
            width: 100%;
            display: flex;
            flex-direction: row;
            border-top-style:solid;
            border-width:1px;
            border-color: darkgrey;
            margin-top: 5px;
            padding-top: 5px;
        }

        .comment .comment-item{
            width: 100%;
        }

        .comment-item .reply{
            color: #1E9FFF;
        }

        .more-image{
            display: flex;
            flex-direction: row;
            flex-wrap:wrap;
        }

        .more-image img{
            width: 130px;
            height: 130px;
            margin-right: 10px;
            margin-bottom: 10px;
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
        <blockquote class="layui-elem-quote">共有数据：@{{total}} 条</blockquote>

        <div class="post-container">
            <div class="post-item" v-for="post in posts">
                <div class="item-left">
                    <img v-bind:src="post.poster.avatar" >
                </div>
                <div class="item-right">
                    <div class="nickname item-right-sub">@{{ post.poster.nickname }}</div>
                    <div class="content item-right-sub" v-if="post.topic != '无' " style="color: #1E9FFF"># @{{ post.topic }} #</div>
                    <div class="content item-right-sub">@{{ post.content }}</div>
                    <div class="img-container item-right-sub">
                        <div class="single-image" v-if="post.attachments.length == 1">
                            <img class="image-item" v-bind:src="imageUrl+post.attachments[0]">
                        </div>
                        <div class="more-image" v-if="post.attachments.length > 1">
                            <img class="image-item" v-for="image in post.attachments" v-bind:src="imageUrl+image">
                        </div>
                    </div>
                    <div class="created-time item-right-sub">@{{ post.created_at }}</div>
                    <div class="comment-container">
                        <div class="praise">
                            <div class="praise-item" v-for="praise in post.praises">
                                <div class="praise-avatar">
                                    <img v-bind:src="praise.avatar" alt="">
                                </div>
                                <div class="praise-nickname">@{{ praise.nickname }}</div>
                            </div>
                        </div>
                        <div class="comment">
                            <div class="comment-item">
                                <span class="nickname">叶子</span>
                                <span class="reply">回复</span>
                                <span>你好帅呀水电费水电费第三方第三方第三方士大夫地方大幅度舒服啥地方水电费第三方第三方对方水电费水电费第三方第三方都是</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                posts:[],
                total:0,
                page_size:20,
                current_page:1,
                imageUrl:'http://image.kucaroom.com/'
            },
            created:function () {
                this.getUsers();
                console.log('用户首页');
            },
            methods:{
                handleCurrentChange:function (e) {
                    console.log(e);
                    this.current_page = e;
                    this.getUsers();
                },
                getUsers:function () {
                    axios.get("{{ asset('admin/post/list') }}",{
                        page_size:this.page_size,
                        page_number:this.current_page,
                        order_by:'created_at',
                        sort_by:'desc'
                    }).then( response=> {
                        var res = response.data;
                        this.posts = res.data.page_data;
                        this.total = res.data.page.total_items;
                        console.log(res);
                    }).catch(function (error) {
                        console.log(error);
                    });
                }
            }
        })
    </script>

@endsection