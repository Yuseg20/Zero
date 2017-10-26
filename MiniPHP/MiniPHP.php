<?php

/*==================================================
  =			  请求分发器（前端控制器）            =
  ==================================================
  =                                                =
  = MiniPHP 入口文件							   =
  =                                                =
*///================================================

//==================================== 请求解析 ====================================

/* 接收请求字符串 */
$get_q  = !empty($_GET['q'])  ? $_GET['q']  : '';//GET请求
$post_q = !empty($_POST['q']) ? $_POST['q'] : '';//POST请求

/* 分解请求字符串 */
$a_get_q  = explode('/', $get_q);
$a_post_q = explode('/', $post_q);

/* 提取分解内容 */
if(!empty($post_q)){//POST请求优先
	$platName = !empty($a_post_q[0]) ? $a_post_q[0] : 'index';//平台名
	/*
		ucfirst()  将字符串首字母转成大写，遵循 Linux 下的大小写区分规则
	*/
	$mdlName  = ucfirst(!empty($a_post_q[1]) ? $a_post_q[1] . 'Controller' : 'IndexController');//模块名
	$mthName  = !empty($a_post_q[2]) ? $a_post_q[2] : 'index';//方法名
}else{//GET请求其次
	$platName = !empty($a_get_q[0]) ? $a_get_q[0] : 'index';//平台名
	$mdlName  = ucfirst(!empty($a_get_q[1]) ? $a_get_q[1] . 'Controller' : 'IndexController');//模块名
	$mthName  = !empty($a_get_q[2]) ? $a_get_q[2] : 'index';//方法名
}

//检测平台目录
if(!is_dir(dirname($_SERVER['SCRIPT_FILENAME'])."/{$platName}")){
	$platName = '';
}

//==================================================================================

/* 系统常量定义 */
defined('DEBUG')		or define('DEBUG', false);//程序模式，开发和生产,默认关闭调试
defined('DS')			or define('DS', DIRECTORY_SEPARATOR);//目录分隔符 '\' or '/'
defined('APP_PATH')		or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']).'/');//应用路径
defined('ROOT_PATH')	or define('ROOT_PATH', dirname(__FILE__).'/');//框架路径
defined('PLAT_PATH')	or define('PLAT_PATH', APP_PATH."{$platName}");//应用平台路径
defined('CLS_EXT')		or define('CLS_EXT', '.class.php');//类文件后缀
defined('REQUEST_TYPE') or define('REQUEST_TYPE', !empty($post_q) ? 'post' : 'get');//请求方式

/* 手动加载类库文件 */
require_once ROOT_PATH.'Libs/Singleton.class.php';//单例类
require_once ROOT_PATH.'Libs/Config.class.php';//配置类

/* 设置程序模式 */
if(DEBUG === true){
	//开启错误提示
	ini_set('display_errors', 'On');
}else if(DEBUG === false){
	//关闭错误提示
	ini_set('display_errors', 'Off');
}

/* 初始化参数 */
//初始化项目，检测应用目录下的 MVC 结构目录是否存在，无则添加
function initApp(){
	//创建控制器目录
	if(!is_dir(APP_PATH.'Controller')){
		mkdir(APP_PATH.'Controller');
		file_put_contents(APP_PATH.'Controller/index.html', '');
	}

	//创建模型目录
	if(!is_dir(APP_PATH.'Model')){
		mkdir(APP_PATH.'Model');
		file_put_contents(APP_PATH.'Model/index.html', '');
	}

	//创建视图目录
	if(!is_dir(APP_PATH.'View')){
		mkdir(APP_PATH.'View');
		file_put_contents(APP_PATH.'View/index.html', '');
	}

	//创建Session目录
	if(!is_dir(APP_PATH.'Session')){
		mkdir(APP_PATH.'Session');
		file_put_contents(APP_PATH.'Session/index.html', '');
	}

	//创建config目录
	if(!is_dir(APP_PATH.'config')){
		mkdir(APP_PATH.'config');
		file_put_contents(APP_PATH.'config/index.html', '');
	}

	//创建配置文件副本
	if(!is_file(APP_PATH.'config/config.php')){
		copy(ROOT_PATH.'Config/config.php', APP_PATH.'config/config.php');
	}
}
//启动自检
initApp();

/*
	自动加载所需类文件
*/
function __autoload($className){
	//框架类库
	$pLibs = array(
		'Controller',
		'MySQL',
		'Cookie',
		'Session',
		'Error',
		'MiniPDO'
	);
	if(in_array($className, $pLibs)){
		//加载框架公共类文件
		require_once ROOT_PATH."Libs/{$className}".CLS_EXT;
	}

	//应用类库
	if(preg_match("/^[a-zA-Z]+Controller$/", $className)){
		//加载应用控制器类文件
		require_once PLAT_PATH."Controller/{$className}".CLS_EXT;
		//echo PLAT_PATH."Controller/{$className}".CLS_EXT;
	}else if(preg_match("/^[a-zA-Z]+Model$/", $className)){
		//加载应用模型类文件
		require_once PLAT_PATH."Model/{$className}".CLS_EXT;
	}
}

/* 动作，调用控制器 */
$controller = new $mdlName();//实例化对象（可变类）
$controller -> $mthName();//可变方法（函数）
