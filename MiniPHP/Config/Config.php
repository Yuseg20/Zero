<?php
/* 站点基本配置 */

return array(

	//页面设置
	"view" => [
		"charset" => "UTF-8"//页面字符编码
	],

	/* 目录配置 */
	"path" => [
		"view_path" => PLAT_PATH."View/"//视图模板路径
	],

	/* MySQL 数据库配置 */
	"mysql" => [
		"host"     => "localhost",//数据库主机地址
		"port"	   => "3306",//端口号
		"user"     => "root",//用户名
		"password" => "",//密码
		"db"       => "meblog"//数据库
	],

	/* Session 配置 */
	"session" => [
		"display"	  => "on",	//自定义 Session 开关
		"save_path"	  => APP_PATH."Session",	//Session 保存地址
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