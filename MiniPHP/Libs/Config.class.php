<?php

/* 配置类 */

class Config{

	private static $jsonConfig = null;//配置信息对象

	//构造方法 私有化
	private function __construct(){
	}

	//加载配置文件
	public static function load($filename){
		//判断文件是否存在
		if(!is_file(APP_PATH."config/{$filename}.php")){
			return null;
		}

		//读取相应配置文件
		$arrConfig = require_once(APP_PATH."config/{$filename}.php");
		//转换配置信息为json对象
		self::$jsonConfig = json_decode(json_encode($arrConfig));
	}

	//获取指定配置项
	public static function get($keyStr = ''){
		if(empty($keyStr)){
			return -1;
		}

		//分解键名
		$keys = explode('.', $keyStr);
		//初始化json对象
		$result = self::$jsonConfig;
		//循环取值
		for($i=0;$i<count($keys);$i++){
			//判断对象属性是否存在
			if(!property_exists($result, $keys[$i])){
				//不存在则结束返回
				return -2;
			}

			//存在赋值
			$result = $result -> $keys[$i];
		}

		//返回结果
		return $result;
	}
}
