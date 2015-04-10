<?php

namespace KT;

class PushMessage
{
	private $host = '';
	
	function __construct($host)
	{
		$this->host = $host;
	}
	
	public function send($to, $msg, $par)
	{
		global $config;
		
		$message = array(
			'msg' => $msg,
			'par' => $par
		);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://' . $this->host . '/pub' . '?id=' . $to);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, addslashes(json_encode($message)));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($ch);
		$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		if ($response_code == 200)
			return 0;
	}
}
	
?>