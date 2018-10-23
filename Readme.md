# 开发分支

~~~php+HTML
开发中
~~~

##安装
~~~
// 安装所需的依赖
$ composer install
// 生成一个key 
$ php artisan key:generate
// 生成jwt的一个密钥
$ php artisan jwt:secret
// 运行数据库迁移
$ php artisan migrate
