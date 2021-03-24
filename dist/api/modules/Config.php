<?php
	class Config{
	
	function __construct($actname, $path){
			$this->action = $actname;
			$this->path = __WRK . $path;	
		}
		
		public function action($data){
			$this->data = $data;
			return call_user_func(array($this, $this->action));
		}
		
		public function getConfig(){
			$tmp = parse_ini_file(CONFIG."config.main.ini");
			foreach($tmp as $key=>$val){
				if(strripos($val, "@")===false)
					$config[$key] = $val;
				else{
					$v = explode("@", $val);
					$config[$key] = $v[1];
				}
			}
			return array("config"=>$config);
		}
	}
?>