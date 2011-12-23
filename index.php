<?php

define('THINK_PATH', './ThinkPHP/');

define('APP_NAME', 'WordDestructor');
define('APP_PATH', './WordDestructor');
define('AUX', './Auxiliary/proxy');
session_start();

require(THINK_PATH."/ThinkPHP.php");
require(AUX."/Permission.class.php");
require(AUX."/ProxyDispatcher.class.php");
require(AUX."/Exception.class.php");

ProxyCollection::startProxy();
App::run();

?>
