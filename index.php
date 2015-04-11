<?php

namespace KTTest;

include_once 'vendor/autoload.php';

use KT;

$kttest = new KT\KTCurl();
echo $kttest->get('http://www.prosikli.com');

?>