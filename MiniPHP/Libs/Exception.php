<?php

/* 异常处理 */
try{
	throw new Exception('异常信息内容', 1);
}catch(Exception $e){
	print_r($e -> getMessage());
}
