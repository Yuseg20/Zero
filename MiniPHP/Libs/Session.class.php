<?php
	/*
		自定义Session类
	*/
class Session{
	static $pdo = null;

	//构造方法
	public function __construct(){

		//文件存储
		if(Config::get('session.display') == "on"){
			ini_set('session.save_path', Config::get('session.save_path'));//Session 文件保存位置
			ini_set('session.gc_maxlifetime', Config::get('session.maxlifetime'));//Session 生命周期 s
			ini_set('session.gc_probability', Config::get('session.probability'));//Session 回收几率分子
			ini_set('session.gc_divisor', Config::get('session.divisor'));//Session 回收几率分母
			return;
		}

		//Redis 数据库存储
		if(Config::get('redis.display') == 'on'){
			//设置session存储机制为“用户自定义”，默认为文件
			ini_set('session.save_handler', 'user');

			//设定动作函数，代表session会话处理：开始、结束、读取、写入、删除、回收。
			//array(对象, 方法名)
			session_set_save_handler(
				array($this, 'start'),
				array($this, 'finish'),
				array($this, 'read'),
				array($this, 'write'),
				array($this, 'remove'),
				array($this, 'gc')
			);

			//数据库
			$dsn = "mysql:host=localhost;port=3306;dbname=meblog";
			$opt = array(
				PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8"//设置数据库编码
			);

			//实例化pdo
			//self::$pdo = new pdo($dsn, 'root', '', $opt);
			self::$pdo = new MiniPDO();
		}
	}

	//开始 session_start() -> 1
	public function start(){
	}

	//结束 session_start() -> 5
	public function finish(){
	}

	//读取[系统传入 sessionid] session_start() -> 2
	public function read($sessionId){
		$sql = "select sess_data from session where sess_id=:sessionId";
		//获取PDO结果集
		$result = self::$pdo -> query($sql, array(
			"sessionId" => $sessionId
		), 1);
		$data = !empty($result[0]['sess_data']) ? $result[0]['sess_data'] : '';
		return $data;
	}

	//写入[系统传入 sessionid 和 数据] session_start() -> 4
	public function write($sessionId, $sessionData){
		//return;
		//获取当前时系统时间戳
		$currentTime = time();
		//replace into 会先删除表中已有的相同主键的数据，然后在插入新值
		$sql = "replace into session(sess_id, sess_data, sess_time)";
		$sql .= " values ('{$sessionId}','{$sessionData}','{$currentTime}')";
		//执行SQL语句
		//$result = self::$pdo -> exec($sql);
		$result = self::$pdo -> execute($sql);
	}

	//删除[系统传入 sessionid], 调用 session_destroy() 时调用
	public function remove($sessionId){
		$sql = "delete from session where sess_id='{$sessionId}'";
		$result = self::$pdo -> execute($sql);
	}

	//回收[系统传入 session 文件生存最大超时时间 s ] session_start() -> 3
	public function gc($maxlifetime){
		//获取当前时系统时间戳
		$currentTime = time();
		$sql ="delete from session where {$currentTime}-sess_time>{$maxlifetime}";
		$result = self::$pdo -> execute($sql);
	}
}
?>