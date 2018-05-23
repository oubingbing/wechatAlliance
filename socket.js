
var server = require('http').createServer();
var io = require('socket.io')(server);
var Redis = require('ioredis');
var redis = new Redis({
    port: 6379,
    host: '127.0.0.1',
    family: 4,
    password: '@Bingbing925455',
    db: 0
});

//订阅事件
redis.subscribe('test_redis');

redis.on('message',function (channel,message) {
    console.log(message);

    //发布事件给前端
    io.emit(channel,message);
});

server.listen(3000);