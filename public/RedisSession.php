<?php

require_once('Redis.php');

/**
 *Redis 存储sessoin数据类
 * */
class RedisSession{
	
	private $_rdb;

	private $_session_time;

	public function __construct(){
		
		$this->_rdb = RedisDb::getInstance()->getRedis();
		$this->_session_time = get_cfg_var('session.gc_maxlifetime');
	
	}


	public function open($save_path,$session_name){

		return true;
		
	}

	public function close(){
		
		//PHP Warning:  session_write_close(): Failed to write session data using user defined save handler. (session.save_path
		#session_write_close();
		return true;
	}

	public function read($session_id){
	
		$result = $this->_rdb->get($session_id);

		if($result){

			return $result;

		}else{

			return '';
		}

	}

	public function write($session_id,$session_data){

		if($this->_rdb->set($session_id,$session_data)){
			$this->_rdb->expire($session_id,$this->_session_time);
			return true;
		}
		return false;
	
	}

	public function destroy($session_id){

		if($this->_rdb->del($session_id)){
			return true;
		}

		return false;
			
	}

	public function gc($maxlifetime){

		return true;
	}

	public function sessionStart(){

		session_set_save_handler(
			array($this,'open'),
			array($this,'close'),
			array($this,'read'),
			array($this,'write'),
			array($this,'destroy'),
			array($this,'gc')
		);
		register_shutdown_function('session_write_close');
		session_start();
	}

}
/*
$obj = new RedisSession();
$obj->sessionStart();
$_SESSION['user'] = 'wssorlwwwssd';
$_SESSION['password'] = 'ss12swws3456';
*/
