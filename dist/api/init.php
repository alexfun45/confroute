<?php
	if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
	if(!defined('__INC')) define('__INC', dirname(__FILE__).DS."..".DS);
	if(!defined('__WRK')) define('__WRK',__INC."wrk");
	if(!defined('CONFIG')) define('CONFIG',"./config".DS);
	if(!defined('ASSETS')) define('ASSETS',__INC."assets".DS);
	if(!defined('DB')) define('DB',__WRK.DS."users".DS);
	if(!defined('LIB')) define('LIB',"./lib".DS);
?>