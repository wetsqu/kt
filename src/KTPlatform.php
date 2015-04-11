<?php

namespace KT;

class KTPlatform
{
	static function toString()
	{
		$md = new \Mobile_Detect;
		return ($md->isMobile() ? 'mobile' : 'desktop');
	}
	
	static function isMobile()
	{
		$md = new \Mobile_Detect;
		return ($md->isMobile() ? true : false);
	}
	
	static function isTablet()
	{
		$md = new \Mobile_Detect;
		return ($md->isTablet() ? true : false);
	}
	
	static function isDesktop()
	{
		$md = new \Mobile_Detect;
		return ($md->isMobile() ? false : true);
	}
	
	static function isAndroidOS()
	{
		$md = new \Mobile_Detect;
		return ($md->isAndroidOS() ? true : false);
	}
	
	static function isiOS()
	{
		$md = new \Mobile_Detect;
		return ($md->isiOS() ? true : false);
	}	
}

?>