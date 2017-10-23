<?php
	/* 提问模型 */
	class AskModel{

		private $status = 0;
		private $user = "";//用户名
		private $title = "";//标题
		private $desc = "";//描述
		private $pubtime = "";//发布时间
		private $pv = "";//浏览量
		private $nickname = "";//昵称
		private $avatar = "";//头像
		private $asklist = null;//问题列表

		//初始化
		public function __construct($aid = ''){
			if(empty($aid)){ return; }

			//参数过滤[防SQL注入]
			$aid = addslashes($aid);

			//创建数据库对象
			$pdo = new MiniPDO();

			//查询问题信息
			$sql1 = "SELECT * FROM user_ask
					 WHERE uq_id=:aid";

			$rs1 = $pdo -> query($sql1,array(
				'aid' => $aid
			));

			if(empty($rs1)){
				//关闭数据库连接
				$pdo -> close();

				$this -> status = -1;

				return;
			}

			//查询用户信息
			$sql2 = "SELECT ui_nickname,ui_avatar FROM user_info
					 WHERE ua_user=:user";

			$rs2 = $pdo -> query($sql2,array(
				'user' => $rs1[0]['ua_user']
			));

			//关闭数据库连接
			$pdo -> close();

			//创建文章对象
			$article = new ArticleModel();
			//获取TA的提问
			$taRs = $article -> getAskList($rs1[0]['ua_user'], 0, 12);

			$this -> user = $rs1[0]['ua_user'];
			$this -> title = $rs1[0]['uq_title'];
			$this -> desc = $rs1[0]['uq_desc'];
			$this -> pv = empty($rs1[0]['uq_pv']) ? '<span>0</span>' : $rs1[0]['uq_pv'];
			$this -> pubtime = $rs1[0]['uq_publish_time'];
			$this -> nickname = $rs2[0]['ui_nickname'];
			$this -> avatar = $rs2[0]['ui_avatar'];
			$this -> asklist = $taRs;
		}

		//获取状态
		public function getStatus(){
			if(!empty($this -> status)){ return $this -> status; }
		}

		//获取标题
		public function getTitle(){
			if(!empty($this -> title)){ return $this -> title; }
		}

		//获取描述
		public function getDesc(){
			if(!empty($this -> desc)){ return $this -> desc; }
		}

		//获取发布时间
		public function getPubTime(){
			if(!empty($this -> pubtime)){ return date("Y-m-d H:i:s", $this -> pubtime); }
		}

		//获取浏览量
		public function getPV(){
			if(!empty($this -> pv)){ return $this -> pv; }
		}

		//获取用户名
		public function getNickname(){
			if(!empty($this -> nickname)){ return $this -> nickname; }
		}

		//获取用户头像
		public function getAvatar(){
			if(!empty($this -> avatar)){ return $this -> avatar; }
		}

		//获取用户提问列表
		public function getAskList(){
			if(!empty($this -> asklist)){ return $this -> asklist; }
		}
	}
?>