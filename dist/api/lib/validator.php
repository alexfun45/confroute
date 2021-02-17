<?php
	class Validator{
		
		public static function check_length($value = "", $min, $max) {
		    $result = (mb_strlen($value) < $min || mb_strlen($value) > $max);
		    return !$result;
		}
				
		public static function clean($value = "") {
		    $value = trim($value);
		    $value = stripslashes($value);
		    $value = strip_tags($value);
		    $value = htmlspecialchars($value);
		    
		    return $value;
		}
		
		public static function cleanAll($values){
			foreach ($values as $property => $argument) {
             $values->{$property} = Validator::clean($argument);
           }		
		}
		
		public static function valid($values){
			foreach($values as $value){
				if(!Validator::check_length($value, 1, 256))
					return false;			
			}	
			return true;	
		}
			
	}
?>