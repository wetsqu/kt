<?php

namespace KT;

class KTPush
{
	private $host = '';
	private $app_env = '';
	
	/*function __construct($host, $app_env)
	{
		$this->host = $host;
	}*/
	
	function __construct($host, $app_env = '')
	{
		$this->host = $host;
		$this->app_env = $app_env;
	}
	
	public function send($to, $msg, $par)
	{
		$ktcurl = new KTCurl();
		$ktcurl->rawPost('http://' . $this->host . '/pub' . '?id=' . $to . '_' . $this->app_env, addslashes(json_encode(array('msg' => $msg, 'par' => $par ))));
	}
}
	
?>