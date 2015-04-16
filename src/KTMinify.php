<?php

namespace KT;

use Request;
use Storage;
use MatthiasMullie\Minify;

class KTMinify
{
	function __construct()
	{		
	}
	
	static function js($files, $cached = false)
	{	
		$minify = new KTMinify();		
		$data = $minify->build_js($files);
		
		$accept_encoding_gzip = strpos(Request::capture()->header('Accept-Encoding'), 'gzip') !== false;
		if ($accept_encoding_gzip) $data['content'] = gzencode($data['content'], 5);
		
		$data['response'] = response($data['content'], 200)->header('Content-Type', 'application/javascript');
		
		if ($cached)
		{
			$datetime_current = date('r', time());
			$datetime_expires = date('r', time() + 3600 * 24 * 7);
		
			$data['response']->setLastModified(new \DateTime($datetime_current))
				             ->setExpires(new \DateTime($datetime_expires))
				             ->setPublic();
		}
		
		if ($accept_encoding_gzip) $data['response']->header('Content-Encoding', 'gzip');
		
		return $data;
		//return $response;
	}
	
	static function css($files, $cached = false)
	{	
		$minify = new KTMinify();		
		$content = $minify->build_css($files);
		
		$accept_encoding_gzip = strpos(Request::capture()->header('Accept-Encoding'), 'gzip') !== false;
		if ($accept_encoding_gzip) $content = gzencode($content, 5);
		
		$response = response($content, 200)->header('Content-Type', 'text/css');
		
		if ($cached)
		{
			$datetime_current = date('r', time());
			$datetime_expires = date('r', time() + 3600 * 24 * 7);
		
			$response->setLastModified(new \DateTime($datetime_current))
				     ->setExpires(new \DateTime($datetime_expires))
				     ->setPublic();
		}
		
		if ($accept_encoding_gzip) $response->header('Content-Encoding', 'gzip');
		return $response;
	}
	
	private function build_js($files)
	{	
		if (count($files) > 0)
		{
			$closure = new KTClosure();
			$etag = '';
			$content = '';
			$filename = '';
			
			foreach ($files as $file)
			{
				if (env('MINIFY', false))
				{
					// $pc->add($file)->simpleMode()->quiet()->hideDebugInfo();
					$closure->add($file)->simpleMode()->quiet();
					$filename .= (strlen($filename) > 0 ? '-' : '') . basename($file);					
					$etag .= md5(filemtime($file));
				}
				else
				{
					$content .= file_get_contents($file) . ' ';
					$etag .= md5(filemtime($file));
				}
			}
			
			$etag = md5($etag);
			
			if (env('MINIFY', false))
			{
								
				$filename = str_replace('.', "_", $filename);
				
				if (Storage::exists('ktminify/js/' . $filename . '-' . $etag))
				{
					return array('content' => Storage::get('ktminify/js/' . $filename . '-' . $etag), 'etag' => $etag);
				}
				else
				{				
					$content = $closure->compile();
					$content = pack("CCC",0xef,0xbb,0xbf) . $content;
					
					Storage::put('ktminify/js/' . $filename . '-' . $etag, $content);
					return array('content' => $content, 'etag' => $etag);
				}
			}
			else
			{
				return array('content' => $content, 'etag' => $etag);
			}
		}
		
		return '';
	}
	
	private function build_css($files)
	{
		if (count($files) > 0)
		{
			$minifier = new Minify\CSS();
			$etag = '';
			$content = '';
			$filename = '';
			
			foreach ($files as $file)
			{
				if (env('MINIFY', false))
				{
					$minifier->add($file);
					$filename .= (strlen($filename) > 0 ? '-' : '') . basename($file);
					$etag .= md5(filemtime($file));
				}
				else
				{
					$content .= file_get_contents($file) . ' ';
				}
			}
			
			if (env('MINIFY', false))
			{
				$etag = md5($etag);
				$filename = str_replace('.', "_", $filename);
				
				if (Storage::exists('ktminify/css/' . $filename . '-' . $etag))
				{
					return Storage::get('ktminify/css/' . $filename . '-' . $etag);
				}
				else
				{
					$content = $minifier->minify();
					$content = pack("CCC",0xef,0xbb,0xbf) . $content;
					
					Storage::put('ktminify/css/' . $filename . '-' . $etag, $content);				
					return $content;
				}
			}
			else
			{
				return $content;
			}	
		}
		
		return '';
	}
}

?>