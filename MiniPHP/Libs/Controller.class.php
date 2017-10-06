<?php

/* 基础控制器 */

abstract class Controller{

	protected $data = [];//渲染的数据数组

	//构造方法
	function __construct(){
		//载入配置
		Config::load('Config');

		//自定义Session
		new Session();
		//启动Session
		session_start();

		//自定义错误处理
		new Error();

		//设置配置项
		Config::setConfig('view');
		//设置页面字符编码
		header('Content-Type:text/html;charset='.Config::getValue('charset'));
	}



	//添加渲染数据
	function set($name, $arrVal){
		$this -> data[$name] = $arrVal;//添加数据
	}

	//获取数据
	function get($name){
		return $this -> data[$name];//返回数据
	}

	//页面渲染
	function display($viewFile = 'index.html'){
		//设置配置项
		Config::setConfig('path');
		include Config::getValue('view_path').$viewFile;//页面渲染
	}

	//请求拦截
	function onlyRequest($interceptName, $fn = null){
		if(strtolower($interceptName) != REQUEST_TYPE){//符合请求方式
			if(!empty($fn)){
				$fn();
			}
			exit();//拦截
		}
	}
}
