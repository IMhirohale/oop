<?php

require_once("Redis.php");

/**
 *
 *Redis 接口访问封装类
 * */

class RedisInit {	

	//redis 实例化对象
	private  $redis;
	
	public function __construct() 
	{
		$this->redis = RedisDb::getInstance()->getRedis();

		return $this->redis;
	}		


	/**
	 *将字符串值 value 关联到 key 。
	 * */
	public function set($key,$value)
	{
		return $this->redis->set($key,$value) ? true : false;
	}

	/**
	 *返回 key 所关联的字符串值
	 * */
	public function get($key)
	{
		return $this->exists($key) ? $this->redis->get($key) : false;
	}

	/**
	 *检查给定 key 是否存在
	 * */
	public function exists($key)
	{
		return $this->redis->exists($key);	
	}

	/**
	 *删除给定的一个key
	 * */
	public function del($key)
	{
		return $this->redis->del($key);
	}

	/**
	 *以秒为单位，返回给定 key 的剩余生存时间(TTL, time to live)。
	 * */
	public function ttl($key)
	{
		return $this->redis->ttl($key);
	}


	/**
	 *清空当前数据库中的所有 key
	 * */
	public function flushdb()
	{
		
		return $this->redis->flushdb();
	}

	/**
	 *清空整个 Redis 服务器的数据(删除所有数据库的所有 key )
	 * */
	public function flushall()
	{
		return $this->redis->flushall();
	}

	/**
	 *获取所有的键
	 *
	 * */
	public function keys()
	{
		return $this->redis->keys('*');
	}

	/**
	 *设置过期时间的key
	 *setex -- set expire
	 * */
	public function setex($key,$seconds,$value)
	{
		return $this->redis->setex($key,$seconds,$value);
	}


	/**
	 *SETNX 是『SET if Not eXists』(如果不存在，则 SET)的简写
	 * */
	public function setnx($key,$value)
	{
		return $this->redis->setnx($key,$value);
	}


	/**
	 *同时设置一个或多个 key-value 对。
	 *$keyValueArr = array('key0' => 'value0', 'key1' => 'value1')
	 * */
	public function mset($keyValueArr)
	{
		return $this->redis->mset($keyValueArr);
	}

	/**
	 *返回所有(一个或多个)给定 key 的值。
	 *$keys = array('key1', 'key2', 'key3') 
	 * */
	public function mget($keys)
	{
		return $this->redis->mget($keys);
	}
	
}

