<?php

require_once("Db.php");
require_once("Message.php");
require_once("MysqlSession.php");
require_once("RedisSession.php");


class User{	

	/*
	 * 用户登录
	 * **/
	public function userLogin($userInfo){

		if(empty($userInfo['userPhone']) || empty($userInfo['userPasswd'])){

			Msg::js("手机号和密码不能为空");

		}

		if(!$this->checkPhone($userInfo['userPasswd'])){

			Msg::js("请输入正确的手机号");

		}

		try{

			$objDb = Db::getInstance()->getPDO();
			$userPhone = $userInfo['userPhone'];
			$query     = "select phone,salt,password from hello_user where phone=$userPhone;";
			$pre       = $objDb->prepare($query) or die(print_r($objDb->errorInfo(), true));
			$pre->execute();
			$res = $pre->fetch(PDO::FETCH_ASSOC);

			$userPasswd = $this->saltPasswd($userInfo['userPasswd'] ,$res['salt']);

			if($userPasswd === $res['password']){

			//	$ssmObj = new MysqlSession();
			//	$ssmObj->sessionStart();		
				
				$ssrObj = new RedisSession();
				$ssrObj->sessionStart();		
				//记录用户登录状态
				$_SESSION['userInfo'] = [
					'userPhone'   => $userInfo['userPhone'],
					'userPasswd'   => $userPasswd,
					'isLogin' => 1,
				];


			}

			$objDb = null;
			Msg::js("登录成功！", "http://test.oop.com/success.html");

		}catch (PDOException $e){
			echo 'Error: ' . $e->getMessage();
			Msg::js("登录失败！", "http://test.oop.com/404.html");
		}

	}

	/*
	 * 用户注册
	 * **/
	public function userRegister($userInfo){

		if(empty($userInfo['userName'] || !$this->checkName($userInfo['userName']))){

			Msg::js("用户名不合法或为空");
		}

		if(empty($userInfo['userPhone']) || !$this->checkPhone($userInfo['userPhone'])){

			Msg::js("手机号不能为空或格式不正确");

		}

		if(empty($userInfo['userPasswd'])){

			Msg::js("密码不能为空");

		}

		if(empty($userInfo['userPasswdConfirm'])){

			Msg::js("确认密码不能为空");
		}

		if($userInfo['userPasswd'] !== $userInfo['userPasswdConfirm']){

			Msg::js("密码与确认密码不相等，请检查并重新输入");
		}

		$saltNumber = $this->saltNumber();
		$userInfo['userPasswd'] = $this->saltPasswd($userInfo['userPasswd'], $saltNumber);
		$userName   = $userInfo['userName'];
		$userPhone  = $userInfo['userPhone'];
		$userPasswd = $userInfo['userPasswd'];
		$createTime = $userInfo['createTime'];

		try{

			$objDb = Db::getInstance()->getPDO();
			$query = "insert into hello_user(name,phone,salt,password,create_time)values('$userName','$userPhone','$saltNumber','$userPasswd','$createTime');";
			$objDb->exec($query) or die(print_r($objDb->errorInfo(), true));
			$objDb = null;
			Msg::js("注册成功！赶快去登录吧！","http://test.oop.com/login.html");

		}catch (PDOException $e){

			echo 'Error: ' . $e->getMessage(); 		
			Msg::js("注册失败！","http://test.oop.com/404.html");

		}


	}

	/*
	 * 校验用户手机号
	 * **/
	public function checkPhone($str){

		if(11 != strlen($str)) return false;
		return preg_match("/13[0-9]{9}|15[0-9]{9}|18[0-9]{9}|147[0-9]{8}|17[0-9]{9}|144[0-9]{8}/",$str);	

	}

	/*
	 * 检测用户姓名
	 * **/
	public function checkName($str){

		return preg_match("/^[\s0-9a-zA-Z\x80-\xff]+$/", $str);

	}

	/*
	 * 用户密码加密
	 * **/
	public function saltPasswd($passwd, $salt){

		return md5(md5($passwd) . ', ' . $salt);	

	}

	/*
	 * 盐密机制
	 * **/
	public function saltNumber(){

		return rand(100000, 999999);

	}

}

$objUser = new User();

if(preg_match('/login/',$_SERVER['HTTP_REFERER'],$res)){

	if('POST' == $_SERVER['REQUEST_METHOD']){

		$userInfo = [
			'userPhone'  => $_POST['phone'],
			'userPasswd' => $_POST['passwd'],
		];

		$objUser->userLogin($userInfo);
	}
}


if(preg_match('/register/',$_SERVER['HTTP_REFERER'],$res)){

	if('POST' == $_SERVER['REQUEST_METHOD']){

		$userInfo = [
			'userName'          => $_POST['name'],
			'userPhone'         => $_POST['phone'],
			'userPasswd'        => $_POST['passwd'],
			'userPasswdConfirm' => $_POST['passwdconfirm'],
			'createTime'        => time(),
		];

		$objUser->userRegister($userInfo);
	}

}

