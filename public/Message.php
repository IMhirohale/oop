<?php

class Msg{

	/**
	 *json消息
	 *@param $Msg 提示信息
	 * */
	public static function ajax($msg = ''){
	
		$jsonMsg = json_encode($msg);

		exit($jsonMsg);
		
	}


	/**
	 *js消息
	 *@param $msg 提示信息
	 *@param $url 网址 
	 * */
	public static function js($msg = '', $url = false){

		header('Content-Type: text/html; charset=utf-8');
		echo '<script type="text/javascript">';

		if(!empty($msg)){

			echo "alert('", (is_array($msg) ? implode('\n', $msg) : $msg), "');";

		}

		if($url !== false){

			echo "self.location='{$url}'";

		}else if(empty($_SERVER['HTTP_REFERER'])){

			echo "history.back(-1)";

		}else{
			
			echo "self.location='{$_SERVER['HTTP_REFERER']}';";
		
		}
		
		exit('</script>');
	
	}


}
