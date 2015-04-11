<?php

namespace KT;

class KTPlatform
{
	static function toString()
	{
		include_once dirname(__FILE__) . '/../lib/Mobile_Detect.php';
		
		$md = new \Mobile_Detect;
		return ($md->isMobile() ? 'mobile' : 'desktop');
	}
	
	static function isMobile()
	{
		include_once dirname(__FILE__) . '/../lib/Mobile_Detect.php';
		
		$md = new \Mobile_Detect;
		return ($md->isMobile() ? true : false);
	}
	
	static function isTablet()
	{
		include_once dirname(__FILE__) . '/../lib/Mobile_Detect.php';
		
		$md = new \Mobile_Detect;
		return ($md->isTablet() ? true : false);
	}
	
	static function isDesktop()
	{
		include_once dirname(__FILE__) . '/../lib/Mobile_Detect.php';
		
		$md = new \Mobile_Detect;
		return ($md->isMobile() ? false : true);
	}
	
	static function isAndroidOS()
	{
		include_once dirname(__FILE__) . '/../lib/Mobile_Detect.php';
		
		$md = new \Mobile_Detect;
		return ($md->isAndroidOS() ? true : false);
	}
	
	static function isiOS()
	{
		include_once dirname(__FILE__) . '/../lib/Mobile_Detect.php';
		
		$md = new \Mobile_Detect;
		return ($md->isiOS() ? true : false);
	}	
}

?>