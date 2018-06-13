<?php
class Db{

	/**
	 *PDO数据库连接实例
	 *$var resource
	 */
	private $_db;


	/**
	 *类静态属性
	 */
    private static $_instance;


	/**
	 *数据库用户名和密码
	 */
	const DB_DNS  = 'mysql:host=localhost;dbname=helloworld';
	const DB_USER = 'root';
	const DB_PWD  = 'xlghl123.com';

	/**
	 *构造函数
	 */
    private function __construct(){

		$this->_db = new PDO(self::DB_DNS ,self::DB_USER ,self::DB_PWD);
    }

	/**
	 *克隆函数
	 */
    private function __clone(){
        
    }

	/**
	 *单例模式
	 */
    public static function getInstance(){
        if(!(self::$_instance instanceof Db)){
            self::$_instance = new Db();
        }
        return self::$_instance ;
    }

	/**
	 *获取PDO数据库连接实例
	 */
	public function getPDO(){

		return $this->_db;

	}
}

/*
$objDb = Db::getInstance();
$pre = $objDb->getPDO()->prepare("select * from hello_user where id = 10") or die(print_r($objDb->getPDO()->errorInfo(), true));
$res = $pre->execute();
$res = $pre->fetch(PDO::FETCH_ASSOC);
var_dump($res);
*/
