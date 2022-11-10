部署

## 宝塔部署

### 一、安装宝塔面板

了解宝塔所需环境和SSH（远程连接）工具的用法后，就可以正式的开始安装面板。



查看下面的命令，选择合适自己Linux系统的安装脚本进行安装，或者在[宝塔Linux面板](https://www.bt.cn/download/linux.html)上查看脚本安装地址。

Centos安装命令：

> ```shell
> yum install -y wget && wget -O install.sh http://download.bt.cn/install/install.sh && sh install.sh
> ```

Ubuntu/Deepin安装命令：

> ```shell
> wget -O install.sh http://download.bt.cn/install/install-ubuntu.sh && sudo bash install.sh
> ```

Debian安装命令：

> ```shell
> wget -O install.sh http://download.bt.cn/install/install-ubuntu.sh && bash install.sh
> ```

Fedora安装命令:

> ```shell
> wget -O install.sh http://download.bt.cn/install/install.sh && bash install.sh
> ```

安装完成后，会提示安装成功的提示，面板地址，面板账号还有密码,记住它们，稍后会用到，（如图）：

![](http://ww1.sinaimg.cn/large/0079MVdAly1fypxnd2tdgj30lb06i3zo.jpg)

### 二、安装LNMP

安装完成在浏览上打开面板的地址，登入面板，面板会自动推荐你安装环境套件，这里有两种选择，第一种是LNMP套件，第二种是LAMP套件（如图）：

![](http://ww1.sinaimg.cn/large/0079MVdAly1fypxo3j3hhj30mz0euq4z.jpg)

LNMP和LAMP环境主要的区别在于web服务器上面，一个使用是Apache服务器、一个使用Nginx服务器。

**注意，这里我们安装lnmp环境** 。所以请取消勾选lamp环境。并且取消勾选FTP

- **Mysql版本选择5.7（否则稍后会报连接错误）**

- **PHP选择7.2版本**
- 安装方式两种都可以。



选择安装后的程序后，面板的左上角，会自动显示任务的数量，点击后进入任务列表（如图）：

![](http://ww1.sinaimg.cn/large/0079MVdAly1fypxol5v3oj30m90l240o.jpg)

在当前界面会显示程序的安装进度，或程序执行进度等。等待一段时间后，查看任务列表中的信息，查看是否完成。

安装完LNMP后，还需要到软件管理安装Redis。

**完成后进入软件管理，选择我们安装的PHP版本，选择设置->安装扩展->安装redis**



### 三、安装PHP包管理工具composer

- 下载Composer

  `curl -sS https://getcomposer.org/installer | php`

- 设置全局命令

  `sudo mv composer.phar /usr/local/bin/composer`

- 查看安装结果

  `composer -v`

  如果出现下图，则安装成功

  ![](http://ww1.sinaimg.cn/large/0079MVdAly1fypwgffxq9j30qq0eogml.jpg)

- 切换为阿里源
  - 首先把默认的源给禁用掉
  composer config -g secure-http false

  - 再修改镜像源阿里的源
  composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

### 四、搭建网站

使用SSH远程连接工具（推荐Xshell）连接服务器后执行如下命令

- 进入相应目录

`cd /www/wwwroot`

- 使用Git下载后台源代码并且指定下载文件夹为xiaoqingshu

`git clone https://github.com/oubingbing/wechatAlliance.git xiaoqingshu`

- 进入网站目录

  `cd xiaoqingshu`

- 安装PHP的laravel框架

  `composer install`

  此命令会自动安装项目所需其他依赖，比如各种第三方包等等。若出现如下截图则成功

  ![](http://ww1.sinaimg.cn/large/0079MVdAly1fypwlccwwuj30ms098dgs.jpg)

- 创建网站

  打开宝塔->进入网站->选择添加站点->填写内容->提交。如图

  ![](http://ww1.sinaimg.cn/mw690/0079MVdAly1fypwwjg8hdj30mc0izt9p.jpg)



  域名和数据库账号密码请替换成自己想填的密码，并且牢记。根目录要指定为刚才我们下载源码的目录。

- 配置网站目录

 ![](http://article.qiuhuiyi.cn/FktDDH0qnahI4zNKm1Cep5dNXO2u)

  Laravel框架需要指定运行目录，按照上图配置即可。

- 配置伪静态

  规则如下

  location / {  
    try_files $uri $uri/ /index.php$is_args$query_string;  
  }  

  ![](http://ww1.sinaimg.cn/mw690/0079MVdAly1fypx0zc2fgj30mh0gggmj.jpg)

  将规则填入后保存。

- 配置项目

  将项目根目录下的.env.example文件重命名为 **.env** 文件

  `mv .env.example .env`

- 生成appkey

  `php artisan key:generate`

- 编辑.env文件

  在Linux终端下编辑文件需要用到Vi命令，参考这里，学习Vi命令的使用   [Vi的使用](https://www.vpser.net/manage/vi.html)
  - 配置App_url

    ​	APP_URL=https://kucaroom.com，应用的域名，发邮箱激活链接用的，填上你的项目域名即可.

  - 配置数据库

  - ```
    DB_CONNECTION=mysql
    DB_HOST=(数据库所在的主机IP地址)
    DB_PORT=3306
    DB_DATABASE=填入你刚才的数据库名
    DB_USERNAME=数据库用户名
    DB_PASSWORD=数据库密码
    ```

  其他修改请参考.env文件相关说明注释。

- 配置JWT

  在终端xiaoqingshu目录下执行以下命令

  `php artisan jwt:secret`

- 导入数据库表

  在项目根目录上有一个love_wall.sql的文件，导入自己的数据库中即可

### 五、关于情侣脸参数配置

  情侣脸的接口可以使用阿里云或腾讯云的人脸1:1对比的套餐包，因为阿里云需要企业认证但是便宜，而腾讯云不需要认证个人也可以用但是比较贵，所以可以自己取舍到底用哪种
  .env文件里配置参数

```

    # 情侣脸接口商类型，1=阿里云，2=腾讯云
    COMPARE_FACE_TYPE = 1

    # 阿里云情侣脸配置参数
    ALI_ID=
    ALI_SECRET=
    ALI_ENDPOINT=
    ALI_ENDPOINT_ID=cn-shanghai

    # 腾讯云情侣脸配置参数，region最好选择华南地区广州值为ap-guangzhou
    TENCENT_SECRET_ID=
    TENCENT_SECRET_KEY=
    TENCENT_REGION=ap-guangzhou```json
    # 情侣脸接口商类型，1=阿里云，2=腾讯云
    COMPARE_FACE_TYPE = 1

    # 阿里云情侣脸配置参数
    ALI_ID=
    ALI_SECRET=
    ALI_ENDPOINT=
    ALI_ENDPOINT_ID=cn-shanghai

    # 腾讯云情侣脸配置参数，region最好选择华南地区广州值为ap-guangzhou
    TENCENT_SECRET_ID=
    TENCENT_SECRET_KEY=
    TENCENT_REGION=ap-guangzhou
```

### 六、常见问题

##### 如果您的站点搭建完成后，访问站点跑不起来的话，可以参考以下解决方案。

- 执行composer install提示 putenv() has been disabled for security reasons

  只需要找到您的php.ini文件，在里面找到putenv()然后将其删掉即可

- 执行composer install提示fileinfo扩展问题

  问题如下图

  ![](http://article.qiuhuiyi.cn/FnmFXPqfF-Hqh1a5E5i0csx0NLZU)

  1、找到您的php.ini文件，将;extension=fileinfo前面的冒号删除保存即可

  2、安装fileinfo扩展，找到您安装使用的对应的php版本进行安装fileinfo扩展，宝塔操作如下

  ![](http://article.qiuhuiyi.cn/FtPXFqdElHUqhN2IJwGpdRgj1jFd)

  等待扩展安装完成即可

- 执行composer install提示he Process class relies on proc_open, which is not available on your PHP installation.

  找到您的php.ini文件，在里面找到proc_open()删除即可

- 访问站点提示 HTTP ERROR 500 该网页无法正常运作

  这是由于项目目录下的 /storage 目录没有读写权限，项目日志没法写入导致的，只需要给该目录读写权限即可，可以登录终端进入项目目录下，执行以下命令后就能正常访问了

  `sudo chmod -R 755 ./storage/`

- 确认.env配置参数JWT_SECRET
  有些部署成功了但是在登录的时候出现问题，可能是.env配置文件中JWT_SECRET参数未配置，该参数为jwt的加密串，执行命令php artisan jwt:secret即可生成。

- 登录后台后项目目录下storage/log今天的日志出现错误：Unable to boot ApiServiceProvider, configure an API domain or prefix
  后台登录报错截图如下
  ![](http://article.qiuhuiyi.cn/WechatIMG71.png)

  日志提示如下
  ![](http://article.qiuhuiyi.cn/WechatIMG73.png)

  这是因为你的php有个函数putenv()被禁用了，在PHP中删除即可，操作如下
  ![](http://article.qiuhuiyi.cn/WechatIMG72.png)

- 执行composer install的时候出现类似的提示
  
  [Composer\Downloader\TransportException]                                                                                                                                                 
  The "https://mirrors.aliyun.com/composer/p/provider-2017%248d3cdf6c9c766724349873e7c49f6a2c26950ea1094916feaf8075a0f802f502.json" file could not be downloaded (HTTP/1.1 404 Not Found)

  如图：
  ![](http://article.qiuhuiyi.cn/%E5%BE%AE%E4%BF%A1%E5%9B%BE%E7%89%87_20221110112156.jpg)

  是因为composer的版本问题，解决方案如下：

  ```shell

  composer self-update --preview

  composer clearcache

  composer update

  ```

