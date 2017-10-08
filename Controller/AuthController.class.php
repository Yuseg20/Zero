<?php
	/* 认证控制器 */
	class AuthController extends Controller{

		//注册
		public function register(){
			//设置请求方式，仅POST
			$this -> request('post');
			//设置响应数据格式
			$this -> format('json');

			//初始化响应参数
			$type = 'user_register';
			$status = 0;

			//接收请求数据
			$username = $_POST['username'];
			$nickname = $_POST['nickname'];
			$password = md5($_POST['password']);// 32位MD5加密
			$rePassword = $_POST['rePassword'];

			//业务处理
			$auth = new AuthModel();

			if($auth -> hasUser($username) === true){
				//用户已存在
				$status = -1;
			}else{
				//用户不存在,写入新用户注册信息
				if($auth -> register($username, $password, $nickname) === false){
					//写入失败
					$status = -2;
				}else{
					//写入完成，注册成功，存储登录状态
					$_SESSION['logined_user'] = $username;
				}
			}

			//设置数据
			$this -> setJson('type', $type);
			$this -> setJson('status', $status);

			//发送响应数据
			$this -> response();
		}

		//登录
		public function login(){
			//设置请求方式，仅POST
			$this -> request('post');
			//设置响应数据格式
			$this -> format('json');

			//初始化响应参数
			$type = 'user_login';
			$status = 0;

			//接收请求数据
			$username = $_POST['username'];
			$password = $_POST['password'];

			//业务处理
			$auth = new AuthModel();
			//根据用户名获取密码
			$pwd = $auth -> getPasswordByUser($username);
			//密码匹配成功
			if(md5($password) == $pwd){
				//存储会话
				$_SESSION['logined_user'] = $username;
			}else{
				$status = -1;
			}

			//设置数据
			$this -> setJson('type', $type);
			$this -> setJson('status', $status);

			//发送响应数据
			$this -> response();
		}

		//注销
		public function logout(){
			//设置请求方式， 仅POST
			$this -> request('post');
			//设置响应数据格式
			$this -> format('json');

			//初始化响应参数
			$type = 'user_logout';
			$status = 0;

			//业务处理
			if(isset($_SESSION['logined_user'])){
				//清除登录状态
				unset($_SESSION['logined_user']);
			}

			//设置响应数据
			$this -> setJson('type', $type);
			$this -> setJson('status', $status);

			//发送响应数据
			$this -> response();
		}
	}
?>