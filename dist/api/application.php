<?php
	include_once LIB."tokenHandler.php";
	include_once LIB."validator.php";
	
	class Application{
		
		var $path = null;
		var $action = '';
		var $modName = '';
		var $data;	
		var $log_action = array("deleteInterface", "saveInterface");
		
		private function getTokenFromHeaders(){
			$headers = getallheaders();
			return $headers["X-Token"];		
		}	
		
		private function synonim($action, $data){
			//if($action=="saveInterface" && $data->isNew==true)
				//return "addInterface";
			return $action;		
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
			
			if(!Validator::valid($reqData))
				return false;
			//echo $reqData->path;
			//Validator::cleanAll($reqData);
			if(array_key_exists("data", $reqData))
				$this->data = $reqData->data;
			if(($this->action=="login" || $this->action=="refreshToken")){
				$this->data->login = $reqData->login;
				$this->data->password = $reqData->password;
			}
			$this->modName = array_pop(explode("/", $this->path));
			return true;
		}		
		
		public function run(){
			if(!$this->getRequestData())
				return false;

			if($this->verify_token()===false){
				echo json_encode(array("data"=>"", "reqInfo"=>tokenHandler::getRequestInfo()));
				return false;
			}
			//echo $this->action;
			$module = new $this->modName($this->action, $this->path);
			$res = $module->action($this->data);
			if(in_array($this->action, $this->log_action))
				$this->addToLog($this->action, $this->data);
			echo json_encode(array("data"=>$res, "reqInfo"=>tokenHandler::getRequestInfo()));	
		}	
		
		private function getActionData(){
			switch($this->action){
				case 'saveInterface':
				case 'addInterface':
					return $this->path."/".$this->data->interfaceFile;
				case 'deleteInterface':
					return $this->path."/".$this->data->iName;
							
			}		
		}
		
		private function addToLog($actionName, $data){
			$actionName = $this->synonim($actionName, $data);
			$logData = $this->getActionData();
			$_t = date("H:i:s");
			$user = $_COOKIE['login'];
			$fLog = fopen(LOG . "log_" . date("d-m-y"), "a");
			$fCommonLog = fopen(LOG . "change.log", "a");
			fputs($fLog, $_t." ".$user." ".$actionName." ".$logData."\r\n");
			fputs($fCommonLog, $_t." ".$user." ".$actionName." ".$logData."\r\n");
			fclose($fLog);
			fclose($fCommonLog);
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