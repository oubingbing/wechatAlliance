@extends('layouts/admin')
@section('content')
    <link rel="stylesheet" href="{{asset('css/element-ui-index.css')}}">
    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
    <script src="{{asset('js/element-ui-index.js')}}"></script>
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
            border-top-style:solid;
            border-width:1px;
            border-color: #F5F5F5;
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
            flex-direction: column;
        }

        .comment .comment-item{
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .comment-item .item{
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
            <div class="layui-form layui-col-md12 x-so">
                <input type="text" name="username" v-model="username"  placeholder="请输入用户名" autocomplete="off" class="layui-input">
                <button class="layui-btn"  lay-submit="" lay-filter="sreach" v-on:click="searchUser"><i class="layui-icon">&#xe615;</i></button>
            </div>
        </div>
        <blockquote class="layui-elem-quote">共有数据：@{{total}} 条</blockquote>

        <div class="post-container">
            <div class="post-item" v-for="face in faces">
                <div class="item-left">
                    <img v-bind:src="face.poster.avatar" >
                </div>
                <div class="item-right">
                    <div class="nickname item-right-sub">@{{ face.poster.nickname }}</div>
                    <div class="content item-right-sub">相似度：@{{ face.confidence }}</div>
                    <div class="img-container item-right-sub">
                        <div class="more-image">
                            <img class="image-item" v-bind:src="face.attachments['rect_a']">
                            <img class="image-item" v-bind:src="face.attachments['rect_b']">
                        </div>
                    </div>
                    <div class="created-time item-right-sub">@{{ face.created_at }}</div>
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
        "use strict";
        new Vue({
            el: '#app',
            data: {
                faces:[],
                total:0,
                page_size:20,
                current_page:1,
                imageUrl:"{{env('QI_NIU_DOMAIN')}}"+"/",
                username:""
            },
            created:function () {
                this.getPosts();
                console.log('用户首页');
            },
            methods:{
                /**
                 * 监听分页
                 */
                handleCurrentChange:function (e) {
                    console.log(e);
                    this.current_page = e;
                    this.getPosts();
                },
                searchUser:function () {
                    this.current_page=1;
                    this.getPosts();
                },
                /**
                 * 获取一个帖子列表
                 */
                getPosts:function () {
                    var url = "{{ asset('admin/compare_faces') }}";
                    axios.get(url+"?page_size="+this.page_size+'&page_number='+this.current_page+'&order_by=created_at&sort_by=desc&username='+this.username,{
                        page_size:this.page_size,
                        page_number:this.current_page,
                        order_by:'created_at',
                        sort_by:'desc'
                    }).then( response=> {
                        var res = response.data;
                        let list = res.data.page_data;
                        list = list.map(item=>{
                            let a = item.attachments.rect_a.split("/")
                            if(a[2] == 'image.kucaroom.com'){
                                item.attachments.rect_a = "http://img.qiuhuiyi.cn/"+a[3]
                            }else{
                                item.attachments.rect_a = "{{env('QI_NIU_DOMAIN')}}"+"/"+a[3]
                            }

                            let b = item.attachments.rect_b.split("/")
                            console.log(b[2])
                            if(b[2] == 'image.kucaroom.com'){
                                item.attachments.rect_b = "http://img.qiuhuiyi.cn/"+b[3]
                            }else{
                                item.attachments.rect_b = "{{env('QI_NIU_DOMAIN')}}"+"/"+b[3]
                            }

                            return item;
                        })

                        this.faces = list;
                        this.total = res.data.page.total_items;
                    }).catch(function (error) {
                        console.log(error);
                    });
                }
            }
        })
    </script>

@endsection