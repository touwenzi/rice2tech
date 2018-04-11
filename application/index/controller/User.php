<?php
/**
 * Created by PhpStorm.
 * user: Sirius <siriustseng@outlook.com>
 * Date: 2017/06/02
 * Time: 10:21
 * Copyrigth © 2017 https://rice2tech.com
 * Licensed: ( http://www.apache.org/licenses/LICENSE-2.0 )
 */
namespace app\index\controller;

use think\Controller;
use think\Cookie;
use think\Db;
use think\Request;
use think\response\Redirect;
use think\Session;
use app\extra\MyClass\MyClass;


class User extends Controller
{
	function _initialize()
	{
        if (is_file('../database.php')){
            $this->redirect('/index');
        }
		$this->assign('from', '');
	}

	public function index()
	{
		if (Session::get('status') != 1) {
			$this->redirect('/user/login');
		} else {
			$this->redirect('/');
		}

	}

	public function login()
	{
//		判断logout
		if ($this->request->isGet()) {
			if (input('logout') == true) {
				Session::clear();
				Cookie::delete('uid');
				Cookie::delete('authentication');
				return $this->success('登出成功', '/');
			}
		}

//		判断是否记住登录信息
		if (Cookie::has('uid')){
			$result=Db::name('user')->where('Id',Cookie::get('uid'))->find();
			if ($result!=null){
				$confirm=md5($result['birth'].'rtcc'.$result['pass'].'ieeh'.$result['mail']);
				if ($confirm==Cookie::get('authentication')){
					Session::set('status', '1');
					Session::set('uid', $result['Id']);
					Session::set('user_name', $result['user_name']);
					Session::set('auth', $result['auth']);
					Session::set('info',$result['info']);
					Session::set('icon',$result['icon']);
					return '1';
				}else{
					Cookie::delete('uid');
					Cookie::delete('authentication');
					return '0';
				}
			}
		}


		if ($this->request->isPost()) {
			$uname = input('uname');
			//密码加密过程
			$pwd = MyClass::encryptPassword(input('pwd'));
			$result = Db::name('user')->where('user_name', $uname)->where('pass', $pwd)->find();
			if ($result != null) {
				Session::set('status', '1');
				Session::set('uid', $result['Id']);
				Session::set('user_name', $result['user_name']);
				Session::set('auth', $result['auth']);
				Session::set('info',$result['info']);
				Session::set('icon',$result['icon']);
				if (input('autopass')=='autopass'){
					Cookie::set('uid',$result['Id'],604800);
					Cookie::set('authentication',md5($result['birth'].'rtcc'.$result['pass'].'ieeh'.$result['mail']),604800);
				}
				if ($result['auth'] == 0) {
					return $this->success('登录成功', '/admin/index');
				} else {
					return $this->success('登录成功');
				}
			} else {
				$this->error('用户名或密码错误', '/user/login');
			}
		}
		return $this->fetch();
	}

	public function register()
	{
		if ($this->request->isPost()) {
			($userinfo = $this->request->param());
			if (!($userinfo['uname'] == '' || $userinfo['pwd'] == '' || $userinfo['sex'] == '' || $userinfo['mail'] == '' || $userinfo['birth'] == '')) {
				if (MyClass::verifyAccount($userinfo)) {
					$userinfo['pwd'] = MyClass::encryptPassword($userinfo['pwd']);//密码加密
					$userinfo['birth'] = strtotime($userinfo['birth']);
					$data = [
						'user_name' => $userinfo['uname'],
						'pass' => $userinfo['pwd'],
						'sex' => $userinfo['sex'],
						'birth' => $userinfo['birth'],
						'mail' => $userinfo['mail'],
						'nick_name' => $userinfo['uname'],
					];
					$result = 0;
					try {
						$result = Db::name('user')->insert($data);
					} catch (\mysqli_sql_exception $exception) {
					} finally {
						if ($result) {
							return $this->success('注册成功', '/');
						} else {
							return $this->error('注册失败，用户名已存在！', '/');
						}
					}
				}
			}
		}
		return $this->fetch();
	}

