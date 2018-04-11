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
use think\Session;
use think\Cookie;
use app\extra\MyClass\MyClass;

class Admin extends Controller
{
	function _initialize()
	{
        if (is_file('../database.php')){
            $this->redirect('/index');
        }
		$this->assign('from', '');
		if (Session::get('status') != 1) {
			return $this->error('未登录！', 'user/login');
		}

		$res=Db::name('user')->where('Id',Session::get('uid'))->find();
		$this->assign('name', $res['user_name']);
		$this->assign('icon', $res['icon']);
	}

	public function index()
	{
		$this->assign('from', 'index');

		return $this->fetch();
	}

	//文章管理
		//文章编辑
	public function articleOpt()
	{

		if ($this->request->isPost()) {
			$infomation = $this->request->param();
			$infomation['super'] = Db::name('columns')->where('col_name', $infomation['columns'])->find()['super_name'];
			$data = [
				'title' => $infomation['title'],
				'author' => Session::get('user_name'),
				'columns' => $infomation['columns'],
				'super' => $infomation['super'],
				'date' => time(),
				'title_pic'=>$infomation['title_pic'],
				'content' => $infomation['content'],
				'brief' => $infomation['brief'],
			];
			if (Session::get('auth') == 0) {
				$data['status'] = 1;
			} else {
				$data['status'] = 0;
			}
			$result = 0;
			try {
				if ($infomation['type'] == 'add') {
					$result = Db::name('article')->insert($data);
				} elseif ($infomation['type'] == 'modify') {
					$result = Db::name('article')->where('aid', $infomation['aid'])->update($data);
				}
			} catch (\mysqli_sql_exception $exception) {
			} finally {
				if ($result) {
					if ($data['status'] == 1) {
						return $this->success('操作成功！', '/admin/lst');
					} else {
						return $this->success('投稿成功，请等待管理员审核', '/admin/editor');
					}
				} else {
					return $this->error('操作失败！', '/admin/lst');
				}
			}
		}
	}
		//文章删除
	public function articleDel()
	{

		if ($this->request->isPost()) {
			$aid = input('aid');
			$article = Db::name('article')->where('aid', $aid)->find();
			$permission = false;
			if (Session::get('auth') == 0) {
				$permission = true;
			} elseif (Session::get('user_name') == $article['author']) {
				$permission = true;
			}
			if ($permission == true) {
				if (Db::name('article')->where('aid', $aid)->delete() == 1) {
					return $this->success('删除文章成功', '/admin/lst');
				} else {
					return $this->error('删除文章失败', '/admin/lst');
				}
			} else {
				return $this->error('没有权限删除该文章', '/admin/lst');
			}
		}
	}
		//编辑器
	public function editor()
	{
		$this->assign('auth', Session::get('auth'));
		$this->assign('title', '');
		$this->assign('content', '');
		$this->assign('aid', '0');
		$this->assign('type', 'add');
		$this->assign('column', '');
		$this->assign('author', Session::get('user_name'));

		if ($this->request->isPost()) {
			if (input('type') == 'modify') {
				$article = Db::name('article')->where('aid', input('aid'))->find();
				$this->assign('title', $article['title']);
				$this->assign('content', $article['content']);
				$this->assign('column', $article['columns']);
				$this->assign('brief', $article['brief']);
				$this->assign('type', 'modify');
				$this->assign('aid', $article['aid']);
				$this->assign('author', $article['author']);
				$this->assign('title_pic',$article['title_pic']);
				$this->assign('status',$article['status']);
			}
		}
		$super = Db::name('super')->select();
		$columns = Db::name('columns')->select();
		$this->assign('super', $super);
		$this->assign('columns', $columns);
		return $this->fetch();
	}
		//文章列表
	public function lst()
	{
		if (Session::get('auth') != 0) {
			return $this->error('信息已过期', '/user/jump?target=login');
		}
		$article = Db::name('article')->select();
		$this->assign('article', $article);
		return $this->fetch();
	}
		//编辑器图片上传
	public function uploadPic()
	{
		$file = request()->file('file');
		$info = $file->move('static/article/pic/');
		if ($info) {
			$data['pic'] = '/static/article/pic/' . $info->getSaveName();

			$jsondata = array(
				"code" => 0,
				"msg" => "",
				"data" => [
					"src" => 'http://' . $_SERVER['HTTP_HOST'] . $data['pic'],
					"title" => $file->getSaveName(), //可选
				],
			);
			return json_encode($jsondata);
		} else {
			$jsondata = array(
				"code" => 1,
				"msg" => "",
				"data" => [
					"src" => '',
					"title" => $file->getSaveName(), //可选
				],
			);
			return json_encode($jsondata);
		}
	}
		//文章主图片上传
	public function uploadTitlePic(){
		$file = request()->file('title-pic');
		$info = $file->move('static/article/pic/');
		if ($info) {
			$data['pic'] = '/static/article/pic/' . $info->getSaveName();
			$jsondata = array(
				"code" => 0,
				"msg" => "",
				"src" => 'http://' . $_SERVER['HTTP_HOST'] . $data['pic'],
				"serverPath"=>$data['pic'],
			);
			$image=\think\Image::open('./static/article/pic/' . $info->getSaveName());
			$image->thumb(200,200,\think\Image::THUMB_CENTER)->save('./static/article/pic/' . $info->getSaveName());
			return json_encode($jsondata);
		} else {
			$jsondata = array(
				"code" => 1,
				"msg" => '',
				"src" => '',
				"serverPath"=>'',
			);
			return json_encode($jsondata);
		}
	}
	//栏目管理
		//栏目管理器
	public function columnsmanager()
	{
		if (Session::get('auth') != 0) {
			return $this->error('信息已过期', '/user/jump?target=login');
		}
		if ($this->request->isPost()) {
			$opt = input('opt');
			if ($opt == 'add') {
				//添加新的栏目
				$columns = $this->request->param();

				if (Db::name('super')->where('super_name', $columns['super_name'])->find() == null) {
					Db::name('super')->insert(['super_name' => $columns['super_name']]);
				}
				$data = [
					'col_name' => $columns['col_name'],
					'super_name' => $columns['super_name'],
				];
				if (Db::name('columns')->insert($data) != 0) {
					return $this->success('添加栏目成功', 'columnsmanager');
				} else {
					return $this->error('添加栏目失败', 'columnsmanager');
				}

			} elseif ($opt == 'del') {
				//删除一个栏目
				$cid = input('cid');
				if (Db::name('columns')->where('cid', $cid)->delete() == 1) {
					return $this->success('删除栏目成功', 'columnsmanager');
				} else {
					return $this->error('删除栏目失败', 'columnsmanager');
				}

			} elseif ($opt == 'modify') {
				//修改一个栏目
				$cid = input('cid');
				$columns = $this->request->param();
				$data = [
					'col_name' => $columns['col_name'],
					'super_name' => $columns['super_name'],
				];
				if (Db::name('columns')->where('cid', $cid)->update($data)) {
					return $this->success('修改栏目成功', 'columnsmanager');
				} else {
					return $this->error('修改栏目失败', 'columnsmanager');
				}
			}
		} else {
			$columns = Db::name('columns')->select();
			$this->assign('columns', $columns);
			return $this->fetch();
		}
	}
		//栏目编辑器
	public function columnedit()
	{
		if (Session::get('auth') != 0) {
			return $this->error('信息已过期', '/user/jump?target=login');
		}
		if ($this->request->isPost()) {
			$this->assign('cid', input('cid'));
			$this->assign('col_name', input('col_name'));
			$this->assign('super_name', input('super_name'));
			$this->assign('opt', input('opt'));
		} else {
			$this->assign('cid', input(''));
			$this->assign('col_name', input(''));
			$this->assign('super_name', input(''));
			$this->assign('opt', 'add');
		}
		$supers = Db::name('super')->select();
		$this->assign('supers', $supers);
		return $this->fetch();
	}

	//评论列表
    public function commentLst(){
        if(input('aid')!=''){
            $cmt=Db::name('getcomment')->where('aid',input('aid'))->select();
            $this->assign('cmt',$cmt);
            return $this->fetch();
        }else{
            $this->redirect('/admin/lst');
        }
    }
}