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
<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
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

    ws = new WebSocket("wss://kucaroom.com:8585");

    // 服务端主动推送消息时会触发这里的onmessage
    ws.onmessage = function(e){

        console.log(e.data);

        // json数据转换成js对象
        var data = eval("("+e.data+")");
        var type = data.type || '';
        switch(type){
            // Events.php中返回的init类型的消息，将client_id发给后台进行uid绑定
            case 'init':
                // 利用jquery发起ajax请求，将client_id发给后端进行uid绑定
                $.get('/bind?client_id='+data.client_id, {}, function(data){
                    console.log(data);
                }, 'json');
                break;
            // 当mvc框架调用GatewayClient发消息时直接alert出来
            default :
                alert(e.data);
        }
    };

</script>
</html>