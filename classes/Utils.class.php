<?php
		class Utils{
			private static $sessionVar = "cms_session";
			
			// TODO: Cookie variables
			private static $path = "/~pjm8632/";
			private static $domain = "nova.it.rit.edu";
			private static $secure = false;
			
			static function getSessionVarValue(){
				// get ip address
				$ip = $_SERVER['REMOTE_ADDR'];
				
				// get user agent
				$broswer = $_SERVER['HTTP_USER_AGENT'];
				
				// add salt
				$salt = "pedroANDmatt";
				
				return $salt.$ip.$broswer;
			}
			
			static function getSessionVar(){
				return self::$sessionVar;
			}
			
			static function setcookie($name, $value, $expire, $path=null, $domain=null, $secure=null){
				if(!$path){
					
				}
				
				
				setcookie($name, $value, $expire, $path, $domain, $secure);
			}
		}
?>