## docker部署方式
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

如果你的操作系统是window或者mac的，安装docker的时候已经包含在里面了，无需再单独安装，如果你的是linux系统，需要按照下面的方法安装即可。

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