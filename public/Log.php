<?php

/**
 *日志类
 * */
class Log{

	const MESSAGE_TYPE = 3;//日志写入方式：写入到日志文件
	const DIR = __DIR__;   //文件运行的当前目录
	const LOGPATH = '/home/homework/log/'; //项目日志文件存放路径

	public function __construct()
	{

	}

	/**
	 *获取log相关的部分信息
	 * */
	public static function getLogInfo()  
	{  
		$debugInfo = debug_backtrace();  
		$errFile   = $debugInfo[0]["file"];  
		$errLine   = $debugInfo[0]["line"];  
		$dateTime  = date("[Y-m-d H:i:s]");  

		$logStr    = $dateTime."[".$errFile.":".$errLine."] ";  

		return $logStr;

	}


	/**
	 * 打印日志信息到access文件
	 * @$errMsg 日志信息
	 * */
	public static function addNotice($errMsg = '')
	{
		$logInfo = self::getLogInfo();
		$logInfo = $logInfo." ".$errMsg."\r\n";
		$logFile = self::getAccessLogFile();
		
		error_log($logInfo, self::MESSAGE_TYPE, $logFile);  
	}

	/**
	 * 打印日志信息到error文件
	 * @$errMsg 日志信息
	 * */
	public static function addWarning($errMsg = '')
	{	
		$logInfo = self::getLogInfo();
		$logInfo = $logInfo." ".$errMsg."\r\n";
		$logFile = self::getErrorLogFile();

		error_log($logInfo, self::MESSAGE_TYPE, $logFile);  

	}

	/**
	 *获取access日志文件路径
	 * */
	public static function getAccessLogFile()
	{
		$appPath = explode('/',self::DIR);			
		$appName = $appPath[4];
		$accessLogFile = self::LOGPATH.$appName.'/'.$appName."-"."access.log";
		return is_file($accessLogFile) ? $accessLogFile : false;
	}

	/**
	 *获取error日志文件路径
	 * */
	public static function getErrorLogFile()
	{
		$appPath = explode('/',self::DIR);			
		$appName = $appPath[4];
		$errorLogFile = self::LOGPATH.$appName.'/'.$appName."-"."error.log";
		return is_file($errorLogFile) ? $errorLogFile : false;
	}
}

//Log::addWarning("-------------ghl-----------".var_export($,true));
//var_export — 输出或返回一个变量的字符串表示
//$op = '11111111111';
//Log::addNotice("-------------ghl-----------".var_export($op,true));
