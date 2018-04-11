<?php
/**
 * Created by PhpStorm.
 * user: Sirius <siriustseng@outlook.com>
 * Date: 2017/4/18
 * Time: 20:34
 * Copyrigth © 2017 https://rice2tech.com
 * Licensed: ( http://www.apache.org/licenses/LICENSE-2.0 )
 */
namespace app\extra\MyClass;


class MyClass
{
	static function encryptPassword($pwd)
	{
		$salt = 'Sirius&Zzz&Lwt';
		$newPwd = $pwd . $salt;
		$newPwd = md5($newPwd);
		return $newPwd;
	}

	static function verifyAccount($account)
	{
		$regex = [
			'uname' 	=> 		"/^[\w!@#$%\^&*()-=_+]{6,20}$/",
			'pwd' 		=> 		"/^[\w!@#$%\^&*()-=_+]{8,20}$/",
			'mail' 		=> 		"/[\w!#$%&\'*+\/=?^_`{|}~-]+(?:\.[\w!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?/",
			'birth' 	=>		"/^[1-9]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/"
		];
		if (preg_match($regex['uname'], $account['uname']) && preg_match($regex['pwd'], $account['pwd']) && preg_match($regex['mail'], $account['mail']) && preg_match($regex['birth'], $account['birth'])) {
			return true;
		} else {
			return false;
		}
	}

	static function dealArticle($content)
	{
		$order = "\n";
		$replace = "</p><br><p>";
		$content = str_replace($order, $replace, $content);
		return $content;
	}
	static function verifyPassword($pwd){
		$regex="/^[\w!@#$%\^&*()-=_+]{8,20}$/";
		if (preg_match($regex,$pwd)){
			return true;
		}else{
			return false;
		}
	}
}