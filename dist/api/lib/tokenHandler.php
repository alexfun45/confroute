<?php
	include_once CONFIG."jwt.conf.php";
	include_once LIB.'BeforeValidException.php';
	include_once LIB.'ExpiredException.php';
	include_once LIB.'SignatureInvalidException.php';
	include_once LIB.'JWT.php';
	use \Firebase\JWT\JWT;
	include_once LIB."user.php";
	global $confjwt;
	
	class tokenHandler{
		
		public static $code = 200;
		public static $info = '';
		public static $jwt = false;
		public static function generate_string($strength = 10) {
			 $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		    $input_length = strlen($input);
		    $random_string = '';
		    for($i = 0; $i < $strength; $i++) {
		        $random_character = $input[mt_rand(0, $input_length - 1)];
		        $random_string .= $random_character;
		    	}
    		return $random_string;
			}
		
		// validate refresh token
		public static function verifyRefreshToken($jwt, $refreshToken){
			return (substr($jwt, -6) == substr($refreshToken, -6));		
		}
		
		public static function setCode($code){
			self::$code = $code;		
		}
		
		public static function getRequestInfo(){
			if(self::$jwt!==false)
				self::$info->token = self::$jwt;
			self::$info->code = self::$code;
			return self::$info;		
		}
		
		public static function verify_token($token){
			global $confjwt;
			try {
				$decoded = JWT::decode($token, $confjwt['key'], array('HS256'));
				if(isset($_COOKIE["refreshToken"])){
					if($decoded->exp<time() ){
						$newjwt = tokenHandler::refreshToken($decoded);
						if(!$newjwt){
							self::$info = array(
			            "message" => "Токен просрочен и не может быть продлен",
			            "data"=>$decoded->exp,
			            "error"=>true,
			            "code" => 50001
			        		);
							return false;						
							}
						 else{
						 	tokenHandler::setCode(500);
						 	return true;
						 	}			
						}
					if($decoded->exp>time())
						return true;
					else{
					  self::$info = array(
		            "message" => "Токен просрочен или неверен",
		            "data"=>$decoded->exp,
		            "current_time"=>time(),
		            "error"=>true,
		            "code" => 50001
		        		);
		        		return false;							
					}				
				}
				else{
					 tokenHandler::setCode(50008);
					 //http_response_code(50008);
					 self::$info = array(
		            "message" => "Отсутствует токен обновления",
		            "code" => 50002
		        );					
				}
				return $decoded->data->id; 
				}
			 catch (Exception $e){
				 
				 if($e->getMessage()=="Expired token"){
					$newjwt = tokenHandler::refreshToken($decoded);
					tokenHandler::setCode(201);				       
			      if($newjwt===false){ 
			        self::$info = array(
			            "message" => "токен не верен",
			            "error" => $e->getMessage(),
			            "code"=> 50003
			        );
			        return false;
			        }
			        else{
			        	self::$jwt = $newjwt;
			        	return true;
			        	}
		         }
		   	 }	
		}
		
		/*public static function verify_token($token){
			global $confjwt;
			try {
				$decoded = JWT::decode($token, $confjwt['key'], array('HS256'));
				if(isset($_COOKIE["refreshToken"])){
					//&& tokenHandler::verifyRefreshToken($token, $_COOKIE["refreshToken"])
					if($decoded->exp<time() ){
						$newjwt = tokenHandler::refreshToken($decoded);
						if(!$newjwt){
							echo json_encode(array(
			            "message" => "Токен просрочен и не может быть продлен",
			            "data"=>$decoded->exp,
			            "error"=>true,
			            "code" => 50001
			        		));
							return false;						
							}
						 else{
						 	tokenHandler::setCode(500);
						 	return true;
						 	}			
						}
					if($decoded->exp>time())
						return true;
					else{
					  echo json_encode(array(
		            "message" => "Токен просрочен или неверен",
		            "data"=>$decoded->exp,
		            "current_time"=>time(),
		            "error"=>true,
		            "code" => 50001
		        		));
		        		return false;							
					}				
				}
				else{
					 tokenHandler::setCode(50008);
					 //http_response_code(50008);
					 echo json_encode(array(
		            "message" => "Отсутствует токен обновления",
		            "code" => 50002
		        ));					
				}
				return $decoded->data->id; 
				}
			 catch (Exception $e){
				 
				 if($e->getMessage()=="Expired token"){
					$newjwt = tokenHandler::refreshToken($decoded);
					tokenHandler::setCode(201);				       
			      if($newjwt===false){ 
			        echo json_encode(array(
			            "message" => "токен не верен",
			            "error" => $e->getMessage(),
			            "code"=> 50003
			        ));
			        return false;
			        }
			        else{
			        	self::$jwt = $newjwt;
			        	return true;
			        	}
		         }
		   	 }					
		}
		*/
		
		public static function refreshToken($token){
			if(isset($_COOKIE["refreshToken"])){
				$refreshToken = $_COOKIE["refreshToken"];
				$user = new User(); 
				if($user->getUserDataByIndex($token->id)!==false){
					$jwt = tokenHandler::getJWT($user);
					return $jwt;
					}								
				}
				return false;		
			}
		
		// generate a new refresh token
		public static function setNewRefreshToken($jwt){
			//$userId = $userId + 10000;
			//$refreshToken = tokenHandler::generate_string() . $confjwt['refreshKey'] . substr($jwt, -6);
			$refreshToken = tokenHandler::generate_string() . $confjwt['refreshKey'];
			setcookie("refreshToken", $refreshToken, time()+3600*24*7, '/', '', NULL, TRUE );		
		}
		
		public static function getJWT($user){
			global $confjwt;
			$token = array(
		       "iat" => time(),
		       "exp"=>time()+(60*15),			// time of life token 15 minutes
		       "data" => array(
		           "firstname" => $user->getField("name"),
		           "surname" =>$user->getField("surname"),
		           "lastname" => $user->getField("lastname"),
		           "email" => $user->getField("email"),
		           "id" => $user->getUserId()
		       )
		    );
		    $jwt = JWT::encode($token, $confjwt['key']);
		    return $jwt;				
		}
		
	}
?>