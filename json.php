<?php
	$arr = array(

		/* ҳ������ */
		"view" => [
			"charset" => "UTF-8"//ҳ���ַ�����
		],

		/* Ŀ¼���� */
		"path" => [
			"view_path" => "View/"//��ͼģ��·��
		],

		/* ϵͳ���� */
		"system" =>[
			"timezone" => "PRC"//ʱ������,PRC(�л����񹲺͹�)
		],

		/* MySQL ���ݿ����� */
		"mysql" => [
			"host"     => "127.0.0.1",//���ݿ�������ַ
			"port"	   => "3306",//�˿ں�
			"user"     => "root",//�û���
			"password" => "68852911",//����
			"db"       => "zero"//���ݿ�
		],

		/* Session ���� */
		"session" => [
			"display"	  => "on",	//�Զ��� Session ����
			"save_path"	  => "Session",	//Session �����ַ
			"maxlifetime" => 1440,		//����������� s
			"probability" => 1,		//���ո��ʷ���
			"divisor"	  => 1000	//���ո��ʷ�ĸ, 0 ΪĬ��
		],

		/* Redis ���� */
		"redis" => [
			"display"	=> "off",		//����
			"host"		=> "127.0.0.1",	// Redis ��������ַ
		]
	);

	$str = json_encode($arr);

	$config = json_decode($str);

	print_r($arr);
	echo "<br/><br/>";
	print_r($str);
	echo "<br/><br/>";
	print_r($config);
	echo "<br/><br/>";
	$t = 'view';
	print_r($config -> $t -> charset);

	$str = 'a.c.b';
	print_r(explode('.', $str));

	print_r(property_exists($config -> view, 'charset'));

	echo '<br/><br/>';
	$keyStr = 'view.charset';
	if(empty($keyStr)){
		return -1;
	}

	//�ֽ����
	$keys = explode('.', $keyStr);

	$result = $config;
	//ѭ��ȡֵ
	for($i=0;$i<count($keys);$i++){
		//�ж϶��������Ƿ����
		if(!property_exists($result, $keys[$i])){
			//���������������
			print_r(-1);
			return;
		}

		//���ڸ�ֵ
		$result = $result -> $keys[$i];
	}

	//���ؽ��
	print_r($result);
?>