	public function modifyPassword()
	{

		if (Session::get('status') != 1) {
			return $this->error('未登录！', 'user/login');
		}
		if ($this->request->isPost()) {
			$nPwd = input('npwd');
			$uid = Session::get('uid');
			$opwd = MyClass::encryptPassword(input('opwd'));
			if (Db::name('user')->where('Id', $uid)->where('pass', $opwd)->find() != null) {
				if (MyClass::verifyPassword($nPwd)) {
					$nPwd = MyClass::encryptPassword($nPwd);
					if (Db::name('user')->where('Id', Session::get('uid'))->update(['pass' => $nPwd]) != 0) {
						Session::set('status', '0');
						return $this->success('更改密码成功', '/user/jump?target=login');
					} else {
						return $this->error('密码不符合规则');
					}
				}
			} else {
				return $this->error('原密码错误！！');

			}
		}
		return $this->fetch();
	}

	public function profile()
	{
//		if (Session::get('uid')!=Cookie::get('uid')){
//			return $this->error('登录信息已过期，请重新登录','/user/jump?target=login');
//		}
		if ($this->request->isPost()) {
			$getData = $this->request->param();

			if ($getData['sex'] != '') {
				$data['sex'] = $getData['sex'];
			}
			if ($getData['nick_name'] != '') {
				$data['nick_name'] = $getData['nick_name'];
			}
			if ($getData['birth'] != '') {
				$data['birth'] = strtotime($getData['birth']);
			}
			if ($getData['info'] != '') {
				$data['info'] = $getData['info'];
			}
			if (Db::name('user')->where('Id', Session::get('uid'))->update($data) != 0) {
				if ($getData['info'] != '') {
					$this->assign('info',$getData['info']);
				}
				return $this->success('信息更改成功', '/user/profile');
			} else {
				return $this->error('信息更改失败');
			}
		}
		$data = Db::name('user')->where('Id', Session::get('uid'))->find();
		$data['birth'] = date("Y-m-d", $data['birth']);
		$this->assign('data', $data);
		$this->assign('jsonurl', "/user/uploadicon");
		$this->assign('elem', 'profile_icon');

		switch ($data['auth']) {
			case 0:
				$this->assign('auth', "管理员");
				break;
			case 1:
				$this->assign('auth', "普通用户");
				break;
			case 2:
				$this->assign('auth', "作者");
				break;
			case 3:
				$this->assign('auth', "编辑");
				break;
		}

		return $this->fetch();
	}

	public function uploadicon()
	{
//		if (Session::get('uid')!=Cookie::get('uid')){
//			return $this->error('登录信息已过期，请重新登录','/user/jump?target=login');
//		}
		$file = request()->file('icon');
		$info = $file->move('static/profile/icon/');

		if ($info) {
			$data['icon'] = '/static/profile/icon/' . $info->getSaveName();
			Db::name('user')->where('Id', Session::get('uid'))->update(['icon' => $data['icon']]);
			$jsondata = array(
				"code" => 0,
				"msg" => "ok",
				"url" => 'http://' . $_SERVER['HTTP_HOST'] . $data['icon'],
			);
			$image=\think\Image::open('./static/profile/icon/' . $info->getSaveName());
			$image->thumb(200,200,\think\Image::THUMB_CENTER)->save('./static/profile/icon/' . $info->getSaveName());
			Session::set('icon',$data['icon']);
			return json_encode($jsondata);
		} else {
			return $this->error('上传出问题了', '/user/profile');
		}
	}

	public function userManager()
	{
//		if (Session::get('uid')!=Cookie::get('uid')){
//			return $this->error('登录信息已过期，请重新登录','/user/jump?target=login');
//		}
		if (Session::get('status') != 1) {
			return $this->error('未登录！', 'user/login');
		}
		if (Session::get('auth') != 0) {
			return $this->error('信息已过期', '/user/jump?target=login');
		}
		if ($this->request->isPost()) {
			$getData = $this->request->param();
			if ($getData['method'] == 'modify') {

				if (Db::name('user')->where('Id', $getData['uid'])->update(['auth' => input('auth')]) != 0) {
					return $this->redirect('/user/usermanager');
				} else {
					return $this->error('修改失败', '/user/usermanager');
				}
			} elseif ($getData['method'] == 'del') {
				if (Db::name('user')->where('Id', $getData['uid'])->delete() != 0) {
					return $this->success('删除成功', '/user/usermanager');
				} else {
					return $this->error('删除失败', '/user/usermanager');
				}
			}
		}
		$user = Db::name('user')->select();
		$this->assign('user', $user);
		return $this->fetch();

	}

	public function jump($target = '')
	{
		if ($target == 'login') {
			Session::clear();
			Cookie::delete('uid');
			Cookie::delete('authentication');
			$this->assign('href', '/user/login');
			return $this->fetch();
		}
		return $this->redirect('/');
	}

}





