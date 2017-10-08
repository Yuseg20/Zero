<?php

/* 基础控制器 */

abstract class Controller{

	protected $data = [];//页面渲染的数据数组
	protected $jsonData = [];//json数组

	//构造方法
	function __construct(){
		//载入配置
		Config::load('config');

		//自定义Session
		new Session();
		//启动Session
		session_start();

		//自定义错误处理
		new Error();

		//设置时区
		date_default_timezone_set(Config::get('system.timezone'));
		
		//设置页面字符编码
		header('Content-Type:text/html;charset='.Config::get('view.charset'));
	}

	//页面渲染
	function display($viewFile = 'index.html'){
		//读取文件内容
		$content = file_get_contents(Config::get('path.view_path').$viewFile);
		//检测文件UTF8编码头BOM
		if(substr($content, 0, 3) == pack('C3', 0xEF, 0xBB, 0xBF)){
			//去除BOM头
			file_put_contents(Config::get('path.view_path').$viewFile, substr($content, 3));
			echo "去除BOM头";
		}
		//页面渲染
		include_once Config::get('path.view_path').$viewFile;
	}

	//请求拦截
	function request($interceptName, $fn = null){
		if(strtolower($interceptName) != REQUEST_TYPE){//符合请求方式
			if(!empty($fn)){
				$fn();
			}
			exit();//拦截
		}
	}

	//设置响应数据格式
	function format($format){
		$head = '';
		if($format == 'html'){
			//html数据格式
			$head = "Content-Type:text/html";
		}else if($format == 'json'){
			//json数据格式
			$head = "Content-Type:application/json";
		}else if($format == 'xml'){
			//xml数据格式
			$head = "Content-Type:text/xml";
		}else{
			//参数无效
			return -1;
		}

		header($head);
	}

	//添加渲染数据
	function set($name, $arrVal){
		$this -> data[$name] = $arrVal;//添加数据
	}

	//获取数据
	function get($name){
		return $this -> data[$name];//返回数据
	}

	//设置 Json 值
	function setJson($key = '', $val = ''){
		if(empty($key)){
			return;
		}

		//存储相应键值
		$this -> jsonData[$key] = $val;
	}

	//获取 Json 值
	function getJson($key = ''){
		if(empty($key)){
			//获取完整 json
			return json_encode($this -> jsonData);
		}else{
			//获取指定 json
			if(array_key_exists($key, $this -> jsonData)){
				return '{"'. $key .'":"'. $this -> jsonData[$key] .'"}';
			}
		}
	}

	//发送响应数据
	function response($data = ''){
		if(empty($data)){
			echo $this -> getJson();
		}else{
			echo $data;
		}
	}
}
