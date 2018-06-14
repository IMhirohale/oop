<?php

require_once("Db.php");

class Session{


	/**
	 *PDO数据库连接实例
	 */
	private $_db;

	/**
	 *session 有效时间
	 */
	private $_session_time;

	/**
	 *构造函数
	 **/
	public function __construct(){

		$this->_db = Db::getInstance()->getPDO();
		$this->_session_time = get_cfg_var('session.gc_maxlifetime');
		
	}

	/**
	 *session-open()函数
	 */
	public function sessionOpen(){
        
		if($this->_db == null){
			$this->_db = Db::getInstance()->getPDO();
		}
		return !empty($this->_db) ? true : false;
		
	} 

	/**
	 *session-close()函数
	 */
	public function sessionClose(){
		
		if(!is_null($this->_db)){

			$this->_db = null;

			@session_write_close();

			return true;
		}

		return false;

	}

	/**
	 *session-read()函数
	 */
	public function sessionRead($sessionId = ''){

		try {

			$nowTime = time();

			$sql = 'select session_info as sessionInfo from user_session where session_key = ? and expire_time > ?';

		    $pre = $this->_db->prepare($sql);	

			$pre->execute(array($sessionId, $nowTime));

			$res = $pre->fetch(PDO::FETCH_ASSOC);

			if($res === false ){
				return '';
			}

			return $res['sessionInfo'];
		
		}catch (PDOException $e){

			echo 'Error: ' . $e->getMessage();
			return false;
		}

		
	}

	/**
	 *session-write()函数
	 */
	public function sessionWrite($sessionId, $sessionData){
		
		try {
			$nowTime = time();
			$expireTime = $nowTime + $this->_session_time;
			$sql = 'insert into user_session(session_key,session_info,expire_time)values(?,?,?)';
			$pre = $this->_db->prepare($sql);
			$res = $pre->execute(array($sessionId, $sessionData, $expireTime));

			if($res !== false){
			
				return true;
			}
			
		
		}catch (PDOException $e){

			echo 'Error: ' . $e->getMessage();
			return false;
			
		}
	}

	/**
	 *session-destroy()函数
	 */
	public function sessionDestroy($sessionId = ''){
		
		try {

			$sql = 'delete from user_session where session_key = ?';
			$pre = $this->_db->prepare($sql);
			$res = $pre->execute(array($sessionId));
			if($res !== false){
				return true;
			}
		}catch (PDOException $e){

			 echo 'Error: ' . $e->getMessage();		
			 return false;
			
		}
	}

	/**
	 *Perform session data garbage collection
	 *执行会话数据垃圾收集
	 */
	public function sessionGc(){
	
		try {

			$nowTime = time();
			$sql = 'delete from user_session where expire_time < ?';	
			$pre = $this->_db->prepare($sql);
			$res = $pre->execute(array($nowTime));

			if($res !== false){
				return true;
			}

		}catch (PDOException $e){

			echo 'Error: ' . $e->getMessage();

			return false;
		}


	}

	/**
	 *session id 设置函数
	 */
	public  function sessionId(){

		if(filter_input(INPUT_GET, session_name()) == '' && filter_input(INPUT_COOKIE, session_name()) == ''){

			try {

				$sql = 'select uuid() as sessionId';
				$pre = $this->_db->prepare($sql);
				$pre->execute();
				$res = $pre->fetch(PDO::FETCH_ASSOC);
				$sessionId = str_replace('-' ,'' ,$res['sessionId']);
				session_id($sessionId);

				return true;

			}catch (PDOException $e){
				echo 'Error: ' . $e->getMessage();

				return false;

			}
		}
	}


	/**
	 *session启动
	 **/
	public  function sessionStart(){

		$handler = new Session();
		session_set_save_handler(
			array($handler, 'sessionOpen'),
			array($handler, 'sessionClose'),
			array($handler, 'sessionRead'),
			array($handler, 'sessionWrite'),
			array($handler, 'sessionDestroy'),
			array($handler, 'sessionGc')
		);
		register_shutdown_function('session_write_close');
		$handler->sessionId();
		session_start();
	}

}

/*
$obj = new Session();
$obj->sessionStart();
$_SESSION['user'] = 'worlssd';  
$_SESSION['password'] = '12ss3456';  
*/
