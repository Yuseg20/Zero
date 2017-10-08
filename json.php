<?php
	$arr = array(

		/* 页面设置 */
		"view" => [
			"charset" => "UTF-8"//页面字符编码
		],

		/* 目录配置 */
		"path" => [
			"view_path" => "View/"//视图模板路径
		],

		/* 系统配置 */
		"system" =>[
			"timezone" => "PRC"//时区设置,PRC(中华人民共和国)
		],

		/* MySQL 数据库配置 */
		"mysql" => [
			"host"     => "127.0.0.1",//数据库主机地址
			"port"	   => "3306",//端口号
			"user"     => "root",//用户名
			"password" => "68852911",//密码
			"db"       => "zero"//数据库
		],

		/* Session 配置 */
		"session" => [
			"display"	  => "on",	//自定义 Session 开关
			"save_path"	  => "Session",	//Session 保存地址
			"maxlifetime" => 1440,		//最大生命周期 s
			"probability" => 1,		//回收概率分子
			"divisor"	  => 1000	//回收概率分母, 0 为默认
		],

		/* Redis 配置 */
		"redis" => [
			"display"	=> "off",		//开关
			"host"		=> "127.0.0.1",	// Redis 服务器地址
		]
	);

	$str = json_encode($arr);

	$config = json_decode($str);

	print_r($arr);
	echo "<br/><br/>";
	print_r($str);
	echo "<br/><br/>";
	print_r($config);
	echo "<br/><br/>";
	$t = 'view';
	print_r($config -> $t -> charset);

	$str = 'a.c.b';
	print_r(explode('.', $str));

	print_r(property_exists($config -> view, 'charset'));

	echo '<br/><br/>';
	$keyStr = 'view.charset';
	if(empty($keyStr)){
		return -1;
	}

	//分解键名
	$keys = explode('.', $keyStr);

	$result = $config;
	//循环取值
	for($i=0;$i<count($keys);$i++){
		//判断对象属性是否存在
		if(!property_exists($result, $keys[$i])){
			//不存在则结束返回
			print_r(-1);
			return;
		}

		//存在赋值
		$result = $result -> $keys[$i];
	}

	//返回结果
	print_r($result);
?>