<?php

namespace KT;

use PicoFeed\Reader\Reader;

class KTRSS extends anlutro\cURL\cURL
{
	function __construct()
	{
		return new Reader;
	}	
}

?>