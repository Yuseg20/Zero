<?php
	header("Content-Type:text/html;charset=utf8");
	//-----缓冲区测试-----

	echo "ob缓存之前<br/>";

	//开启缓冲区
	ob_start();

	//输出数据
	echo "首次缓冲区数据";

	//获取缓冲区内容并清除缓冲区
	$str = ob_get_clean();

	//再次输出数据
	echo "二次缓冲区数据";
?>