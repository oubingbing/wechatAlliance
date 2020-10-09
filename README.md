小情书是一个开源项目，你可以使用叶子的后台服务，也可以自己搭建后台服务。本文档为独立部署小情书后台服务的教程。

有空帮忙点一下右上角的start，谢谢

前端源码在这里：https://github.com/oubingbing/school_wechat

## 项目环境要求

    PHP 7.0以上
    
    MySQL 5.7
## docker部署方式（推荐）
使用docker部署，只需要五分钟即可，方便快捷，只需要配置一个docker-compose文件即可
### 一、部署docker环境（ubuntu）

##### Docker 官方为了简化安装流程，提供了一套便捷的安装脚本，Ubuntu 系统上可以使用这套脚本安装：
`` $ curl -fsSL get.docker.com -o get-docker.sh ``

`` $ sudo sh get-docker.sh --mirror Aliyun ``
执行这个命令后，脚本就会自动的将一切准备工作做好，并且把 Docker CE 的 Edge 版本安装在系统中。

#### 启动 Docker CE
``` $ sudo systemctl enable docker ```

``` $ sudo systemctl start docker ```

##### 输入以下命令检测docker是否安装好了
``` $ docker -v ```

如果打印出docker的版本信息即安装成功

### 二、安装docker-compose

如果你的操作系统是window或者mac的，安装docker的时候已经包含在里面两位，无需再单独安装，如果你的是linux系统，需要按照下面的方法安装即可。

在 Linux 上的也安装十分简单，从 官方 GitHub Release 处直接下载编译好的二进制文件即可。

例如，在 Linux 64 位系统上直接下载对应的二进制包。

``` $ sudo curl -L https://github.com/docker/compose/releases/download/1.17.1/docker-compose-`uname -s`-`uname -m` > /usr/local/bin/docker-compose ```

``` $ sudo chmod +x /usr/local/bin/docker-compose```

如果安装docker-compose遇到问题可以直接搜索相关资料，有很多解决方案。

### 三、使用docker-compose.yml部署小情书后台
1、把小情书后台源码中的docker-compose.yml贴到你需要部署的目录中，然后配置后台项目需要的参数

