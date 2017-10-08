<?php
	/* 网站配置模型 */
	class WebConfigModel{

		private $webConfig = [];

		//初始化
		public function __construct(){
			$pdo = new MiniPDO();

			//查询网站配置
			$sql = "select * from sys_info";
			$arr = $pdo -> query($sql);

			//关闭数据库连接
			$pdo -> close();

			//存储配置
			$this -> webConfig['dname'] = $arr[0]['si_dname'];
			$this -> webConfig['root'] = $arr[0]['si_root'];
			$this -> webConfig['intro'] = $arr[0]['si_intro'];
			$this -> webConfig['copyright'] = $arr[0]['si_copyright'];
		}

		//获取网站域名
		public function getDName(){
			return $this -> webConfig['dname'];
		}

		//获取网站根目录
		public function getRoot(){
			return $this -> webConfig['root'];
		}

		//获取网站标语
		public function getIntro(){
			return $this -> webConfig['intro'];
		}

		//获取网站版权信息
		public function getCopyright(){
			return $this -> webConfig['copyright'];
		}
	}
?>