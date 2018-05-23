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
                <image class="wechat-image" src="http://image.kucaroom.com/16882398.jfif"></image>
            </div>
            <h3>叶子</h3>
            <p>一个想创造美好事物的人儿</p>
            <a href="https://github.com/oubingbing" class="github">https://github.com/oubingbing</a>
        </div>
    </div>
@stop