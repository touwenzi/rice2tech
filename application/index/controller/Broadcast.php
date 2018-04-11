<?php
/**
 * Created by PhpStorm.
 * User: Sirius
 * Date: 2017/5/28
 * Time: 10:47
 */
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use app\extra\MyClass\MyClass;

Class Broadcast extends Controller
{

	function _initialize()
	{
        if (is_file('../database.php')){
            $this->redirect('/index');
        }
		return $this->error('404 not found','/');
	}
	public function index(){
		return $this->error('404 not found','/');
	}
}