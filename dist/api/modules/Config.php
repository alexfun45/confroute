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
			$config = parse_ini_file(CONFIG."config.main.ini");
			return array("config"=>$config);
		}
	}
?>