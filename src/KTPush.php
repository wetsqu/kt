<?php

namespace KT;

class KTPush
{
	private $host = '';
	
	function __construct($host)
	{
		$this->host = $host;
	}	
	
	public function send($to, $msg, $par)
	{
		$ktcurl = new KTCurl();
		$ktcurl->rawPost('http://' . $this->host . '/pub' . '?id=' . $to, addslashes(json_encode(array('msg' => $msg, 'par' => $par ))));
	}
}
	
?>