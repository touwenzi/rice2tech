<?php
/**
 * Created by PhpStorm.
 * user: Sirius <siriustseng@outlook.com>
 * Date: 2017/06/02
 * Time: 10:21
 * Copyrigth © 2017 https://rice2tech.com
 * Licensed: ( http://www.apache.org/licenses/LICENSE-2.0 )
 */
namespace app\index\model;

use Think\Model;

class User extends Model{
	protected $table='tp_user';

	protected $connection=[
		'type'	=>'mysql',
		'host'=>'127.0.0.1'
	];
}