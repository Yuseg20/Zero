<?php

/* Cookie 类 */

class Cookie{

	//设置Cookie
	public function setVal($cookieName, $cookieValue, $cookieTime = 0){
		if($cookieTime == 0){
			setcookie($cookieName, $cookieValue);
		}else{
			setcookie($cookieName, $cookieValue, time()+$cookieTime);
		}
	}

	//获取Cookie
	public function getVal($cookieName){
		return !empty($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : null;
	}
}

$cookie = new Cookie();
if($cookie -> getVal('user') !== null){
	echo $cookie -> getVal('user');
}else{
	echo 'Cookie 初始化...';
	$cookie -> setVal('user', 'admin', 3600);
}