docker-compose.yml文件
![](http://article.qiuhuiyi.cn/Fi6KA0gsV62l8vjVH5nEx2yBVPce)

2、编辑docker-compose.yml文件，填写项目参数
只需要编辑红框中的这些参数即可，根据提示填上相应的参数，然后保存即可，数据库名称必须为love_wall，数据库的用户名称必须为root。
![](http://article.qiuhuiyi.cn/FgLUbgltLK7b0SLrcMZn7Djvs65F)

3、启动项目，执行命令

`docker-compose -up -d`

第一次启动会有点久，因为第一次需要拉取镜像，耐心等待

项目启动完成，docker-compose会启动
 - nginx
 - php-fpm
 - mysql
 - phpmyadmin.

#### 注意：
#### 本地通过访问127.0.0.1:8000即可访问项目
#### phpmyadmin通过127.0.0.1:8080访问，host为db，把项目目录下的love_wall.sql导入数据库即可

如果你是在本地window或者mac开发环境可以直接这样访问，如果是线上的云主机可以使用你的IP+端口来访问，云主机需要防火墙放开8000和8080端口才能访问，本地直接在浏览器打开访问即可，如果你想通过域名访问，需要在宿主机器上部署nginx来转发请求到127.0.0.1:8000和127.0.0.1:8080去访问项目和phpmyadmin

4、停止项目，执行以下命令停止项目

`docker-compose down`

如果想再次启动项目执行docker-compose up -d即可



## 手动部署方式
### 一、下载后台源码

##### 1.1直接下载后台源码

github源码地址：https://github.com/oubingbing/wechatAlliance.git

直接点击download下载源码即可

## 别忘了点右上角的star哈
<img src="http://article.qiuhuiyi.cn/Fl9uqGovXBsPWr7LoianLXAjJS3w" alt="Build Status">

##### 1.2使用git获取源码

在Git输入命令

    git clone https://github.com/oubingbing/wechatAlliance.git

二、安装PHP包管理工具composer

安装 - Windows

使用安装程序
这是将 Composer 安装在你机器上的最简单的方法。

下载并且运行 Composer-Setup.exe，它将安装最新版本的 Composer ，并设置好系统的环境变量，因此你可以在任何目录下直接使用 composer 命令。

composer下载地址：https://getcomposer.org/Composer-Setup.exe

直接下载运行即可。

安装完成后在终端输入

composer -v看到下图就说明安装成功了

<img src="http://article.qiuhuiyi.cn/FlPyixDSp7YQPNRHwi8mrpm7gIiX" alt="Build Status">

### 三、安装PHP的laravel框架

php的laravel框架是一款非常优秀的php框架，如果有兴趣的可以学一下。

这是中文文档的网站：https://laravelacademy.org/

源码下载后进入项目根目录使用cmd命令行工具或者其他的终端工具都是可以，数据命令:

    composer install

安装laravel框架
<img src="http://article.qiuhuiyi.cn/Fgau-lnInun7-SdCP330bCrIe-xG" alt="Build Status">

等他安装完成就可以了。

安装完成后输入

    composer dump-autoload

### 四、配置项目

将项目根目录下的.env.example文件重命名为 **.env** 文件

    APP_NAME=小情书
    APP_ENV=local(开发的时候是local,部署的时候改为prod)
    APP_KEY=(laravel 的secret key) 
    APP_DEBUG=true(开发的时候是false,部署的时候改为true)
    APP_LOG_LEVEL=debug
    APP_LOG=daily
    APP_URL=https://kucaroom.com
    
    DB_CONNECTION=mysql
    DB_HOST=(数据库所在的主机IP地址)
    DB_PORT=3306
    DB_DATABASE=
    DB_USERNAME=
    DB_PASSWORD=
    
    BROADCAST_DRIVER=log
    CACHE_DRIVER=file
    SESSION_DRIVER=file
    SESSION_LIFETIME=120
    QUEUE_DRIVER=sync
    
    REDIS_HOST=
    REDIS_PASSWORD=
    REDIS_PORT=6379
    
    MAIL_DRIVER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    
    PUSHER_APP_ID=
    PUSHER_APP_KEY=
    PUSHER_APP_SECRET=
    
    API_PREFIX=api
    JWT_SECRET=
    
    QI_NIU_ACCESS_KEY=
    QI_NIU_SECRET_KEY=
    BUCKET_NAME=
    QI_NIU_DOMAIN=
    
    SEND_CLOUD_API_USER=
    SEND_CLOUD_APP_KEY=
    
    YUN_PIAN_SINGLE_URL=
    YUN_PIAN_MULTI=
    YUN_PIAN_KEY=
    
    ALI_ID=
    ALI_SECRET=
    ALI_URL=


##### 1、生成app_key 
终端输入：php artisan key:generate
然后会显示一下信息，复制 [] 中括号的字符串贴到.env的APP_KEY就行了
Application key [base64:3ZYAJ6R5fzNcQpc1kfEuhMQJZU06HUXt93BS92UK8Pc=] set successfully.

##### 2、APP_URL=https://kucaroom.com ，应用的域名，发邮箱激活链接用的，填上你的项目域名即可

##### 3、数据库，输入你数据对应的信息即可

        DB_CONNECTION=mysql
        DB_HOST=(数据库所在的主机IP地址)
        DB_PORT=3306
        DB_DATABASE=
        DB_USERNAME=
        DB_PASSWORD=

##### 4、redis缓存，配置你的Redis账号密码

        REDIS_HOST=
        REDIS_PASSWORD=
        REDIS_PORT=6379

##### 5、生成jwt key，用dingo api

输入命令：php artisan jwt:secret

结果如下，复制中括号的字符串到    JWT_SECRET= 即可

    jwt-auth secret [pV7G5egB2TfcLwpc3J8xEqiudof5SxyM] set successfully.

##### 6、七牛配置

在七牛注册一个账号，获取到七牛的access_key，和secret_key以及存储桶的名字填到下面就行了，用于小程序的图片上传，存储区域最好选择华南区也就是 Z2。

        QI_NIU_ACCESS_KEY=
        QI_NIU_SECRET_KEY=
        BUCKET_NAME=
        QI_NIU_DOMAIN= //你的七牛域名

##### 7、sendcloud邮件配置

注册一个sendcloud账号，配置下面的信息

        SEND_CLOUD_API_USER=
        SEND_CLOUD_APP_KEY=


##### 8、配置云片短信

用于短信的发送

        YUN_PIAN_SINGLE_URL=
        YUN_PIAN_MULTI=
        YUN_PIAN_KEY=
        
##### 9、配置阿里云开发者信息

    ALI_ID=
    ALI_SECRET=
    ALI_URL=

阿里人脸识别地址：

    https://data.aliyun.com/product/face?spm=5176.10609282.1146454.885.21d538010MzGRj#face-verify

短信验证码的文字信息在
    wechatAlliance\app\Http\Service\YunPianService.php中的sendMessageCode($phone)修改。

表白帖子的短信文本信息在
    wechatAlliance\app\Http\Controllers\Wechat\PostController.php中的store()修改

需要你在云片备案相关的短信模板，根据自己的需求，填入模板的信息。

### 五、生成数据库表

在项目根目录运行

    php artisan migrate

进行数据表迁移，MySQL一定要5.7以上，否则会报错

数据表文件在这个目录下：**wechatAlliance\database\migrations**

### 六、运行项目

在项目根目录运行

php artian serve

项目就可以跑起来的，在浏览器输入

http://127.0.0.1:8000

就可以访问项目了

## 正式部署到linux上后，只需要把80或者443端口的请求指向项目的public目录就可以了

### 觉得对你有帮助的话，可以打赏一下作者，谢谢啦。
<img src="http://article.qiuhuiyi.cn/hui_yi_15398317840008242" alt="">

