<?php
	/* 主页控制器 */
	class IndexController extends Controller{

		//主页
		public function index(){
			//------业务处理------
			$webConfig = new WebConfigModel();//网站配置
			$article = new ArticleModel();
			$answer = new AnswerModel();
			$person = new PersonModel();
			$logout = 'block';//注销状态
			$logined = 'none';//登录状态

			//当前存在登录用户
			if(!empty($_SESSION['logined_user'])){
				//切换登录状态
				$logout = 'none';
				$logined = 'block';
			}

			//------设置渲染数据------
			$this -> set('online', '666');
			$this -> set('webDName', $webConfig -> getDName());//设置域名
			$this -> set('webRoot', $webConfig -> getRoot());//设置根目录
			$this -> set('webIntro', $webConfig -> getIntro());//设置介绍标语
			$this -> set('webCopyright', $webConfig -> getCopyright());//设置版权信息
			$this -> set('logout', $logout);//注销状态标志
			$this -> set('logined', $logined);//登录状态标志
			$this -> set('index-target', '');//设置首页跳转方式
			$this -> set('newestAskList', $article -> getNewestAskList(0, 12));//最新问题列表
			$this -> set('newestAnswerList', $answer -> getNewestAnswerList(0, 12));//最新回答列表
			$this -> set('good-list', $person -> getGoodList());//达人榜
			$this -> set('rand-ask-list', $article -> getRandAsk());//随机问答
			$this -> set('hot-ask-list', $answer -> getHotAsk(0, 6));//热题推荐

			//------页面渲染------
			$this -> display('main.html');
		}

		//更新在线生命周期并获取在线人数
		public function getOnlines(){

			//------设置请求方式------
			$this -> request('post');

			//------初始化响应数据------
			$status = 0;
			$onlines = 0;

			//------业务处理------
			$cookie = new Cookie();
			$auth = new AuthModel();
			$cid = $cookie -> getVal('cid');

			if($cid === null){
				//---客户端身份认证不存在---
				//生成客户端身份令牌
				$cid = md5(time().rand(0, 1000));
				//存储客户端身份令牌
				$cookie -> setVal('cid', $cid);
				//写入客户端身份令牌
				$auth -> insertOnlineLife($cid);
			}else{
				//---客户端身份认证已存在---
				//更新客户端身份令牌
				$auth -> updateOnlineLife($cid);
			}

			//获取在线人数
			$onlines = $auth -> getOnlines();

			if($onlines === false) $status = -1; else $onlines = $onlines['onlines'];

			//------设置响应数据------
			$this -> setJson('status', $status);
			$this -> setJson('onlines', $onlines);

			//------响应数据------
			$this -> response();
		}

		//获取随机问答
		public function getRandAsk(){

			//------设置请求方式------
			$this -> request('post');

			//------初始化响应数据------
			$status = 0;
			$content = 0;

			//------业务处理------
			$article = new ArticleModel();

			$this -> set('rand-ask-list', $article -> getRandAsk());
			$content = $this -> getXView('rand-ask-list.html');

			//------设置响应数据------
			$this -> setJson('status', $status);
			$this -> setJson('content', $content);

			//------响应数据------
			$this -> response();
		}

		//我要提问
		public function wantAsk(){

			//------设置请求方式------
			$this -> request('post');

			//------初始化响应数据------
			$status = 0;
			$content = 0;

			//------业务处理------
			if(empty($_SESSION['logined_user'])){
				$status = -1;
			}

			//------设置响应数据------
			$this -> setJson('status', $status);
			$this -> setJson('content', $content);

			//------响应数据------
			$this -> response();
		}

		//搜索页
		public function search(){
			//------接收请求数据------
			$keyword = $_REQUEST['keyword'];

			//------业务处理------
			$webConfig = new WebConfigModel();//网站配置
			$article = new ArticleModel();
			$answer = new AnswerModel();
			$person = new PersonModel();
			$logout = 'block';//注销状态
			$logined = 'none';//登录状态

			//当前存在登录用户
			if(!empty($_SESSION['logined_user'])){
				//切换登录状态
				$logout = 'none';
				$logined = 'block';
			}

			//------设置渲染数据------
			$this -> set('webDName', $webConfig -> getDName());//设置域名
			$this -> set('webRoot', $webConfig -> getRoot());//设置根目录
			$this -> set('webIntro', $webConfig -> getIntro());//设置介绍标语
			$this -> set('webCopyright', $webConfig -> getCopyright());//设置版权信息
			$this -> set('logout', $logout);//注销状态标志
			$this -> set('logined', $logined);//登录状态标志
			$this -> set('index-target', '');//设置首页跳转方式
			$this -> set('keyword', $keyword);//搜索关键字
			$this -> set('s-result', $article -> getSearch($keyword, 0, 12));//搜索结果
			$this -> set('counts', $article -> getSearchPages($keyword));//搜索结果记录数

			//------页面渲染------
			$this -> display('alist.html');
		}

		//Ajax 搜索
		public function ajaxSearch(){
			//------设置请求方式------
			$this -> request('post');

			//------接收请求数据------
			$keyword = $_POST['keyword'];
			$page = $_POST['page'];

			//------初始化响应数据------
			$status = 0;
			$content = 0;

			//------业务处理------
			$article = new ArticleModel();

			//------设置渲染数据------
			$this -> set('s-result', $article -> getSearch($keyword, ($page-1)*12, 12));//搜索结果

			//------页面渲染------
			$content = $this -> getXView('slist.html');

			//------设置响应数据------
			$this -> setJson('status', $status);
			$this -> setJson('content', $content);

			//------响应数据------
			$this -> response();
		}
	}
?>