<?php
	include_once CONFIG."jwt.conf.php";
	include_once LIB."user.php";		
	include_once LIB."tokenHandler.php";
	
	class Login{
		
		var $action = '';
		var $path = '';
		var $data = null;
		var $_salt = 'fewf123f';
		
		function __construct($actname, $path){
			$this->action = $actname;
			$this->path = __WRK . $path;	
		}
		
		public function action($data){
			$this->data = $data;
			return call_user_func(array($this, $this->action));
		}	
		
		public function refreshToken(){
			tokenHandler::refreshToken();	
		}	
				
		public function login(){
			$login = $this->data->login;
			$password = $this->data->password;
			$user = new User();
			if($user->isUserExists($login) && $user->verify_password($login, $password)){
				$jwt = tokenHandler::getJWT($user);
				tokenHandler::setNewRefreshToken($jwt);
				//$id = tokenHandler::verify_token($jwt);
				//http_response_code(200);
				$_SESSION['login'] = $login;
				tokenHandler::setCode(500);
			   return array(
			            "message" => "Успешный вход в систему",
			            "jwt" => $jwt,
			            "login"=>$login,
			            "code"=>200,
			            "id"=>$id );	
			}
			else{
				// код ответа
			  http_response_code(401);
			
			  // сказать пользователю что войти не удалось
			  echo json_encode(array("message" => "Login incorrect", "pass" => $password));			
			}		
		}
	}
?>