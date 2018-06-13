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
	 *构造函数
	 */
    private function __construct(){

		$this->_db = new PDO('mysql:host=localhost;dbname=helloworld', 'root', 'xlghl123.com');
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
$pre = $objDb->getPDO()->prepare("select * from hello_user") or die(print_r($objDb->getPDO()->errorInfo(), true));
$pre->execute();
$res = $pre->fetch(PDO::FETCH_ASSOC);
var_dump($res);
*/
