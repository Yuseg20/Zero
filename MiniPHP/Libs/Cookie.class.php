<?php

/* Cookie 类 */

class Cookie{

	//设置Cookie
	public function setVal($cookieName, $cookieValue, $cookieTime = 0, $path = '/'){
		if($cookieTime > 0){
			$cookieTime += time();
		}

		setcookie($cookieName, $cookieValue, $cookieTime, $path);
	}

	//获取Cookie
	public function getVal($cookieName){
		return !empty($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : null;
	}
}
