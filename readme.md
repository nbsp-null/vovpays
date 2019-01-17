##环境
1. Laravel 5.5.* 
2. Mysql 5.7
3. PHP 7.2.*
4. redis
5. RabbitMQ
 
##安装步骤
1. 下载或克隆项目，进入项目根目录执行,等待框架安装

``composer install``
2. 将.env.example修改为.env,并进行相关配置,然后在项目根目录执行

``php artisan key:generate``
3. 手动创建数据库,执行迁移数据库表结构和数据

``php artisan migrate:refresh --seed``



##安装报错解决
安装时报错，提示 Your requirements could not be resolved to an installable set of packages?

``composer install --ignore-platform-reqs``

##使用扩展包：
1. 验证码 [mews/captcha](https://github.com/mewebstudio/captcha)
2. php-amqplib": "^2.8"



##使用前端资源：
1. AdminLTE
2. toastr.js
3. sweetalert
4. bootstrap-switch
5. bootstrap-fileinput-4.5.1
6. Chart.min.js
7. jquery.qrcode.min.js

##命令使用
1. ``php artisan queue:work --queue=orderNotify`` 运行异步下游通知
2. ``php artisan phone:get`` 运行手机监听
3. ``php artisan order:callback`` 运行免签订单回调监听
 
##日志
以天为单位,订单回调详情日志：orderCallback; 异步通知只记录通知失败的信息：orderAsyncNotify;


