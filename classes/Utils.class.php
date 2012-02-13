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
			
			/**
			 * Sets a cookie based on the passed in values.
			 * 
			 * Defaults are sepecified in the class as properties
			 */
			static function setcookie($name, $value, $expire, $path=null, $domain=null, $secure=null){
				
				// check if we have variables passed in, if not, use the defaults defined 
				// in the class as properties
				if(is_null($path)){
					$path = self::$path;
				}
				if(is_null($domain)){
					$domain = self::$domain;
				}
				if(is_null($secure)){
					$secure = self::$secure;
				}
				
				// set the cookie
				return setcookie($name, $value, $expire, $path, $domain, $secure);
			}
		}
?>