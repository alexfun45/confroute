<?php
	if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
	if(!defined('__INC')) define('__INC', dirname(__FILE__).DS."..".DS."..".DS);
	if(!defined('SYS')) define('SYS', __INC."sys".DS);
	if(!defined('__WRK')) define('__WRK',__INC."wrk");
	if(!defined('CONFIG')) define('CONFIG',SYS."config".DS);
	if(!defined('LOG')) define('LOG',SYS."logs".DS);
	if(!defined('ASSETS')) define('ASSETS',__INC."public".DS."assets".DS);
	if(!defined('DB')) define('DB',__WRK.DS."users".DS);
	if(!defined('LIB')) define('LIB',"./lib".DS);
?>