<?php
/* 站点基本配置 */

return array(

	/* 页面设置 */
	"view" => array(
		"charset" => "UTF-8"//页面字符编码
	),

	/* 目录配置 */
	"path" => array(
		"view"   => PLAT_PATH."View/",//视图模板路径
		"avatar" => "/src/images/avatar.jpg"//默认头像路径
	),

	/* 系统配置 */
	"system" =>array(
		"timezone" => "PRC"//时区设置,PRC(中华人民共和国)
	),

	/* MySQL 数据库配置 */
	"mysql" => array(
		"host"     => "127.0.0.1",//数据库主机地址
		"port"	   => "3306",//端口号
		"user"     => "root",//用户名
		"password" => "68852911",//密码
		"db"       => "zero",//数据库
		"charset"  => "utf8"//数据库编码
	),

	/* Session 配置 */
	"session" => array(
		"display"	  => "on",	//自定义 Session 开关
		"save_path"	  => APP_PATH."Session",	//Session 保存地址
		"maxlifetime" => 1440,		//最大生命周期 s
		"probability" => 1,		//回收概率分子
		"divisor"	  => 1000	//回收概率分母, 0 为默认
	),

	/* Redis 配置 */
	"redis" => array(
		"display"	=> "off",		//开关
		"host"		=> "127.0.0.1",	// Redis 服务器地址
	)
);
