<?php

class RedisDb{

	private $_rdb;

	private static $_instance;

	const REDIS_DNS  = 'localhost';
	const REDIS_PORT = '6379';

	private function __construct(){
			
		$this->_rdb = new Redis(); 
		$this->_rdb->connect(self::REDIS_DNS, self::REDIS_PORT);
	}

	private function __close(){
	
	}

	public static function getInstance(){
		
		if(!(self::$_instance instanceof RedisDb)){
			self::$_instance = new RedisDb();
		}
		return self::$_instance;
	}

	public function getRedis(){
		return $this->_rdb;
	}

}
/*
$redis = RedisDb::getInstance()->getRedis();
$redis->lpush("tutorial-list", "Redis");
$redis->lpush("tutorial-list", "Mongodb");
$redis->lpush("tutorial-list", "Mysql");
// 获取存储的数据并输出
$arList = $redis->lrange("tutorial-list", 0 ,5);
print_r($arList);
*/
