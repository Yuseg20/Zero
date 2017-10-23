<?php
	/* 文章模型 */
	class ArticleModel{

		//初始化
		public function __construct(){
		}

		//根据用户名获取提问列表页数
		public function getAskPages($user = ''){
			if(empty($user)){
				return false;
			}

			//参数过滤[防SQL注入]
			$user = addslashes($user);

			//创建数据库对象
			$pdo = new MiniPDO();

			//查询部分用户提问列表页数
			$sql = "SELECT user_ask.uq_id as 'askId'
					FROM user_ask left join user_answer
					on user_ask.uq_id=user_answer.uq_id
					WHERE user_ask.ua_user=:user
					GROUP BY user_ask.uq_id
					ORDER BY uq_publish_time desc";

			//执行
			$rs = $pdo -> query($sql, array(
				'user' => $user
			));

			//关闭数据库连接
			$pdo -> close();

			return ceil(count($rs)/12);
		}

		//根据用户名获取提问列表
		public function getAskList($user = '', $index = 0, $length = 0){
			if(empty($user)){
				return false;
			}

			//参数过滤[防SQL注入]
			$user = addslashes($user);

			//创建数据库对象
			$pdo = new MiniPDO();

			//查询部分用户提问列表
			$sql = "SELECT user_ask.uq_id as 'askId', user_answer.uq_id as 'answerId', user_ask.uq_title as 'title', count(*) as 'answers'
					FROM user_ask left join user_answer
					on user_ask.uq_id=user_answer.uq_id
					WHERE user_ask.ua_user=:user
					GROUP BY user_ask.uq_id
					ORDER BY uq_publish_time desc
					LIMIT {$index},{$length}";
			//执行
			$rs = $pdo -> query($sql, array(
				'user' => $user
			));

			//关闭数据库连接
			$pdo -> close();

			// 0 记录数据匹配
			for($i=0;$i<count($rs);$i++){
				if(empty($rs[$i]['answerId'])){
					$rs[$i]['answers'] = 0;
				}
			}

			return $rs;
		}

		//获取最新提问列表
		public function getNewestAskList($index = 0, $length = 0){
			//创建数据库对象
			$pdo = new MiniPDO();

			//查询部分用户提问列表
			$sql = "SELECT user_ask.uq_id as 'askId', user_ask.uq_title as 'title'
					FROM user_ask,user_answer
					WHERE user_ask.uq_id NOT IN(
						SELECT user_answer.uq_id
						FROM user_answer
						GROUP BY uq_id)
					GROUP BY user_ask.uq_id
					ORDER BY uq_publish_time DESC
					LIMIT {$index},{$length}";
			//执行
			$rs = $pdo -> query($sql);

			//关闭数据库连接
			$pdo -> close();

			// 0 记录数据匹配
			for($i=0;$i<count($rs);$i++){
				if(empty($rs[$i]['answerId'])){
					$rs[$i]['answers'] = 0;
				}
			}

			return $rs;
		}

		//写入发布提问
		public function setAsk($user = '', $title = '', $description = ''){
			if(empty($user) || empty($title)){
				return;
			}

			//转义处理[防SQL注入]
			$title = addslashes($title);
			$description = addslashes($description);

			//创建数据库对象
			$pdo = new MiniPDO();

			//插入提问记录
			$pubTime = time();
			$sql = "INSERT INTO user_ask (ua_user, uq_title, uq_desc,uq_publish_time)
					VALUES ('{$user}', '{$title}', '{$description}', '{$pubTime}')";

			//执行
			$rs = $pdo -> execute($sql);

			//关闭数据库连接
			$pdo -> close();

			return $rs;
		}

		//根据用户名获取回答列表页数
		public function getAnswerPages($user = ''){
			if(empty($user)){
				return false;
			}

			//参数过滤[防SQL注入]
			$user = addslashes($user);

			//创建数据库对象
			$pdo = new MiniPDO();

			//查询部分用户提问列表
			$sql = "SELECT user_ask.uq_id as 'askId'
					FROM user_ask, user_answer
					WHERE user_answer.ua_user=:user and user_answer.uq_id=user_ask.uq_id
					GROUP BY user_answer.uq_id
					ORDER BY user_answer.uas_publish_time desc";
			//执行
			$rs = $pdo -> query($sql, array(
				'user' => $user
			));

			//关闭数据库连接
			$pdo -> close();

			return count($rs);
		}

		//根据用户名获取回答列表
		public function getAnswerList($user = '', $index = 0, $length = 0){
			if(empty($user)){
				return false;
			}

			//参数过滤[防SQL注入]
			$user = addslashes($user);

			//创建数据库对象
			$pdo = new MiniPDO();

			//查询部分用户提问列表
			$sql = "SELECT user_ask.uq_id as 'askId', user_ask.uq_title as 'title'
					FROM user_ask, user_answer
					WHERE user_answer.ua_user=:user and user_answer.uq_id=user_ask.uq_id
					GROUP BY user_answer.uq_id
					ORDER BY user_answer.uas_publish_time desc
					LIMIT {$index},{$length}";
			//执行
			$rs = $pdo -> query($sql, array(
				'user' => $user
			));

			//关闭数据库连接
			$pdo -> close();

			return $rs;
		}

		//更新浏览量
		public function updatePV($aid = ''){
			//创建数据库对象
			$pdo = new MiniPDO();

			$sql = "UPDATE user_ask SET uq_pv=uq_pv+1 WHERE uq_id={$aid}";

			//更新
			$pdo -> execute($sql);
		}
	}
?>