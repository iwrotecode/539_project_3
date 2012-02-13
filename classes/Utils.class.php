<?php
    phpinfo();
		
		class Utils{
			public static $sessionVar = "cms_session";
			
			static function getSessionVarValue(){
				// get ip address
				$ip = $_SERVER['REMOTE_ADDR'];
				
				// get user agent
				$broswer = $_SERVER['HTTP_USER_AGENT'];
				
				// add salt
				$salt = "pedroANDmatt";
				
				return $salt.$ip.$browser;
			}
			
			static function getSessionVar(){
				return self::$sessionVar;
			}
		}
?>