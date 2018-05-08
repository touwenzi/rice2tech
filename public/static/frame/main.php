<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title></title>
		<link rel="stylesheet" href="/static/style/admin/plugins/layui/css/layui.css" media="all" />
		<link rel="stylesheet" href="/static/style/admin/css/main.css" />
	</head>

	<body>

	<div class="admin-main">
    <fieldset class="layui-elem-field">
        <legend>系统信息</legend>


        <table style="margin-left:2%;width:96%" class="layui-table">
            <colgroup>
                <col width="15%">
                <col >
            </colgroup>
            <thead>
            <tr>
                <th>项目</th>
                <th>值</th>
              
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td>获取系统类型及版本号：  </td>
                    <td><?php echo php_uname();?></td>
                </tr>
                <tr>
                    <td>获取PHP运行方式：         </td>
                    <td><?php echo php_sapi_name(); ?></td>
                </tr>
                <tr>
            
                    <td>获取PHP版本：</td>
                    <td><?php  echo PHP_VERSION ?></td>
                </tr>
                <tr>
                    <td>获取服务器IP：</td>
                    <td><?php echo GetHostByName($_SERVER['SERVER_NAME']);?></td>
                </tr>
                <tr>
                    <td>接受请求的服务器IP：</td>
                    <td><?php  echo $_SERVER["SERVER_ADDR"];?></td>
                </tr>
                <tr>
                    <td>获取服务器解译引擎：</td>
                    <td><?php echo $_SERVER['SERVER_SOFTWARE'];?></td>
                </tr>
                
                <tr>
                    <td>获取服务器域名：</td>
                    <td><?php echo $_SERVER['SERVER_NAME'];?></td>
                </tr>
                
                <tr>
                    <td>获取服务器语言：</td>
                    <td><?php  echo $_SERVER['HTTP_ACCEPT_LANGUAGE'];?></td>
                </tr>
                <tr>
                    <td>获取服务器Web端口：</td>
                    <td><?php echo $_SERVER['SERVER_PORT']; ?></td>
                </tr>
               
            </tbody>
        </table>
    </fieldset>
    </div>
                
                
    </body>

</html>