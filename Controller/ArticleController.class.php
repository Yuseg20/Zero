<?php
	/* 文章控制器 */
	class ArticleController extends Controller{

		//文章页
		public function article(){
			//------接收请求数据------
			$aid = $_GET['aid'];

			//------初始化响应数据------
			$logout = 'block';//注销状态
			$logined = 'none';//登录状态

			//------业务处理------
			$webConfig = new WebConfigModel();
			$ask = new AskModel($aid);
			$answer = new AnswerModel($aid);

			if($ask -> getStatus()){
				return;
			}

			//当前存在登录用户
			if(!empty($_SESSION['logined_user'])){
				//切换登录状态
				$logout = 'none';
				$logined = 'block';
			}

			//------设置响应数据------
			$this -> set('webDName', $webConfig -> getDName());//设置域名
			$this -> set('webRoot', $webConfig -> getRoot());//设置根目录
			$this -> set('webIntro', $webConfig -> getIntro());//设置介绍标语
			$this -> set('webCopyright', $webConfig -> getCopyright());//设置版权信息
			$this -> set('logout', $logout);//注销状态标志
			$this -> set('logined', $logined);//登录状态标志
			$this -> set('index-target', '');//设置首页跳转方式
			$this -> set('title', $ask -> getTitle());//提问标题
			$this -> set('desc', $ask -> getDesc());//提问描述
			$this -> set('pubtime', $ask -> getPubTime());//提问发布时间
			$this -> set('pv', $ask -> getPV());//提问浏览量
			$this -> set('nickname', $ask -> getNickname());//提问者昵称
			$this -> set('avatar', $ask -> getAvatar());//提问者头像
			$this -> set('ask-list', $ask -> getAskList());//TA的问题列表
			$this -> set('answer-pages', $answer -> getAnswerPages());//回答列表页数
			$this -> set('answer-list', $answer -> getAnswerList(0, 5));//回答列表
			$this -> set('aid', $aid);//文章编号
			
			//------页面渲染------
			$this -> display('article.html');
		}

		//提交答案
		public function submitAnswer(){
			//------设置请求方式，仅POST------
			$this -> request('post');
			//------设置响应数据格式------
			$this -> format('json');

			//------初始化响应参数------
			$type = 'submit_answer';
			$status = 0;

			//------接收请求数据------
			$aid = $_POST['aid'];
			$content = $_POST['content'];

			//------业务处理------
			if(empty($_SESSION['logined_user'])){
				//用户未登录
				$status = -1;
			}else{
				//用户已登录
				$user = $_SESSION['logined_user'];
				$answer = new AnswerModel();
				if($answer -> setAnswer($aid, $user, $content) == -1){
					$status = -2;
				}
			}

			//------设置响应数据------
			$this -> setJson('type', $type);
			$this -> setJson('status', $status);

			//------发送响应数据------
			$this -> response();
		}

		//获取单页回答列表
		public function getAnswerPage(){
			//------设置请求方式，仅POST------
			$this -> request('post');
			//------设置响应数据格式------
			$this -> format('json');

			//------接收请求数据------
			$aid = $_POST['aid'];
			$page = $_POST['page'];

			//------初始化响应数据------
			$type = 'get_answer_page';
			$status = 0;
			$content = "";

			//------业务处理------
			$webConfig = new WebConfigModel();
			$answer = new AnswerModel($aid);

			//------设置渲染数据------
			$this -> set('webDName', $webConfig -> getDName());//设置域名
			$this -> set('webRoot', $webConfig -> getRoot());//设置根目录
			$this -> set('answer-list', $answer -> getAnswerList(($page-1)*5, 5));//回答列表
			
			//------获取渲染后的页面------
			$content = $this -> getXView('answer-page.html');

			//------设置响应数据------
			$this -> setJson('type', $type);
			$this -> setJson('status', $status);
			$this -> setJson('content', $content);

			//------响应数据------
			$this -> response();
		}

		//更新浏览量
		public function updatePV(){
			//------设置请求方式，仅POST------
			$this -> request('post');
			//------设置响应数据格式------
			$this -> format('json');

			//------接收请求数据------
			$aid = $_POST['aid'];

			//------业务处理------
			$article = new ArticleModel();

			$article -> updatePV($aid);
		}
	}
?>