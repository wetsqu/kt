<?php

namespace KTTest;

include_once 'vendor/autoload.php';

use KT;

$messager = new KT\KTMessager('https://messager001.kooditehdas.fi', 'test', 'kissa123');
$r = $messager->send('', 'testmessage', array('val1' => 1));

if ($r)
{
	echo 'SUCCESS<br/>';
	
	var_dump($messager->response());
}
else
{
	echo 'ERROR<br/>';
	
	var_dump($messager->error());
}

?>