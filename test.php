<?php
	header("Content-Type:text/html;charset=utf8");
	//-----缓冲区测试-----
	/*
	echo "ob缓存之前<br/>";

	//开启缓冲区
	ob_start();

	//输出数据
	echo "首次缓冲区数据";

	//获取缓冲区内容并清除缓冲区
	$str = ob_get_clean();

	//再次输出数据
	echo "二次缓冲区数据";

	//------文件指定编码测试------
	/*$writeStr = "这是一段编码测试内容，this is a encoding test content";
	$inCharset = mb_detect_encoding($writeStr);
	$writeStr = iconv($inCharset, 'GBK', $writeStr);
	file_put_contents("test.zero", $writeStr);
	echo $inCharset;*/

	/*$rs = Array ( 
		Array ( 
			"user" => "zero", 
			"content" => "", 
			"pubtime" => 1508033600, 
			"nickname" => "超级用户", 
			"avatar" => "/src/images/avatar.jpg" 
		), 
		Array ( 
			"user" => "admin", 
			"content" => "", 
			"pubtime" => 1508033800, 
			"nickname" => "超级管理员", 
			"avatar" => "/src/images/avatar.jpg" 
		), 
		Array ( 
			"user" => "admin", 
			"content" => "/answer/38.zero", 
			"pubtime" => 1508033800, 
			"nickname" => "超级管理员", 
			"avatar" => "/src/images/avatar.jpg" 
		), 
		Array ( 
			"user" => "admin", 
			"content" => "", 
			"pubtime" => 1508033800, 
			"nickname" => "超级管理员", 
			"avatar" => "/src/images/avatar.jpg" 
		), 
		Array ( 
			"user" => "admin", 
			"content" => "", 
			"pubtime" => 1508033800, 
			"nickname" => "超级管理员", 
			"avatar" => "/src/images/avatar.jpg" 
		), 
		Array ( 
			"user" => "admin", 
			"content" => "/answer/38.zero", 
			"pubtime" => 1508033800, 
			"nickname" => "超级管理员", 
			"avatar" => "/src/images/avatar.jpg" 
		)
	);

	print_r($rs);
	echo '<br/><br/>';

	for($i=0;$i<count($rs);$i++){
		if(empty($rs[$i]['content'])){
			unset($rs[$i]);
			$rs = array_values($rs);
			$i = -1;
		}
	}
	
	echo '<br/><br/>';
	print_r($rs);*/

	//------SQL防注入测试------

	$str = "select id,isDisplay from user where username='縗' OR 1 limit 1/*' and passwd='%'
Illegal mix of collations (latin1_swedish_ci,IMPLICIT) and (gbk_chinese_ci,COERCIBLE) for operation '='";
	echo addcslashes($str, '~!@#$%^&*()_+{}|-=[]:";\'>?./');
?>