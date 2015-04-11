<?php

namespace KT;

class KTJSMin
{
	static function minify($files, $cached = false)
	{	
		$content = KTJSMin::js_content($files);		
		$accept_encoding_gzip = strpos(Request::capture()->header('Accept-Encoding'), 'gzip') !== false;
		if ($accept_encoding_gzip) $content = gzencode($content, 5);
		
		$response = response($content, 200)->header('Content-Type', 'application/javascript');
		
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
	
	static function js_content($files)
	{	
		if (count($files) > 0)
		{
			$pc = new KTClosure();
			$etag = '';
			$content = '';
			$filename = '';
			
			foreach ($files as $file)
			{
				if (env('MINIFY', false))
				{
					// $pc->add($file)->simpleMode()->quiet()->hideDebugInfo();
					$pc->add($file)->simpleMode()->quiet();
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
				
				if (Storage::exists('minify/js/' . $filename . '-' . $etag))
				{
					return Storage::get('minify/js/' . $filename . '-' . $etag);
				}
				else
				{				
					$content = $pc->compile();
					$content = pack("CCC",0xef,0xbb,0xbf) . $content;
					
					Storage::put('minify/js/' . $filename . '-' . $etag, $content);
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