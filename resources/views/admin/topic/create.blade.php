@extends('layouts/admin')
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <style>
    </style>
    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
    <script src="https://unpkg.com/element-ui/lib/index.js"></script>
    <div class="x-body" id="app">
        <form class="layui-form">
            <div class="layui-form-item">
                <label for="username" class="layui-form-label">
                    标题
                </label>
                <div class="layui-input-inline">
                    <input type="text" lay-verify="required" v-model="title" class="layui-input" placeholder="标题" style="width: 500px">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="phone" class="layui-form-label">
                    <span class="x-red">*</span>内容
                </label>
                <div class="layui-input-inline">
                    <textarea rows="3" cols="50" v-model="content" required="" class="layui-input" style="height:150px;width: 500px" placeholder="内容">

                    </textarea>
                </div>
            </div>

            <div class="layui-form-item">
                <label for="phone" class="layui-form-label">
                </label>
                <div class="layui-input-inline">
                    <el-upload
                            :action="domain"
                            class="upload-demo"
                            :on-remove="handleRemove"
                            :on-success="uploadSuccess"
                            :file-list="fileList"
                            :on-change="handleChange"
                            list-type="picture">
                        <el-button size="small" type="primary">点击上传</el-button>
                        <div slot="tip" class="el-upload__tip" style="width:500px">只能上传jpg/png文件，且不超过500kb</div>
                    </el-upload>
                </div>
            </div>

            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                </label>
                <button type="button"  class="layui-btn" lay-filter="add" v-on:click="submit">新建</button>
            </div>
        </form>
    </div>
    <script>

        new Vue({
            el: '#app',
            data: {
                domain:'https://up-z2.qbox.me',
                fileList:[],
                images:[],
                image:{},
                title:'',
                content:''
            },
            created:function () {
                console.log('用户首页');

                this.getUploadToken();
            },
            methods:{
                /**
                 * 监听图片切换事件
                 */
                handleChange:function (file, fileList) {
                    console.log('file:'+file.name);
                    this.image.name = file.name;
                },
                /**
                 * 移除图片
                 */
                handleRemove:function (file) {
                    var fileName = file.name;
                    var imageData = this.images;
                    imageData.map((item,index)=>{
                        if(item.name == fileName){
                            this.images.splice(index,1);
                        }
                    });
                    console.log('图片数组为：'+JSON.stringify(this.images));
                },
                /**
                 * 提交数据
                 */
                submit:function () {
                    if(this.content == ''){
                        layer.msg('内容不能为空！');
                        return false;
                    }

                    axios.post("{{ asset("admin/topic/create") }}",{
                        title:this.title,
                        content:this.content,
                        attachments:this.images
                    }).then( response=> {
                        var res = response.data;
                        if(res.code === 200){
                            layer.msg(res.message);
                            setTimeout(function () {
                                window.location.href = "{{ asset('admin/topic') }}";
                                var index = parent.layer.getFrameIndex(window.name);
                                //关闭当前frame
                                parent.layer.close(index);
                            },1000)
                        }else{
                            layer.msg('新建失败');
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
                            this.domain = this.domain+'?token='+response.data.data;
                            console.log(this.postData.token);
                        }).catch(function (error) {
                        console.log(error);
                    });
                },
                /**
                 * 监听上传成功回调
                 * @param res
                 */
                uploadSuccess:function (res) {
                    this.image.key = res.key;
                    if(this.image.name != '' && this.image.key != ''){
                        this.images.push(this.image);
                    }

                    this.image = {};
                }
            }
        })
    </script>

@endsection