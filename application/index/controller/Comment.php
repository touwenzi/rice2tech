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

Class Comment extends Controller
{
	function _initialize()
	{
        if (is_file('../database.php')){
            $this->redirect('/index');
        }
	}

	public function addComment()
	{
        $this->init();
		$data = [
			'aid' => input('aid'),
			'uid' => Session::get('uid'),
			'from_uid' => input('from_uid','0'),
			'content' =>input('content'),
			'date' => time(),
			'from_level'=>input('from_level','0')
		];
		$cmtRes=Db::name('comment')->where('aid',input('aid'))->order('level desc')->select();
		if ($cmtRes==null){
			$data['level']=1;
		}else{
			$data['level']=($cmtRes[0]['level']+1);
		}

		if (Db::name('comment')->insert($data) == 1) {
			return $this->success('评论成功');
		}
	}

	public function delComment()
	{
	    $this->init();
		if (Session::get('auth') == 0) {
			if (Db::name('comment')->where('cmid', input('cmid'))->delete() == 1) {
				return $this->success('删除评论成功','/admin/commentlst/aid/'.input('aid'));
			} else {
				return $this->error('删除失败,权限错误','/admin/commentlst/aid/'.input('aid'));
			}
		} else {
			if (Db::name('comment')->where('cmid', input('cmid'))->where('uid', Session::get('uid'))->delete() == 1) {
				return $this->success('删除评论成功','/admin/commentlst/aid/'.input('aid'));
			} else {
				return $this->error('删除失败,权限错误','/admin/commentlst/aid/'.input('aid'));
			}
		}
	}

	public function getComment($aid='',$count=''){
		if ($aid!=''&&$count!=''){
		$comment=Db::name('getcomment')->where('aid',$aid)->page($count,8)->order('date desc')->select();
		$comment=json_encode($comment);
		return $comment;
		}
		return $this->error('404 not found');
	}

    private function init(){
        if(Session::get('status')!=1){
            return $this->error('未登录');
        }
        if(Session::get('auth')!=0){
            return $this->error('权限错误');
        }
    }
	public function test(){

		$res=Db::name('comment')->where('aid',2)->select();
		return $res;
	}
}