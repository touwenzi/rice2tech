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

Class Blog extends Controller
{

	public function _initialize()
	{
        if (is_file('../database.php')){
            $this->redirect('/index');
        }
		$res = Db::name('user')->where('Id', Session::get('uid'))->find();
		$this->assign('icon', $res['icon']);
		$this->assign('uname', $res['user_name']);
		$this->assign('nick_name',$res['user_name']);
		$this->assign('info',$res['info']);
		$super = Db::name('super')->select();
		$columns = Db::name('columns')->select();
		$this->assign('super', $super);
		$this->assign('columns', $columns);
        $setting=Db::name('setting')->find();
        $this->assign('blog_title',$setting['title']);
	}

	public function index()
	{
		$this->assign('active', 'blog');
		$sn=input('sn');
		$cn=input('cn');

		if ($sn!='') {
			$su=Db::name('super')->where('sid',$sn)->find()['super_name'];
			$article = Db::name('article')->where('super',$su)->where('status', 1)->order('date desc')->paginate(8);
			$this->assign('active', 'su_'.$sn);
		}else if($cn!=''){
			$cu=Db::name('columns')->where('cid',$cn)->find();
			$sn=Db::name('super')->where('super_name',$cu['super_name'])->find()['sid'];
			$article = Db::name('article')->where('columns',$cu['col_name'])->where('status', 1)->order('date desc')->paginate(8);
			$this->assign('active', 'su_'.$sn);
		}
		else {
			$article = Db::name('article')->where('status', 1)->order('date desc')->paginate(8);
		}
		$this->assign('article', $article);
		return $this->fetch('blog');
	}

	public function article()
	{
		if ($this->request->isGet()) {
			$aid = input('aid');
			$uid = Session::get('uid');
			$userinfo = Db::name('user')->where('Id', $uid)->find();
			$article = Db::name('article')->where('aid', $aid)->find();
			$comment=Db::name('getcomment')->where('aid',$aid)->page(1,8)->order('date desc')->select();
			$sn=Db::name('super')->where('super_name',$article['super'])->find();
			$cn=Db::name('columns')->where('col_name',$article['columns'])->find();

			if ($article['status'] == 0) {
				return $this->error('该文章还未通过审核！', 'index');
			}
			if (!empty($article)) {
				$this->assign('title', $article['title']);
				$this->assign('aid', $article['aid']);
				$this->assign('author', $article['author']);
				$this->assign('date', date("Y-m-d H:i:s", $article['date']));
				$this->assign('super_name',$article['super']);
				$this->assign('columns_name',$article['columns']);
				$this->assign('content', MyClass::dealArticle($article['content']));
				$this->assign('comment',$comment);
				$this->assign('sid',$sn['sid']);
				$this->assign('cid',$cn['cid']);
			} else {
				return $this->error('未找到文件，正为您跳转到主页', '/blog');
			}
			if (!empty($userinfo)) {
				$this->assign('uname', $userinfo['user_name']);
				$this->assign('uid', $uid);
			} else {
				$this->assign('uname', '未登录');
				$this->assign('uid', '');
			}
			$this->assign('active', 'article');

			return $this->fetch();
		}
	}

	public function search($kw=''){
		if ($kw==''){
			$this->error('请输入关键词','/blog');
		}else{
			$searchRes=Db::name('article')->whereOr('title','like','%'.$kw.'%')->whereOr('brief','like','%'.$kw.'%')->order("date desc")->paginate(8);
			$this->assign('article',$searchRes);
			$this->assign('kw',$kw);
			return $this->fetch('blog');

		}
	}

	public function ajaxtest($aid=''){
		if ( $aid!=''){
			$res=json_encode(Db::name('article')->where('aid',$aid)->find());
			return $res;
		}
		return $this->fetch();
	}
}