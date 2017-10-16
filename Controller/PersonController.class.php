<?php
	/* 个人中心控制器 */
	class PersonController extends Controller{

		//获取个人中心主页
		public function index(){
			//------业务处理------
			$webConfig = new WebConfigModel();//网站配置

			$dname = $webConfig -> getDName();//域名
			$root = $webConfig -> getRoot();//根目录

			if(empty($_SESSION['logined_user'])){
				//未登录
				echo "<script> window.alert('请先登录！');location.href='{$dname}{$root}' </script>";
				return;
			}			

			//已登录
			$person = new PersonModel($_SESSION['logined_user']);//个人信息
			$nickname = $person -> getNickname();
			if(empty($nickname)){
				$nickname = $person -> getUser();
			}

			//设置登录状态
			$logout = 'none';
			$logined = 'block';

			//------设置渲染数据------
			$this -> set('webDName', $dname);//设置域名
			$this -> set('webRoot', $root);//设置根目录
			$this -> set('webIntro', $webConfig -> getIntro());//设置介绍标语
			$this -> set('webCopyright', $webConfig -> getCopyright());//设置版权信息
			$this -> set('logout', $logout);//注销状态标志
			$this -> set('logined', $logined);//登录状态标志
			$this -> set('avatar', $dname.$root.$person -> getAvatar());//用户头像
			$this -> set('nickname', $nickname);//用户名
			$this -> set('reg-time', $person -> getRegTime());//注册时间
			$this -> set('index-target', '_blank');//设置首页跳转方式

			//------页面渲染------
			$this -> display('person.html');
		}

		//获取我的提问
		public function getMyAsk(){
			//------设置请求方式------
			$this -> request('post');

			//------设置响应数据格式------
			$this -> format('json');

			//------初始化响应数据------
			$type = 'get_my_ask';
			$status = 0;
			$content = '';

			//------业务处理------
			if(!empty($_SESSION['logined_user'])){
				//已登录
				$article = new ArticleModel();
				//获取用户名提问列表
				$askList = $article -> getAskList($_SESSION['logined_user'], 0, 12);

				// 0 记录数据匹配
				for($i=0;$i<count($askList);$i++){
					if(empty($askList[$i]['answerId'])){
						$askList[$i]['answers'] = 0;
					}
				}

				//------设置渲染数据------
				$this -> set('ask-list', $askList);

				//获取渲染后的页面
				$content = $this -> getXView('ask.html');
			}else{
				//未登录
				$status = -1;
			}

			//------设置响应数据------
			$this -> setJson('type', $type);
			$this -> setJson('status', $status);
			$this -> setJson('content', $content);

			//------发送响应数据------
			$this -> response();
		}

		//获取创建提问
		public function getCreateAsk(){
			//------设置请求方式------
			$this -> request('post');

			//------设置响应数据格式------
			$this -> format('json');

			//------初始化响应数据------
			$type = 'get_create_ask';
			$status = 0;
			$content = '';

			//------业务处理------
			if(!empty($_SESSION['logined_user'])){
				//已登录

				//获取渲染后的页面
				$content = $this -> getXView('create-ask.html');
			}else{
				//未登录
				$status = -1;
			}

			//------设置响应数据------
			$this -> setJson('type', $type);
			$this -> setJson('status', $status);
			$this -> setJson('content', $content);

			//------发送响应数据------
			$this -> response();
		}

		//发布提问
		public function publish(){
			//------设置请求方式------
			$this -> request('post');

			//------设置响应数据格式------
			$this -> format('json');

			//------初始化响应数据------
			$type = 'get_publish';
			$status = 0;

			//------业务处理------
			if(!empty($_SESSION['logined_user'])){
				//已登录
				//获取数据
				$user = $_SESSION['logined_user'];
				$title = $_POST['title'];
				$desc = $_POST['desc'];

				//创建文章模型
				$article = new ArticleModel();

				if($article -> setAsk($user, $title, $desc) === false){
					//发布失败
					$status = -2;
				}
			}else{
				//未登录
				$status = -1;
			}

			//------设置响应数据------
			$this -> setJson('type', $type);
			$this -> setJson('status', $status);

			//------发送响应数据------
			$this -> response();
		}

		//获取我的回答
		public function getMyAnswer(){
			//------设置请求方式------
			$this -> request('post');

			//------设置响应数据格式------
			$this -> format('json');

			//------初始化响应数据------
			$type = 'get_my_answer';
			$status = 0;
			$content = '';

			//------业务处理------
			if(!empty($_SESSION['logined_user'])){
				//已登录
				$article = new ArticleModel();
				//获取用户名提问列表
				$answerList = $article -> getAnswerList($_SESSION['logined_user'], 0, 12);

				//------设置渲染数据------
				$this -> set('answer-list', $answerList);

				//获取渲染后的页面
				$content = $this -> getXView('answer.html');
			}else{
				//未登录
				$status = -1;
			}

			//------设置响应数据------
			$this -> setJson('type', $type);
			$this -> setJson('status', $status);
			$this -> setJson('content', $content);

			//------发送响应数据------
			$this -> response();
		}

		//获取基本信息
		public function getBaseInfo(){
			//------设置请求方式------
			$this -> request('post');

			//------设置响应数据格式------
			$this -> format('json');

			//------初始化响应数据------
			$type = 'get_base_info';
			$status = 0;
			$content = '';

			//------业务处理------
			if(!empty($_SESSION['logined_user'])){
				//已登录
				$person = new PersonModel($_SESSION['logined_user']);
				//获取用户名和昵称
				$user = $person -> getUser();
				$nickname = $person -> getNickname();

				//------设置渲染数据------
				$this -> set('user', $user);
				$this -> set('nickname', $nickname);

				//获取渲染后的页面
				$content = $this -> getXView('info.html');
			}else{
				//未登录
				$status = -1;
			}

			//------设置响应数据------
			$this -> setJson('type', $type);
			$this -> setJson('status', $status);
			$this -> setJson('content', $content);

			//------发送响应数据------
			$this -> response();
		}

		//获取密码修改
		public function getPasswordUpdate(){
			//------设置请求方式------
			$this -> request('post');

			//------设置响应数据格式------
			$this -> format('json');

			//------初始化响应数据------
			$type = 'get_password_update';
			$status = 0;
			$content = '';

			//------业务处理------
			if(!empty($_SESSION['logined_user'])){
				//已登录
				$content = $this -> getXView('password.html');
			}else{
				//未登录
				$status = -1;
			}

			//------设置响应数据------
			$this -> setJson('type', $type);
			$this -> setJson('status', $status);
			$this -> setJson('content', $content);

			//------发送响应数据------
			$this -> response();
		}

		//保存基本信息
		public function saveBaseInfo(){
			//------设置请求方式------
			$this -> request('post');

			//------设置响应数据格式------
			$this -> format('json');

			//------初始化响应数据------
			$type = 'save_base_info';
			$status = 0;

			//------业务处理------
			if(!empty($_SESSION['logined_user'])){
				//已登录
				$user = $_SESSION['logined_user'];
				$nickname = $_POST['nickname'];

				//存储用户昵称
				$person = new PersonModel();
				if($person -> setNickname($user, $nickname) === false){
					$status = -2;
				}
			}else{
				//未登录
				$status = -1;
			}

			//------设置响应数据------
			$this -> setJson('type', $type);
			$this -> setJson('status', $status);

			//------发送响应数据------
			$this -> response();
		}

		//保存密码
		public function savePassword(){
			//------设置请求方式------
			$this -> request('post');

			//------设置响应数据格式------
			$this -> format('json');

			//------初始化响应数据------
			$type = 'save_password';
			$status = 0;

			//------业务处理------
			if(!empty($_SESSION['logined_user'])){
				//已登录
				$user = $_SESSION['logined_user'];
				$oldPwd = $_POST['oldPwd'];
				$newPwd = $_POST['newPwd'];

				//存储用户昵称
				$person = new PersonModel();
				$status = $person -> setPassword($user, $oldPwd, $newPwd);
			}else{
				//未登录
				$status = -1;
			}

			//------设置响应数据------
			$this -> setJson('type', $type);
			$this -> setJson('status', $status);

			//------发送响应数据------
			$this -> response();
		}
	}
?>