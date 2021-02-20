<?php
	class admin{
	
	function __construct($actname, $path){
			$this->action = $actname;
			$this->path = __WRK . $path;	
		}
		
		public function action($data){
			$this->data = $data;
			return call_user_func(array($this, $this->action));
		}
		
		public function setLogo(){
			$base64image = $this->data->imagedata;
			$img = str_replace('data:image/png;base64,', '', $base64image);
			$img = str_replace(' ', '+', $img);
			$data = base64_decode($img);
			$file = ASSETS .  'logo.png';
			$success = file_put_contents($file, $data);
			return $file;		
		}
	}
?>