<?php

/* 错误类 */

class Error{

	//构造方法
	public function __construct(){
		//设置错误处理方法
		set_error_handler(array(
			$this, 'errorProcess'
		));
	}

	/*
		错误处理方法
		$errNo		错误码
		$errStr		错误信息
		$errFile	错误文件
		$errLine	错误行号
	*/
	public function errorProcess($errNo, $errStr, $errFile, $errLine){
		echo "errorok";
		echo "<h1><font color='#CCC'>MiniPHP</font></h1>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;错误代号：{$errStr}<br/>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;错误信息：{$errStr}<br/>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;错误文件：{$errFile}<br/>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;错误行号：{$errLine}<br/>";
	}
}


