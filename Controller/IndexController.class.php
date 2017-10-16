<?php
	/* 主页控制器 */
	class IndexController extends Controller{

		//主页
		public function index(){
			//------业务处理------
			$webConfig = new WebConfigModel();//网站配置
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

			//------页面渲染------
			$this -> display('main.html');
		}
	}
?>