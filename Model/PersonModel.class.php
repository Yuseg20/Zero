<?php
	/* 个人中心模型 */
	class PersonModel{

		private $person = array();//用户信息

		//初始化
		public function __construct($user = ''){
			if(empty($user)){
				return;
			}

			//参数过滤[防SQL注入]
			$user = addslashes($user);

			//创建数据库对象
			$pdo = new MiniPDO();

			//查询用户信息
			$sql = "SELECT ua_user,ui_nickname,ui_avatar,ui_reg_time
					FROM user_info
					WHERE ua_user=:user";
			$params = array(
				'user' => $user
			);
			$arr = $pdo -> query($sql, $params);

			//关闭数据库连接
			$pdo -> close();

			//存储配置
			$this -> person['user'] = $arr[0]['ua_user'];
			$this -> person['avatar'] = $arr[0]['ui_avatar'];
			$this -> person['nickname'] = $arr[0]['ui_nickname'];
			$this -> person['regtime'] = $arr[0]['ui_reg_time'];
		}

		//获取用户名
		public function getUser(){
			if(empty($this -> person['user'])){
				return;
			}

			return $this -> person['user'];
		}

		//获取用户头像
		public function getAvatar(){
			if(empty($this -> person['avatar'])){
				return;
			}

			return $this -> person['avatar'];
		}

		//获取用户昵称
		public function getNickname(){
			if(empty($this -> person['nickname'])){
				return;
			}

			return $this -> person['nickname'];
		}

		//获取用户注册时间
		public function getRegTime(){
			if(empty($this -> person['regtime'])){
				return;
			}

			return date("Y-m-d", $this -> person['regtime']);
		}

		//修改用户昵称
		public function setNickname($user = '', $nickname = ''){
			if(empty($user)){
				return false;
			}

			//参数过滤[防SQL注入]
			$user = addslashes($user);
			$nickname = addslashes($nickname);

			//创建数据库对象
			$pdo = new MiniPDO();

			//更新用户昵称
			$sql = "update user_info set ui_nickname='{$nickname}' where ua_user='{$user}'";

			//执行
			$rs = $pdo -> execute($sql);

			//关闭数据库连接
			$pdo ->close();

			//返回结果
			return $rs;
		}

		//修改用户密码
		public function setPassword($user = '', $oldPwd = '', $newPwd = ''){
			if(empty($user) || empty($oldPwd) || empty($newPwd)){
				return false;
			}

			//参数过滤[防SQL注入]
			$user = addslashes($user);

			//创建数据库对象
			$pdo = new MiniPDO();

			//获取密码
			$sql = "select ua_pwd from user_auth where ua_user=:user";
			$rs = $pdo -> query($sql, array(
				'user' => $user
			));
			$pwd = $rs[0]['ua_pwd'];

			//核对密码
			if(md5($oldPwd) != $pwd){
				//关闭数据库连接
				$pdo -> close();

				//旧密码错误
				return -2;
			}

			//更新用户密码
			$ua_pwd = md5($newPwd);
			$sql = "update user_auth set ua_pwd='{$ua_pwd}' where ua_user='{$user}'";

			//执行
			$rs = $pdo -> execute($sql);

			//关闭数据库连接
			$pdo -> close();

			//返回结果
			if($rs === false){
				//数据库操作出错
				return -3;
			}

			return 0;
		}
	}
?>