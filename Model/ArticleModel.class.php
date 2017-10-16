<?php
	/* 文章模型 */
	class ArticleModel{

		//初始化
		public function __construct(){
		}

		//根据用户名获取提问列表
		public function getAskList($user = '', $index = 0, $length = 0){
			if(empty($user)){
				return false;
			}

			//创建数据库对象
			$pdo = new MiniPDO();

			//查询部分用户提问列表
			$sql = "SELECT user_ask.uq_id as 'askId', user_answer.uq_id as 'answerId', user_ask.uq_title as '			title', count(*) as 'answers'
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

			return $rs;
		}

		//写入发布提问
		public function setAsk($user = '', $title = '', $description = ''){
			if(empty($user) || empty($title)){
				return;
			}

			//创建数据库对象
			$pdo = new MiniPDO();

			//插入提问记录
			$pubTime = time();
			$sql = "INSERT INTO user_ask (ua_user, uq_title, uq_desc,uq_publish_time)
					VALUES ('{$user}', '{$title}', '{$description}', '{$pubTime}')";

			//执行
			$rs = $pdo -> execute($sql);

			return $rs;
		}

		//根据用户名获取回答列表
		public function getAnswerList($user = '', $index = 0, $length = 0){
			if(empty($user)){
				return false;
			}

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

			return $rs;
		}
	}
?>