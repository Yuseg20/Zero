<?php
	/*
		Redis 操作类
	*/

	class MRedis {
		private $redis = null;

		//构造方法
		public function __construct(){
			$this -> redis = new Redis();
			$this ->redis -> connect('127.0.0.1', 6379);
		}

		//------String 类型处理

		//设置 String 值
		public function set($key = '', $val = '', $timeout = -1){
			if(empty($key) || $this -> redis == null) return;

			return $this -> redis -> set($key, $val, $timeout);
		}

		//获取 String 值
		public function get($key = ''){
			if(empty($key) || $this -> redis == null) return;

			return $this -> redis -> get($key);
		}

		//移除 Sting 值
		public function del($key = ''){
			if(empty($key) || $this -> redis == null) return;

			return $this -> redis -> del($key);
		}

		//------Hash 类型处理

		//设置 Hash 值
		public function hSet($key = '', $hashKey = '', $val = ''){
			if(empty($key) || empty($hashKey) || empty($val) || $this -> redis == null) return;

			return $this -> redis -> hSet($key, $hashKey, $val);
		}

		//获取 Hash 值
		public function hGet($key = '', $hashKey = ''){
			if(empty($key) || empty($hashKey) || $this -> redis == null) return;

			return $this -> redis -> hGet($key, $hashKey);
		}

		//获取 Hash 表长度
		public function hLen($key = ''){
			if(empty($key) || $this -> redis == null) return;

			return $this -> redis -> hLen($key);
		}

		//删除 Hash 值
		public function hDel($key = '', $hashKey = ''){
			if(empty($key) || empty($hashKey) || $this -> redis == null) return;

			return $this -> redis -> hDel($key, $hashKey);
		}

		//以数组形式获取 Hash 表中的Keys
		public function hKeys($key = ''){
			if(empty($key) || $this -> redis == null) return;

			return $this -> redis -> hKeys($key);
		}

		//以数组形式获取 Hash 表中的Vals
		public function hVals($key = ''){
			if(empty($key) || $this -> redis == null) return;

			return $this -> redis -> hVals($key);
		}

		//取得整个HASH表的信息，返回一个以KEY为索引VALUE为内容的数组
		public function hGetAll($key = ''){
			if(empty($key) || $this -> redis == null) return;

			return $this -> redis -> hGetAll($key);
		}

		//------系统通用函数

		//关闭Redis的 connect,open 实例
		public function close(){
			if($this -> redis == null) return;

			$this -> redis -> close();
		}

		//检查当前实例连通性
		public function ping(){
			if($this -> redis == null) return;

			return $this -> redis -> ping();
		}

		//随机返回一个存在与 Redis 的 Key
		public function randomKey(){
			if($this -> redis == null) return;

			return $this -> redis -> randomKey();
		}

		//返回某种计算模式取得的 Keys
		public function keys($keyMode = '*'){
			if($this -> redis == null) return;

			return $this -> redis -> keys($keyMode);
		}
	}
?>