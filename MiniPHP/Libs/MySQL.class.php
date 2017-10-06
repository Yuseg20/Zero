<?php
/*
	MySQL 数据库操作工具
*/

class MySQL{
	private $dbl = null;//数据库连接对象
	public $status = 0;//状态
	public $msg = '';//说明

	//构造方法,初始化数据库连接
	public function __construct(){
		//设置配置项
		Config::setConfig('mysql');
		//读取配置值
		$host = Config::getValue('host');	//MySQL主机地址
		$user = Config::getValue('user');	//用户名
		$pwd  = Config::getValue('pwd');	//密码
		$db   = Config::getValue('db');		//选择的数据库
		$this -> dbl = @new mysqli($host, $user, $pwd, $db);//建立了数据库连接
		if($this->dbl->connect_error){//错误处理
			$this->status = -1;
			$this->msg = $this->dbl->connect_error;
			return;
		}
		$this->dbl->set_charset('utf8');//设置默认字符编码
	}

	//query 操作
	public function query($sql){
		$q_result = $this->dbl->query($sql);//执行 sql 语句
		//select 之外的部分操作
		if($q_result == '1'){
			return '1';//成功
		}else if($q_result == ''){
			return '0';//失败
		}
		//查询部分业务
		$r_result = array();
		if($q_result == null){return $q_result;}
		while($n_result = $q_result->fetch_assoc()){//循环获取结果集
			$r_result[] = $n_result;//组装结果集
		}
		$q_result->free();//释放资源
		return $r_result;//返回结果
	}

	//关闭数据库连接
	public function close(){
		if($this->dbl){
			$this->dbl->close();//关闭数据库连接
		}
	}
}
