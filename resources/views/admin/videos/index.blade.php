@extends('layouts/admin')
@section('content')
    <style>
        [v-cloak] {
            display: none;
        }
    </style>
    <link rel="stylesheet" href="{{asset('css/element-ui-index.css')}}">
    <link rel="stylesheet" href="{{asset('css/bank.css')}}">
    <div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">银行</a>
        <a>
          <cite>首页</cite></a>
      </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
            <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="x-body" id="app" v-cloak>
        <xblock>
            <button class="layui-btn" v-on:click="showBankView"><i class="layui-icon"></i>添加</button>
            <span class="x-right" style="line-height:40px">共有数据：@{{ total }} 条</span>
        </xblock>

        <!-- 添加银行的页面 -->
        <div class="add_bank" v-if="showBankForm" style="background: white">
            <form class="layui-form bank-form" style="display: flex;flex-direction: column;width: 500px">
                <div class="close-view">
                    <img class="close-button" v-on:click="closeBankForm" src="{{asset('images/close.png')}}" alt="">
                </div>

                <div class="layui-form-item" style="display: flex;flex-direction: row">
                    <label for="username" class="layui-form-label">
                        <span class="x-red">*</span>视频id
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" lay-verify="required" v-model="videos" class="layui-input" >
                    </div>
                </div>

                <div class="layui-form-item" style="display: flex;flex-direction: row">
                    <label for="username" class="layui-form-label">
                        <span class="x-red">*</span>标题
                    </label>
                    <div class="layui-input-inline">
                        <textarea type="text" lay-verify="required" v-model="title" class="layui-textarea" ></textarea>
                    </div>
                </div>

                <div class="layui-form-item" style="display: flex;flex-direction: row">
                    <label for="username" class="layui-form-label">
                        <span class="x-red">*</span>序号
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" lay-verify="required" v-model="sort" class="layui-input" >
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                    </label>
                    <div  class="layui-btn" lay-filter="add" v-on:click="submitCategoryInfo">
                        提交
                    </div>
                </div>
            </form>
        </div>

        <table class="layui-table">
            <thead>
            <tr>
                <th>序号</th>
                <th>v_id</th>
                <th>标题</th>
                <th>排序</th>
                <th>创建时间</th>
                <th>操作</th>
            </thead>
            <tbody>
            <tr v-for="(v,index) in list">
                <td>@{{ index+1 }}</td>
                <td>@{{ v.v_id }}</td>
                <td>@{{ v.title }}</td>
                <td>@{{ v.sort }}</td>
                <td>@{{ v.created_at }}</td>

                <td class="td-manage" style="float: left">
                    <i class="layui-icon" v-on:click="edit(v)"></i>
                    <i class="layui-icon" v-on:click="deleteVideo(v.id)"></i>
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
    <script src="{{asset('js/element-ui-index.js')}}"></script>
    <script>
        "use strict";
        new Vue({
            el: '#app',
            data: {
                list:[],
                total:0,
                page_size:20,
                current_page:1,
                videos:'',
                title:'',
                sort:1,
                showBankForm:false,
                operateType:'create',
                editId:''
            },
            created:function () {
                this.getVideos();
            },
            methods:{
                showBankView:function () {
                    this.showBankForm = true;
                    this.operateType = 'create';
                },
                closeBankForm:function () {
                    this.showBankForm = false;
                    this.videos = '';
                    this.title = '';
                    this.sort = '';
                },
                edit:function (video) {
                    this.showBankForm = true;
                    this.operateType = 'edit';
                    this.videos = video.v_id;
                    this.title = video.title;
                    this.sort = video.sort;
                    this.editId = video.id;
                },
                submitCategoryInfo:function () {
                    let videos = this.videos;
                    if(!videos){
                        layer.msg("视频链接不能为空");
                        return false;
                    }

                    if(this.operateType == 'create'){
                        axios.post(`/admin/videos/create`,{
                            v_id:videos,
                            title:this.title,
                            sort:this.sort
                        }).then( response=> {
                            console.log(response);
                            let ResData = response.data;
                            if(ResData.error_code == 500){
                                layer.msg(ResData.error_message);
                            }else{
                                layer.msg(ResData.error_message);
                                this.list.unshift(ResData.data);
                                this.videos = '';
                                this.title = '';
                                this.sort = '';
                            }
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }else{
                        let editID = this.editId;
                        axios.post(`/admin/videos/${editID}/update`,{
                            v_id:videos,
                            title:this.title,
                            sort:this.sort
                        }).then( response=> {
                            console.log(response);
                            let ResData = response.data;
                            if(ResData.error_code == 500){
                                layer.msg(ResData.error_message);
                            }else{
                                layer.msg(ResData.error_message);
                                this.showBankForm = false;
                                this.list = this.list.map(item=>{
                                    if(item.id == editID){
                                        item.v_id = videos;
                                        item.title = this.title;
                                        item.sort = this.sort;
                                    }
                                    return item;
                                });
                                this.showBankForm = false;
                            }
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }
                },
                /**
                 * 删除评论
                 */
                deleteVideo:function (id) {
                    this.$confirm('确定要删除吗', '警告', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        axios.delete(`/admin/videos/${id}/delete`,{}).then( response=> {
                            let ResData = response.data;
                            if(ResData.error_code == 500){
                                layer.msg(ResData.error_message);
                            }else{
                                this.list = this.list.filter(item=>{
                                    if(item.id != id){
                                        return item;
                                    }
                                })
                                layer.msg("删除成功");
                            }
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }).catch(() => {
                        this.$message({
                            type: 'info',
                            message: '已取消删除'
                        });
                    });
                },
                /**
                 * 监听分页
                 */
                handleCurrentChange:function (e) {
                    console.log(e);
                    this.current_page = e;
                    this.getVideos();
                },

                /**
                 * 获取一个帖子列表
                 */
                getVideos:function () {
                    var url = "{{ asset('admin/videos') }}";
                    axios.get(url+"?page_size="+this.page_size+'&page_number='+this.current_page+'&order_by=created_at&sort_by=desc',{
                        page_size:this.page_size,
                        page_number:this.current_page,
                        order_by:'created_at',
                        sort_by:'desc'
                    }).then( response=> {
                        var res = response.data;
                        this.list = res.data.page_data;
                        this.total = res.data.page.total_items;
                    }).catch(function (error) {
                        console.log(error);
                    });
                }
            }
        })
    </script>

@endsection