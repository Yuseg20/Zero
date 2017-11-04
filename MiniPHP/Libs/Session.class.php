<?php
	/*
		自定义Session类
	*/
class Session{
	static $mredis = null;
	static $maxlifetime = 0;

	//构造方法
	public function __construct(){

		self::$maxlifetime = Config::get('session.maxlifetime');

		//文件模式存储
		if(Config::get('session.mode') == "file"){
			ini_set('session.save_path', Config::get('session.save_path'));//Session 文件保存位置
			ini_set('session.gc_maxlifetime', self::$maxlifetime);//Session 生命周期 s
			ini_set('session.gc_probability', Config::get('session.probability'));//Session 回收几率分子
			ini_set('session.gc_divisor', Config::get('session.divisor'));//Session 回收几率分母
			return;
		}else if(Config::get('session.mode') == "redis"){
			//Redis 模式存储
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

			//实例化pdo
			self::$mredis = new MRedis();
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
		return self::$mredis -> get('session:'.$sessionId);
	}

	//写入[系统传入 sessionid 和 数据] session_start() -> 4
	public function write($sessionId, $sessionData){
		self::$mredis -> set('session:'.$sessionId, $sessionData, self::$maxlifetime);
	}

	//删除[系统传入 sessionid], 调用 session_destroy() 时调用
	public function remove($sessionId){
		self::$mredis -> del('session:'.$sessionId);
	}

	//回收[系统传入 session 文件生存最大超时时间 s ] session_start() -> 3
	public function gc($maxlifetime){
	}
}
?>