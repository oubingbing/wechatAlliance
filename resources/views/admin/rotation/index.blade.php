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

        .upload-container{
            width: 30%;
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
        <xblock>
            <div class="upload-container">
                <el-upload
                        :action="upLoadDomain"
                        class="upload-demo"
                        :on-remove="handleRemove"
                        :on-success="uploadSuccess"
                        list-type="picture">
                    <el-button size="small" type="primary">点击图片</el-button>
                </el-upload>
            </div>
            <span class="x-right" style="line-height:40px">共有数据：@{{ total }} 条</span>
        </xblock>

        <table class="layui-table">
            <thead>
            <tr>
                <th>图片</th>
                <th>跳转小程序的ID</th>
                <th>跳转小程序的路径</th>
                <th>创建时间</th>
                <th>操作</th>
            </thead>
            <tbody>
            <tr v-for="topic in topics">
                <td>
                    <div class="image-container">
                        <div class="image"><img v-bind:src="topic.image" alt="" style="width: 80px;height: 80px"></div>
                    </div>
                </td>
                <td>
                    <div class="layui-input-inline">
                        <input type="text" @blur="update(topic.id,topic.wechat_app)" lay-verify="required" v-model="topic.wechat_app" class="layui-input" placeholder="小程序ID" name="app_id" style="width: 250px;float: left">
                    </div>
                </td>
                <td>
                    <div class="layui-input-inline">
                        <input type="text" @blur="updateUrl(topic.id,topic.url)" lay-verify="required" v-model="topic.url" class="layui-input" placeholder="小程序ID" name="app_id" style="width: 250px;float: left">
                    </div>
                </td>
                <td>@{{ topic.created_at }}</td>
                <td class="td-manage">
                    <button class="layui-btn layui-btn-danger" v-on:click="deleteImg(topic.id)">删除</button>
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
        "use strict";
        new Vue({
            el: '#app',
            data: {
                topics:[],
                total:0,
                page_size:20,
                current_page:1,
                upLoadDomain:'https://up-z2.qbox.me',
                appImageUrl:'',
                attachments:[],
                imageUrl:"{{env('QI_NIU_DOMAIN')}}"+"/"
            },
            created:function () {
                this.getList();
                this.getUploadToken();
            },
            methods:{

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
                    axios.patch('/admin/rotation_store',{image:this.appImageUrl})
                        .then( response=> {
                            var res = response.data;
                            if(res.error_code == 200){
                                let data = this.topics
                                data.push(res.data)
                                this.topics = data
                                layer.msg('上传成功！');
                            }else{
                                layer.msg('上传失败！');
                            }

                        }).catch(function (error) {
                        console.log(error);
                    });
                },

                deleteImg:function (id) {
                    axios.delete("{{ asset('/admin/rotation_delete') }}?id="+id)
                        .then( response=> {
                            console.log(response.data)
                            layer.msg(response.data.error_message);
                            let data = this.topics
                            if (response.data.error_code == 200){
                                data = data.filter(function(item){
                                    if (item.id != id){
                                        return item
                                    }
                                })
                                this.topics = data
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

                updateUrl:function (id,value) {
                    axios.patch('/admin/rotation_update_url',{id:id,url:value})
                        .then( response=> {
                            var res = response.data;
                            if(res.error_code == 200){
                                let data = this.topics
                                data.push(res.data)
                                this.topics = data
                                layer.msg('修改成功！');
                            }else{
                                layer.msg('修改失败！');
                            }

                        }).catch(function (error) {
                        console.log(error);
                    });
                },

                updateAppId:function (id,value) {
                    axios.patch('/admin/rotation_update',{id:id,wechat_id:value})
                        .then( response=> {
                            var res = response.data;
                            if(res.error_code == 200){
                                let data = this.topics
                                data.push(res.data)
                                this.topics = data
                                layer.msg('修改成功！');
                            }else{
                                layer.msg('修改失败！');
                            }

                        }).catch(function (error) {
                        console.log(error);
                    });
                },

                uploadImage:function () {

                },
                /**
                 * 获取话题列表
                 * */
                getList:function () {
                    var url = "{{ asset("admin/rotation_list") }}";
                    axios.get(url+"?page_size="+this.page_size+'&page_number='+this.current_page+'&order_by=created_at&sort_by=desc')
                        .then( response=> {
                            var res = response.data;
                            if(res.error_code === 200){
                                this.topics = res.data.page_data;
                                this.total = res.data.page.total_items;
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
                            if(res.error_code === 200){
                                layer.msg(res.error_message);
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
                            if(res.error_code === 200){
                                layer.msg(res.error_message);
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