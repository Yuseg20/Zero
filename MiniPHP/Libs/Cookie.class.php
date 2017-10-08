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
