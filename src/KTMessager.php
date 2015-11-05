<?php

namespace KT;

class KTMessager
{
	private $host = '';
	private $service = '';
	private $password = '';
	private $version = '';
	
	private $response = null;
	private $error = null;
	
	function __construct($host, $service, $password, $version = '1.0.0')
	{
		$this->host = $host;
		$this->service = $service;
		$this->password = $password;
		$this->version = $version;
	}
	
	public function send($room, $message, $data)
	{
		$this->response = null;
		$this->error = null;
		
		if ($this->version == '1.0.0')
		{
			try
			{
				$ktcurl = new KTCurl();
				$request = $ktcurl->newRequest('post', $this->host . '/push', [
						'version' => $this->version,
						'service' => $this->service,
						'password' => $this->password,
						'room' => $room, 
						'message' => $message,
						'data' => json_encode($data)
					])
					->setOption(CURLOPT_SSL_VERIFYPEER, false)						
					->setOption(CURLOPT_FOLLOWLOCATION, true);
					
				$response = $request->send();
				
				if ($response->statusCode == 200)
				{
					$json = json_decode($response->body);
					
					if ($json->status == 1)
					{
						$this->response = $json->response;
						
						return true;
					}
					else
					{
						$this->error = new \stdClass();
						$this->error->code = $json->message_code;
						$this->error->message = $json->message;
						
						return false;
					}
				}
				else
				{
					$this->error = new \stdClass();
					$this->error->code = $response->statusCode;
					$this->error->message = 'Http error';
					
					return false;
				}
			}
			catch (\RuntimeException $e)
			{
				$this->error = new \stdClass();
				$this->error->code = 500;
				$this->error->message = 'Unknown host';
			}
		}
	}
	
	public function response()
	{
		return $this->response;
	}
	
	public function error()
	{
		return $this->error;
	}
}
	
?>