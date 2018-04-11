<?php
/**
 * Created by PhpStorm.
 * user: Sirius <siriustseng@outlook.com>
 * Date: 2017/4/18
 * Time: 20:34
 * Copyrigth © 2017 https://rice2tech.com
 * Licensed: ( http://www.apache.org/licenses/LICENSE-2.0 )
 */

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Cookie;
use think\Session;
use app\extra\MyClass\MyClass;

Class Index extends Controller
{
    public function _initialize()
    {
    }

    public function index()
    {
        if (!is_file('../application/index/database.php')) {
            $this->redirect('/index/install');
        }
        $setting = Db::name('setting')->find();
        $this->assign('blog_title', $setting['title']);
        $res = Db::name('user')->where('Id', Session::get('uid'))->find();
        $this->assign('icon', $res['icon']);
        $this->assign('nick_name', $res['nick_name']);
        $this->assign('info', $res['info']);
        $this->assign('uname', $res['user_name']);
        $super = Db::name('super')->select();
        $this->assign('super', $super);
        $columns = Db::name('columns')->select();
        $this->assign('columns', $columns);
        $this->assign('active', 'index');
        return $this->fetch();
    }

    public function install()
    {
        if (!is_file('../application/index/database.php')) {
            if ($this->request->isPost()) {
                $database = $this->request->param();
                $this->setup($database);
            }
            return $this->fetch();
        }

    }

    private function setup($database)
    {


        try {
            Db::connect('mysql://' . $database['db_user'] . ':' . $database['db_pwd'] . '.@' . $database['db_hostname'] . ':3306/' . $database['db_name'] . '#utf8');
        } catch (\InvalidArgumentException $exception) {
            return $this->error('数据库连接失败', '/index');
        }
        //创建数据表
        $this->creatDataBaseForm($database);
        //写入数据库配置文件
        file_put_contents('../application/index/database.php', "
        <?php
        return [
             // 数据库类型
             'type'            => 'mysql',
             // 服务器地址
             'hostname'        => '" . $database['db_hostname'] . "',
             // 数据库名
             'database'        => '" . $database['db_name'] . "',
             // 用户名
             'username'        => '" . $database['db_user'] . "',
             // 密码
             'password'        => '" . $database['db_pwd'] . "',
             // 端口
             'hostport'        => '3306',
             // 连接dsn
             'dsn'             => '',
             // 数据库连接参数
             'params'          => [],
             // 数据库编码默认采用utf8
             'charset'         => 'utf8',
             // 数据库表前缀
             'prefix'          => '" . $database['prefix'] . "',
             // 数据库调试模式
             'debug'           => false,
             // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
             'deploy'          => 0,
             // 数据库读写是否分离 主从式有效
             'rw_separate'     => false,
             // 读写分离后 主服务器数量
             'master_num'      => 1,
             // 指定从服务器序号
             'slave_no'        => '',
             // 是否严格检查字段是否存在
             'fields_strict'   => true,
             // 数据集返回类型
             'resultset_type'  => 'array',
             // 自动写入时间戳字段
             'auto_timestamp'  => false,
             // 时间字段取出后的默认时间格式
             'datetime_format' => 'Y-m-d H:i:s',
             // 是否需要进行SQL性能分析
             'sql_explain'     => false,
            ];
        ");
        $this->redirect('/index');
    }

    private function creatDataBaseForm($database)
    {
        $sqlContent = file_get_contents('../application/extra/setup.sql');
        $sqlContent = str_replace('tp_', $database['prefix'], $sqlContent);
        $sqlArray = explode(';', $sqlContent);
        //删除sql数组中的最后一个空值
        array_pop($sqlArray);
        //事务处理  暂为解决
//        Db::transaction(function () use ($database,$sqlArray){
            foreach ($sqlArray as $value) {
                Db::connect([
                    // 数据库类型
                    'type' => 'mysql',
                    // 数据库连接DSN配置
                    'dsn' => '',
                    // 服s务器地址
                    'hostname' => $database['db_hostname'],
                    // 数据库名
                    'database' => $database['db_name'],
                    // 数据库用户名
                    'username' => $database['db_user'],
                    // 数据库密码
                    'password' => $database['db_pwd'],
                    // 数据库连接端口
                    'hostport' => '3306',
                    // 数据库连接参数
                    'params' => [],
                    // 数据库编码默认采用utf8
                    'charset' => 'utf8',
                    // 数据库表前缀
                    'prefix' => $database['prefix'],
                    // 调试模式
                    'debug' => false
                ])->execute($value);
            }
            $userdata = [
                'user_name' => $database['uname'],
                'pass' => MyClass::encryptPassword($database['pwd']),
                'auth' => 0,
                'mail' => $database['mail'],
            ];
            Db::connect([
                // 数据库类型
                'type' => 'mysql',
                // 数据库连接DSN配置
                'dsn' => '',
                // 服s务器地址
                'hostname' => $database['db_hostname'],
                // 数据库名
                'database' => $database['db_name'],
                // 数据库用户名
                'username' => $database['db_user'],
                // 数据库密码
                'password' => $database['db_pwd'],
                // 数据库连接端口
                'hostport' => '3306',
                // 数据库连接参数
                'params' => [],
                // 数据库编码默认采用utf8
                'charset' => 'utf8',
                // 数据库表前缀
                'prefix' => $database['prefix'],
                // 调试模式
                'debug' => false
            ])->name('user')->insert($userdata);
            Db::connect([
                // 数据库类型
                'type' => 'mysql',
                // 数据库连接DSN配置
                'dsn' => '',
                // 服s务器地址
                'hostname' => $database['db_hostname'],
                // 数据库名
                'database' => $database['db_name'],
                // 数据库用户名
                'username' => $database['db_user'],
                // 数据库密码
                'password' => $database['db_pwd'],
                // 数据库连接端口
                'hostport' => '3306',
                // 数据库连接参数
                'params' => [],
                // 数据库编码默认采用utf8
                'charset' => 'utf8',
                // 数据库表前缀
                'prefix' => $database['prefix'],
                // 调试模式
                'debug' => false
            ])->name('setting')->data(['title' => $database['blog_title']])->insert();
//        });
    }
}
