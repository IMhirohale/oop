<?php

class Log{


	const MESSAGE_TYPE = 3;//日志写入方式：写入到日志文件

	/**
	 *日志级别
	 * */
	const NOTICE  = 1; //提示性错误
	const WARNING = 2; //警告性错误
	const FATAL   = 3; //致命性错误

	static $ERROR = [
		self::NOTICE  => 'PHP Notice',
		self::WARNING => 'PHP Warning',
		self::FATAL   => 'PHP Fatal',
	];

	/**
	 *日志文件路径
	 * */
	private $_logFile;
	private $_logInfo;

	public function __construct(){

	}

	public function getLogInfo()  
	{  
		$debugInfo = debug_backtrace();  
		$errFile = $debugInfo[0]["file"];  
		$errLine = $debugInfo[0]["line"];  

		$dateTime = date("[Y-m-d H:i:s]");  

		$logStr = $dateTime."[".$errFile.":".$errLine."] ";  

		return $logStr;

	}


	public function addNotice($errMsg = '', $logFile = '')
	{
		$logInfo = $this->getLogInfo();
		$logInfo = $logInfo." ".self::$ERROR[1]." ".$errMsg."\r\n";
		
		error_log($logInfo, self::MESSAGE_TYPE, $logFile);  
	}

	public function addWarning($errMsg = '', $logFile = '')
	{	
		$logInfo = $this->getLogInfo();
		$logInfo = $logInfo." ".self::$ERROR[2]." ".$errMsg."\r\n";

		error_log($logInfo, self::MESSAGE_TYPE, $logFile);  

	}

	public function addFatal($errMsg = '', $logFile = '')
	{	
		$logInfo = $this->getLogInfo();
		$logInfo = $logInfo." ".self::$ERROR[3]." ".$errMsg."\r\n";

		error_log($logInfo, self::MESSAGE_TYPE, $logFile);  
	}
}


$obj = new Log();
$obj->addNotice("hello------------------world","/home/homework/log/php/php-errors.log");

