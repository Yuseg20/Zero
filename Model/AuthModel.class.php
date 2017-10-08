<?php
	/* 认证模型 */
	class AuthModel{

		//初始化
		public function __construct(){
		}

		//验证用户名
		public function hasUser($user = ''){
			if(empty($user)){
				return -1;
			}
			
			//初始化参数数组
			$params = array();
			//创建数据库对象
			$pdo = new MiniPDO();
			//构造查询语句
			$sql = "select * from user_auth where ua_user=:user";
			//设置参数数组
			$params['user'] = $user;
			//执行查询
			$rs = $pdo -> query($sql, $params);
			//关闭数据库连接
			$pdo -> close();
			if($rs){
				//用户名已存在
				return true;
			}else{
				//用户名不存在
				return false;
			}
		}

		//写入注册信息
		public function register($user = '', $pwd = '', $nickname = ''){
			if(empty($user) || empty($pwd)){
				return -1;
			}

			//初始化参数数组
			$params1 = array();
			$params2 = array();
			//创建数据库对象
			$pdo = new MiniPDO();
			//获取当前系统时间
			$time = time();
			$sql1 = "insert into user_auth (ua_user,ua_pwd) values ('{$user}','{$pwd}')";
			$params1['user'] = $user;
			$params1['pwd'] = $pwd;
			$sql2 = "insert into user_info (ua_user,ui_nickname,ui_reg_time) 
									values ('{$user}','{$nickname}','{$time}')";
			$params2['user'] = $user;
			$params2['nickname'] = $nickname;
			$params2['time'] = $time;
			//开启事务
			$pdo -> beginTransaction();
			$rs1 = $pdo -> execute($sql1);
			$rs2 = $pdo -> execute($sql2);
			if($rs1 && $rs2){
				//写入成功,提交事务
				$pdo -> commit();
				//关闭数据库连接
				$pdo -> close();

				return true;
			}else{
				//写入失败,回滚事务
				$pdo -> rollback();
				//关闭数据库连接
				$pdo -> close();

				return false;
			}
		}

		//根据用户名获取密码
		public function getPasswordByUser($user = ''){
			if(empty($user)){
				return -1;
			}

			//初始化参数数组
			$params = array();
			//创建数据库对象
			$pdo = new MiniPDO();
			//构建查询语句
			$sql = "select ua_pwd from user_auth where ua_user=:user";
			//设置参数
			$params['user'] = $user;
			//查询
			$rs = $pdo -> query($sql, $params);

			if(empty($rs)){
				return -1;
			}else{
				return $rs[0]['ua_pwd'];
			}
		}
	}
?>