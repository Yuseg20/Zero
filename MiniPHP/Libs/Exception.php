<?php

header('Content-Type:text/html;charset=UTF-8');

/* 异常处理 */
try{
	echo 'ok';
	throw new Exception('异常信息内容', 1);
}catch(Exception $e){
	print_r($e -> getMessage());
}
