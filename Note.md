### ngnix的配置

1. 修改url重写

   ```
   location /atsTp5/ {
   	index index.php;
   	if (!-e $request_filename){
   		rewrite ^/atsTp5/(.*)$ /atsTp5/index.php?s=/$1 last;
   			break;
   	}
   }
   ```

   

2. 修改index.php到根目录

   ```
   // 应用目录
   define('APP_PATH', __DIR__.'/application/');
   // 加载框架引导文件
   require './thinkphp/start.php';
   ```


### runTime权限设置

```
chmod -R 757 runtime
```

### mysql删除设置

```
SET SQL_SAFE_UPDATES=0;
```

### www-data用户设置root权限

1. 虽然在nginx里面设置了启动为root，但是php-fpm还是为www-data用户组?

   + 具体在`/etc/php/5.6/fpm/php-fpm.conf`里面配置，比较麻烦。

2. 修改www-data 权限 

   ```
   1) chmod 757 sudoers
   2) vi /etc/sudoers
   3) www-data ALL=NOPASSWD: ALL
   4) chmod 0440 /etc/sudoers
   ```

### ftp 设定

1. 需要对runtime中ftp文件夹加777权限, 可以理解为对runtime的权限的设定

### cifs_mail.txt

1. 可以不用生成cifs_mail.txt文件

### thinkphp5 备忘

 1. DB:query () 返回的是二维数组。不包括模型数据。

 2. 模板渲染

    ```
    1. return $this->fetch('common/footer');
    2. return $this->fetch();
    ```

    3. 对外接口可以继承控制器，直接使用Request对象。内部使用，可以不继承。


### default_ajax_return
  1. tp5设置成json之后，return要接json($array), 如果设置空, 则为json_encode，不想改了

### default_return_type
  1. 设置成`json`之后，网页都会被解析，只能设置成`html`便于跳转
  

### workman
  1. workman一个主线程可以开启count个进程, 每个进程都可以连接N个客户端, 但是客户端之间的通信比较麻烦,
     所以可以开一个进程会来的简单
  2. 原先做了一个work.php, 但是指定了进程号, 所以又新建了一个server_push.php来推送消息
  3. thinkphp5.1可以下载绑定多个workman, 但是考虑到可能有坑, 暂时不更新. 
  4. 长连接说是要客户端和服务端做心跳的, 但是正式环境没有发现问题, 暂时这样子..
  5. home配置启动workman命令要sudo, 可能www-data没有root权限. 正式环境去掉了sudo.   

### 杀死指定端口进程的方法
  ```
    kill -9 `lsof -t -i:2344`
  ```
### bootstrap-table@1.15.5
  1. 需要bootstrap4的支持的css, 否則dark不能显示.
  
    
---
## thinkphp5目录结构

初始的目录结构如下：

~~~
www  WEB部署目录（或者子目录）
├─application           应用目录
│  ├─common             公共模块目录（可以更改）
│  ├─module_name        模块目录
│  │  ├─config.php      模块配置文件
│  │  ├─common.php      模块函数文件
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  ├─view            视图目录
│  │  └─ ...            更多类库目录
│  │
│  ├─command.php        命令行工具配置文件
│  ├─common.php         公共函数文件
│  ├─config.php         公共配置文件
│  ├─route.php          路由配置文件
│  ├─tags.php           应用行为扩展定义文件
│  └─database.php       数据库配置文件
│
├─public                WEB目录（对外访问目录）
│  ├─index.php          入口文件
│  ├─router.php         快速测试文件
│  └─.htaccess          用于apache的重写
│
├─thinkphp              框架系统目录
│  ├─lang               语言文件目录
│  ├─library            框架类库目录
│  │  ├─think           Think类库包目录
│  │  └─traits          系统Trait目录
│  │
│  ├─tpl                系统模板目录
│  ├─base.php           基础定义文件
│  ├─console.php        控制台入口文件
│  ├─convention.php     框架惯例配置文件
│  ├─helper.php         助手函数文件
│  ├─phpunit.xml        phpunit配置文件
│  └─start.php          框架入口文件
│
├─extend                扩展类库目录
├─runtime               应用的运行时目录（可写，可定制）
├─vendor                第三方类库目录（Composer依赖库）
├─build.php             自动生成定义文件（参考）
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
├─think                 命令行入口文件
~~~

> router.php用于php自带webserver支持，可用于快速测试
> 切换到public目录后，启动命令：php -S localhost:8888  router.php
> 上面的目录结构和名称是可以改变的，这取决于你的入口文件和配置参数。

## 命名规范

`ThinkPHP5`遵循PSR-2命名规范和PSR-4自动加载规范，并且注意如下规范：

### 目录和文件

*   目录不强制规范，驼峰和小写+下划线模式均支持；
*   类库、函数文件统一以`.php`为后缀；
*   类的文件名均以命名空间定义，并且命名空间的路径和类库文件所在路径一致；
*   类名和类文件名保持一致，统一采用驼峰法命名（首字母大写）；

### 函数和类、属性命名
*   类的命名采用驼峰法，并且首字母大写，例如 `User`、`UserType`，默认不需要添加后缀，例如`UserController`应该直接命名为`User`；
*   函数的命名使用小写字母和下划线（小写字母开头）的方式，例如 `get_client_ip`；
*   方法的命名使用驼峰法，并且首字母小写，例如 `getUserName`；
*   属性的命名使用驼峰法，并且首字母小写，例如 `tableName`、`instance`；
*   以双下划线“__”打头的函数或方法作为魔法方法，例如 `__call` 和 `__autoload`；

### 常量和配置
*   常量以大写字母和下划线命名，例如 `APP_PATH`和 `THINK_PATH`；
*   配置参数以小写字母和下划线命名，例如 `url_route_on` 和`url_convert`；

### 数据表和字段
*   数据表和字段采用小写加下划线方式命名，并注意字段名不要以下划线开头，例如 `think_user` 表和 `user_name`字段，不建议使用驼峰和中文作为数据表字段命名。