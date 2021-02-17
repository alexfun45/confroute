<?php
	include_once LIB."tokenHandler.php";
	include_once LIB."validator.php";
	
	class Application{
		
		var $path = null;
		var $action = '';
		var $modName = '';
		var $data;	
		
		private function getTokenFromHeaders(){
			$headers = getallheaders();
			return $headers["X-Token"];		
		}	
		
		private function verify_token(){
			if(($this->action=="login" || $this->action=="refreshToken"))
				return true;
			$token = $this->getTokenFromHeaders();
			//if($token=="" || !$token)
				//return false;
			if(tokenHandler::verify_token($token))
				return true;
			else
				return false;		
		}		
		
		private function getRequestData(){
			$reqBody = file_get_contents("php://input");
			$reqData = json_decode($reqBody);
			$this->action = $reqData->action;
			$this->path = $reqData->path;
			//echo $reqData->action;
			if(!isset($reqData->action) || !isset($reqData->path))
				return false;
			
			//if(!Validator::valid($reqData))
				//return false;
			//echo $reqData->path;
			//Validator::cleanAll($reqData);
			if(array_key_exists("data", $reqData))
				$this->data = $reqData->data;
			$this->data->login = $reqData->login;
			$this->data->password = $reqData->password;
			$this->modName = array_pop(explode("/", $this->path));
			return true;
		}		
		
		public function run(){
			if(!$this->getRequestData())
				return false;

			if($this->verify_token()===false){
				return false;
			}
			//echo $this->action;
			$module = new $this->modName($this->action, $this->path);
			$res = $module->action($this->data);
			echo json_encode(array("data"=>$res, "code"=>tokenHandler::$code));	
		}	
		
		public static function autoload($className){
			 $_lastLoadedFilename = "./modules/" . $className . ".php";
			 //echo $_lastLoadedFilename."<br>";
			 if(file_exists($_lastLoadedFilename))
        	 	require_once($_lastLoadedFilename);
        	 else
        	 	return false;	
			}			
	}
	
	spl_autoload_register(array('Application', 'autoload'));	
?>