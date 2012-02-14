<?php
		class Utils{
			private static $sessionVar = "cms_session";
			
			// Cookie variables
			// TODO: Change for nova			
			// private static $path = "/539_project_3/";
			// private static $domain = "localhost";
			private static $secure = false;
			private static $daysExpire = 3;
			
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
			static function setCookie($name, $value, $expire=null/*, $path=null, $domain=null, $secure=null*/){
				
				// check if we have variables passed in, if not, use the defaults defined 
				// in the class as properties
				/*
				if(is_null($path)){
					$path = self::$path;
				}
				if(is_null($domain)){
					$domain = self::$domain;
				}
				if(is_null($secure)){
					$secure = self::$secure;
				}
				*/
				if(is_null($expire) || !is_int($expire)){
					// seconds in a day: 86400
					$expire = (time() + 86400) *self::$daysExpire;
				}
				
				// set the cookie
				return setcookie($name, $value, $expire/*, $path, $domain, $secure*/);
			}
			
			/**
			 * Clears the specified cookie, and changes its expiration to the number of
			 * seconds specified.
			 * 
			 * @param name - name of the cookie
			 * @param $expire [optional]- seconds to expire, default is 3 days ago 
			 */
			static function expireCookie($name, $expire=null){
				// if they specified a number for expire, then negate it if not already	
				if(is_int($expire) && $expire > 0){
					$expire = -$expire;
				}
				
				// expire the cookie and return
				return self::setCookie($name, "", $expire);
			}
		}
?>