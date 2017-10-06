<?php

/* 配置类 */

class Config{

	private static $allConfig = [];//所有配置信息
	private static $config = [];//指定配置项

	//构造方法 私有化
	private function __construct(){
	}

	//加载配置文件
	public static function load($filename){
		//判断文件是否存在
		if(!is_file(ROOT_PATH."Config/{$filename}.php")){
			return null;
		}
		//引用相应配置文件
		self::$allConfig = require_once(ROOT_PATH."Config/{$filename}.php");
	}

	//获取指定配置项
	public static function setConfig($configName){
		if(!empty(self::$allConfig[$configName])){
			self::$config = self::$allConfig[$configName];
		}else{
			self::$config = array();
		}
	}

	//获取指定值
	public static function getValue($key){
		return !empty(self::$config[$key]) ? self::$config[$key] : null;
	}
}
