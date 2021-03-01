<?php
	class User{
		
		var $dataIndex = array("password"=>0, "name"=>1, "surname"=>2, "lastname"=>3, "email"=>4);	
		var $index = null;
		var $_salt = 'fewf123f';
		function __construct(){
			
		}
				
		public function isUserExists($login){
			if($this->isLoginExists($login))
				return true;
			if($this->isEmailExists($login))
				return true;
			return false;		
		}
		
		protected function getLoginIndex($login){
			$flogins = fopen(DB."logins.txt", "r");
			$i = 0;
			while($rlogin = trim(fgets($flogins))){
				if(strcmp($login,$rlogin)==0){
					$this->index = $i;
					fclose($flogins);
					return $i;
				}
				$i++;
			}
			fclose($flogins);
			return false;	
		}
		
		public function isLoginExists($login){
			if($this->getLoginIndex($login)!==false)
				return true;
			
			return false;						
		}
		
		public function isEmailExists($login){
			if($this->getEmailIndex($login)!==false)
				return true;
			
			return false;		
		}
		
		protected function getEmailIndex($email){
			$femails = fopen(DB."emails.txt", "r");
			$i = 0;
			while($rEmail = trim(fgets($femails))){
				if($email==$rEmail){
					$this->index = $i;
					fclose($femails);
					return $i;
				}
				$i++;
			}
			fclose($femails);
			return false;		
		}
		
		protected function getUserIndex($login){
			if($this->index!=null)
				return $this->index;
			$indx = $this->getLoginIndex($login);
			if($indx!==false)
				return $indx;
			$indx = $this->getEmailIndex($login);
			if($indx!==false)
				return $indx;
			return false;		
		}
		
		// works only after execution getUserIndex() or getUserData()
		public function getUserId(){
			return $this->index;		
		}
		
		public function getField($field){
			if($this->userdata!=null){
				return $this->userdata[$field];
				}		
		}
		
		public function getUserDataByIndex($indx){
			$fuserData = fopen(DB."userdata.txt", "r");
			$i = 0;
			while($dataline = trim(fgets($fuserData))){	
				if($i==$indx){
					$this->userdata = $data;
					fclose($fuserData);
					return true;
					}		
				}
			fclose($fuserData);
			return false;
		}
		
		public function getUserData($login, $field = false){
			if($this->userdata!=null){
				if($field===false)
					return $this->userdata;
				else
					return $this->userdata[$field];			
			}
			$loginIndex	= $this->getUserIndex($login);
			$i = 0;
			$fuserData = fopen(DB."userdata.txt", "r");
			while($dataline = trim(fgets($fuserData))){	
				if($i==$loginIndex){
					$data = explode(";", $dataline);
					$this->userdata = $data;
					if($field===false)
						return $data;
					else if(array_key_exists($field, $this->dataIndex)){
						return $data[$this->dataIndex[$field]];					
					}
					else
						return false;
				}
				$i++;
			}
		}
		
		public function verify_password($login, $password){
			$passw = $this->getUserData($login, "password");
			$hashPass = hash("sha256", $password . $this->_salt);
			//echo $hashPass;
			//$hashPass = md5($password . $this->_salt);
		   //echo $passw;
			//echo json_encode( mb_strtoupper( trim("admin465") ) == mb_strtoupper(trim($passw)));
			//echo json_encode(preg_match('/'.$hashPass.'/iu', $passw));
			if(preg_match('/'.$hashPass.'/iu', $passw)==true)
				return true;
			else
				return false;		
		}
	}
?>