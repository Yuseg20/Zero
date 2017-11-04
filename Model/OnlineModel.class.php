<?php
	/* 在线统计模型 */
	class OnlineModel{

		//初始化
		public function __construct(){
		}

		//设置、更新在线生命周期并获取在线人数
		public function getOnlines($cid = ''){
			//获取生命周期
			$otime = Config::get('system.otime');

			//创建 Redis 对象
			$mredis = new MRedis();

			//设置、更新在线生命周期
			$mredis -> set('online:'.$cid, '', $otime);

			//获取在线人数
			$onlines = $mredis -> keys('online:*');

			//关闭 Redis 实例
			$mredis -> close();

			//返回结果
			return count($onlines);
		}
	}
?>