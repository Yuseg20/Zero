<?php

/*
	MySQL PDO 类
*/

class MiniPDO{
	private $dsn = "";
	private $opt = array();
	private $pdo = null;
	private $stmt = null;

	//构造方法
	public function __construct(){
		//读取数据库配置信息
		$host = Config::get('mysql.host');		//数据库地址
		$port = Config::get('mysql.port');		//数据库端口
		$user = Config::get('mysql.user');		//数据库用户名
		$pwd  = Config::get('mysql.password');		//数据库密码
		$db   = Config::get('mysql.db');		//数据库名
		
		//配置数据源
		$this -> dsn = "mysql:{$host};port={$port};dbname={$db}";

		//配置option
		$this -> opt =array(
			PDO::MYSQL_ATTR_INIT_COMMAND => "set names UTF8"//设置数据库编码
		);

		try{
			//创建对象
			$this -> pdo = new PDO($this -> dsn, $user, $pwd, $this -> opt);
		}catch(Exception $e){
			//抛出异常信息
			throw new Exception($e -> getMessage());
		}
	}

	//查询 预处理参数
	public function query($sql, $params = array(), $fetchStyle = 1){
		//SQL 语句预处理
		$this -> stmt = $this -> pdo -> prepare($sql);
		//遍历并绑定参数
		foreach($params as $key => $val){
			$this -> stmt -> bindValue(":{$key}", $val);
		}
		//执行查询
		$this -> stmt -> execute();
		//设置返回结果集类型
		switch($fetchStyle){
			case 0:
				//数字索引
				$fetchStyle = PDO::FETCH_NUM;
				break;
			case 1:
				//关联数组
				$fetchStyle = PDO::FETCH_ASSOC;
				break;
			case 2:
				//数字索引和关联数组
				$fetchStyle = PDO::FETCH_BOTH;
				break;
			default:
				$fetchStyle = PDO::FETCH_ASSOC;
		}
		//获取查询结果集
		$rs = $this -> stmt -> fetchAll($fetchStyle);
		//范湖结果集
		return $rs;
	}

	//执行
	public function execute($sql){
		//返回受影响记录行数
		return $this -> pdo -> exec($sql);
	}

	//启动一个事务
	public function beginTransaction(){
		$this -> pdo -> beginTransaction();
	}

	//提交一个事务
	public function commit(){
		$this -> pdo -> commit();
	}

	//回滚一个事务
	public function rollBack(){
		$this -> pdo -> rollBack();
	}

	//关闭连接，释放资源
	public function close(){
		if($this -> stmt){
			$this -> stmt = null;
		}

		if($this -> pdo){
			$this -> pdo = null;
		}
	}
}
