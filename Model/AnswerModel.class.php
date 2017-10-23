<?php
	/* 回答模型 */
	class AnswerModel{

		private $aid = '';

		//初始化
		public function __construct($aid = ''){
			if(empty($aid)){ return; }

			//参数过滤[防SQL注入]
			$this -> aid = addslashes($aid);
		}

		//获取回答列表页数
		public function getAnswerPages(){
			//创建数据库对象
			$pdo = new MiniPDO();

			//查询回答信息
			$sql = "SELECT user_answer.uas_content as 'content'
					FROM user_answer, user_info
					WHERE user_answer.uq_id=:aid and user_answer.ua_user = user_info.ua_user";

			$rs = $pdo -> query($sql,array(
				'aid' => $this -> aid
			));

			//关闭数据库连接
			$pdo -> close();

			//处理结果集
			for($i=0;$i<count($rs);$i++){
				if(!is_file(APP_PATH.$rs[$i]['content'])){
					//文件不存在，移除数组元素
					unset($rs[$i]);
					//重新建立数字索引
					$rs = array_values($rs);
					//循环重置
					$i = -1;
				}
			}

			//返回结果
			return ceil(count($rs)/5);
		}

		//获取回答列表
		public function getAnswerList($index = 0, $length = 0){
			//创建数据库对象
			$pdo = new MiniPDO();

			//查询回答信息
			$sql = "SELECT user_answer.ua_user as 'user', user_answer.uas_content as 'content', 
					user_answer.uas_publish_time as 'pubtime', user_info.ui_nickname as 'nickname', 
					user_info.ui_avatar as 'avatar'
					FROM user_answer, user_info
					WHERE user_answer.uq_id=:aid and user_answer.ua_user = user_info.ua_user
					ORDER BY user_answer.uas_publish_time desc
					LIMIT {$index},{$length}";

			$rs = $pdo -> query($sql,array(
				'aid' => $this -> aid
			));

			//关闭数据库连接
			$pdo -> close();

			//处理结果集
			for($i=0;$i<count($rs);$i++){
				//转换时间格式
				$rs[$i]['pubtime'] = is_numeric($rs[$i]['pubtime'])
									 ?date("Y-m-d H:i:s", $rs[$i]['pubtime'])
									 :$rs[$i]['pubtime'];

				if(!is_file(APP_PATH.$rs[$i]['content'])){
					//文件不存在，移除数组元素
					unset($rs[$i]);
					//重新建立数字索引
					$rs = array_values($rs);
					//循环重置
					$i = -1;
				}else{
					//根据路径读取文章内容
					$rs[$i]['content'] = file_get_contents(APP_PATH.$rs[$i]['content']);
				}
			}

			//返回结果
			return $rs;
		}

		//获取最新回答列表
		public function getNewestAnswerList($index = 0, $length = 0){
			//创建数据库对象
			$pdo = new MiniPDO();

			//查询回答信息
			$sql = "SELECT user_ask.uq_id as 'aid', user_ask.uq_title as 'title', count(*) as 'answers'
					FROM user_ask,user_answer
					WHERE user_ask.uq_id=user_answer.uq_id
					GROUP BY user_ask.uq_id
					ORDER BY uq_first_answer_time desc, uq_publish_time desc
					LIMIT {$index},{$length}";

			$rs = $pdo -> query($sql,array(
				'aid' => $this -> aid
			));

			//关闭数据库连接
			$pdo -> close();

			//返回结果
			return $rs;
		}

		//写入答案
		public function setAnswer($aid = '', $user = '', $content = ''){
			if(empty($aid) || empty($user)) return;

			//参数过滤[防SQL注入]
			$aid = addslashes($aid);
			$user = addslashes($user);

			$status = 0;

			//创建数据库对象
			$pdo = new MiniPDO();

			$time = time();
			$fname = date("YmdHis", $time).rand(10,99);
			$path = Config::get('path.answer')."/{$fname}.zero";
			//SQL
			$sql1 = "INSERT INTO user_answer (uq_id, ua_user, uas_content, uas_publish_time)
					VALUES ('{$aid}','{$user}','{$path}','{$time}')";
			$sql2 = "UPDATE user_ask
					SET uq_first_answer_time =
						CASE WHEN uq_first_answer_time = 0
						THEN {$time}
					    ELSE uq_first_answer_time
					    END
					WHERE uq_id={$aid}";

			//开启事务
			$pdo -> beginTransaction();

			//执行写入数据库
			$rs1 = $pdo -> execute($sql1);
			//更新首次回答时间
			$rs2 = $pdo ->execute($sql2);
			//写入答案文档
			if(file_put_contents(APP_PATH.$path, $content) === false){
				//文件写入失败，回滚
				$pdo -> rollBack();
				$status = -1;
			}else if($rs1 === false || $rs2 === false){
				//数据库写入失败，回滚
				$pdo -> rollBack();
				//删除文件
				if(is_file(APP_PATH.$path, $content)) unlink(APP_PATH.$path, $content);
				$status = -1;
			}else{
				//写入成功，提交
				$pdo -> commit();
			}

			//关闭数据库连接
			$pdo -> close();

			//返回结果
			return $status;
		}
	}


?>