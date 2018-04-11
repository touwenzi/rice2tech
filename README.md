# 饭科技网站开源项目
***
## 声明
本项目使用thinkphp5.0开源框架
ThinkPHP是一个免费开源的，快速、简单的面向对象的轻量级PHP开发框架，是为了敏捷WEB应用开发和简化企业应用开发而诞生的。ThinkPHP从诞生以来一直秉承简洁实用的设计原则，在保持出色的性能和至简的代码的同时，也注重易用性。遵循Apache2开源许可协议发布，意味着你可以免费使用ThinkPHP，甚至允许把你基于ThinkPHP开发的应用开源或商业产品发布/销售。
## 部署
1.确认服务器已安装lamp，若未安装请移步[lnmp.org](http://lnmp.org)。  
2.将Apache的网站根目录设置为`/网站目录/public`。    
3.启用Apache的 `mod_rewrite`模块。    
4.通过域名访问。     
5.通过引导页面安装网站。     

## 目录结构
~~~
project  应用部署目录
├─application           应用目录（可设置）
│  ├─common             公共模块目录（可更改）
│  ├─index              模块目录(可更改)
│  │  ├─config.php      模块配置文件
│  │  ├─common.php      模块函数文件
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  ├─view            视图目录
│  │  └─ ...            更多类库目录
│  ├─command.php        命令行工具配置文件
│  ├─common.php         应用公共（函数）文件
│  ├─config.php         应用（公共）配置文件
│  ├─database.php       数据库配置文件
│  ├─tags.php           应用行为扩展定义文件
│  └─route.php          路由配置文件
├─extend                扩展类库目录（可定义）
├─public                WEB 部署目录（对外访问目录）
│  ├─static             静态资源存放目录(css,js,image)
│  ├─index.php          应用入口文件
│  ├─router.php         快速测试文件
│  └─.htaccess          用于 apache 的重写
├─runtime               应用的运行时目录（可写，可设置）
├─vendor                第三方类库目录（Composer）
├─thinkphp              框架系统目录
│  ├─lang               语言包目录
│  ├─library            框架核心类库目录
│  │  ├─think           Think 类库包目录
│  │  └─traits          系统 Traits 目录
│  ├─tpl                系统模板目录
│  ├─.htaccess          用于 apache 的重写
│  ├─.travis.yml        CI 定义文件
│  ├─base.php           基础定义文件
│  ├─composer.json      composer 定义文件
│  ├─console.php        控制台入口文件
│  ├─convention.php     惯例配置文件
│  ├─helper.php         助手函数文件（可选）
│  ├─LICENSE.txt        授权说明文件
│  ├─phpunit.xml        单元测试配置文件
│  ├─README.md          README 文件
│  └─start.php          框架引导文件
├─build.php             自动生成定义文件（参考）
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
├─think                 命令行入口文件
~~~


## 更新日志

### 2017-06-13 Version:v0.02_alpha  

#### 添加一键安装功能  

>优化部分代码  
>部分功能未处理  
>此版本供测试

### 下一步更新一键安装版本


### 2017-06-03 Version:v0.01_beta

#### 优化

>优化前端代码，体验更佳  
>后台代码逻辑调整，避免了一些问题  
>登录记住密码功能实现  

#### 待修复

>因iframe框架导致的后台控制面板在未关闭的情况下，在新的页面注销账户并登录一个新的账户导致的越权问题（严重）  

#### 待添加

>站内信功能  
>注册邮箱验证功能  
>登录验证码

### 2017-05-29 Version:v0.01_alpha_2
#### 添加
>1.后台图片处理  
>2.评论显示功能（ajax异步获取更多评论）  

#### 修复
>1.js逻辑错误引发的搜索框以及栏目点击的效果失效  
>2.页面部分显示问题  

#### 待添加和修复
>1.评论功能  
>2.评论页的显示效果的加强  
>3.登录输入验证码的功能  

---

### 2017-05-26 Version:v0.01_alpha_1
#### 修复

>1.文章排序按时间升序排列改为降序排列  

#### 添加功能
>1.文章标题图片上传
>2.富文本编辑器图片上传  

---
### 2017-05-23 Version:v0.01_alpha
#### 功能实现

>1.用户登录与注册功能  
>2.文章编写、管理以及删除  
>3.动态添加、删除和编辑栏目  
>4.文章列表分页功能，栏目文章列表显示  
>5.用户信息管理，用户组权限管理  
>6.用户文章投稿  

#### 待添加功能

>1.头像编辑上传（减少带宽压力） 
>2.文章编辑器功能添加  
>>a).`tags`标签编写  
>>b).html编辑器  
>>c).图片上传接口  
>
>3.模板页编辑器  
>4.文章搜索功能  

