<?php

/* 单例工具 */

class Singleton{
	
	//初始化对象存储变量
	private static $obj = null;

	//不允许实例化对象
	private function __construct(){
	}

	public static function getObj($className){
		if(self::$obj === null){//对象不存在
			return new $className();//返回实例化后的对象
		}else{//否则
			return self::$obj;
		}
	}
}
