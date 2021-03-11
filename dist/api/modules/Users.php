<?php
	include_once LIB."user.php";
	class Users{
		
		function __construct($actname, $path){
			$this->action = $actname;
			$this->path = __WRK . $path;	
		}
		
		public function action($data){
			$this->data = $data;
			return call_user_func(array($this, $this->action));
		}
		
		public function getUsers(){
			$user = new User();
			return $user->getUsers();	
		}
		
		public function editUser(){
			$user = new User();
			return $user->editUser($this->data);	
		}	
		
		public function removeUser(){
			$user = new User();
			$user->removeUser($this->data);		
		}
		
		public function addUser(){
			$user = new User();
			$user->addUser($this->data);		
		}
	}
?>