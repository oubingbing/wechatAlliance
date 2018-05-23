<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>测试socket</title>
</head>
<style>
    *{
        margin: 0px;
        padding: 0px;
    }
    .room{
        background: darkgray;
    }
</style>
<link href="https://cdn.bootcss.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.bootcss.com/socket.io/2.0.4/socket.io.js"></script>
<script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
<body>

<div id="app">
    <div class="room" v-bind:style="{ width:room_width,height:room_height}">
        <ul id="app">
            <li v-for="item in items">
                @{{ item }}
            </li>
        </ul>

    </div>
</div>

</body>
<script>
    var socket = io('127.0.0.1:3000');
    var width = document.body.clientWidth;
    var height = document.body.clientHeight;

    new Vue({
        el: '#app',
        data: {
            items:[],
            room_width:width+'px',
            room_height:height+'100px'
        },
        mounted() {
            console.log('vue');
            socket.on('test_redis',function (data) {
                var content = JSON.parse(data);
                this.items.push(content.data.name)
            }.bind(this));
        }
    })


</script>
</html>