@extends('layouts/gateway')
<style>
    .wechat-image{
        width: 150px;
        height: 150px;
    }
    .github{
        background: white;
        padding: 5px;
        border-radius: 4px;
        font-size: 20px;
    }
</style>
@section('content')
    <div class="container">
        <div class="jumbotron">
            <div>
                <image class="wechat-image" src="http://image.kucaroom.com/qrcode_for_gh_d03b07b4462b_344 (1).jpg"></image>
            </div>
            <h3>公众号--湛江市赤坎区古卡饮品店</h3>
            <p>关注公众号，可以查看小情书的部署教程和使用手册</p>
            <p>我们是一个小小的情书联盟，也是一群有梦想的bug搬运工，我们一起学习一起成长。</p>
            <a href="https://github.com/oubingbing" class="github" target="_blank">https://github.com/oubingbing</a>
        </div>
    </div>
@stop