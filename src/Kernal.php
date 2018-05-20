<?php
/**
 * The fountain of youth for sparrow
 * no blood no life
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Yzwu <Ldos.net>
 */

class Blood {
	
	private static $_single_obj = array();
	private static $_db_obj = null;
	public static function dispatch() {
		try{
			//config
			require(ROOT_DIR.'/config/config.php');
			//error
			set_error_handler(array('Blood', 'user_error_handler'));
			//autoload
			self::reg_autoload(array('Blood', 'autoload'));
			//setup
			NutrientLib::setup(); 
			//router
			$router_pc = RouterFactoryLib::load();
			$router_pc->dispatch();
		} catch(Exception $e) {
			self::user_exception_handle($e);
			// echo 'Message: ' .$e->getMessage();
		}

	}
	 
	public static function reg_autoload($loader=array('Blood', 'autoload')) {
		if(function_exists('spl_autoload_register')) {
			return spl_autoload_register($loader);
		}
		if(spl_autoload_functions() === false) {
			require(dirname(__FILE__) . '/autoload.php');
		}
	}

	public static function unreg_autoload($loader=array('Blood', 'autoload')) {
		if(spl_autoload_functions()) {
			return spl_autoload_unregister($loader);
		} else {
			return false;
		}
	}

	public static function autoload($class_name) {
		$nspace = $class_name;
		$nspace_path = str_replace('\\', '/', $nspace);
		$nspace_arr = explode('/', $nspace_path);
		if(count($nspace_arr) > 1) {
			$filepath = ROOT_DIR.'/'.$nspace_path.'.php';
			if(is_file($filepath)) {
				require_once($filepath);
				return true;
			} else {
				$class_name = array_pop($nspace_arr);
			}
		}//if start namespace,your can must use the rule of namespace

    	$cname_split = preg_split("/(?=[A-Z])/", $class_name, 0, PREG_SPLIT_NO_EMPTY);
    	if(is_array($cname_split)) {
    		//stack
    		$array_tolower = function($v){return strtolower($v);};
    		$cname_arr = array_reverse(array_map($array_tolower, $cname_split));
    		$cname_ower = array_shift($cname_arr);
    		$ower_flag = '';
    		switch ($cname_ower) {
    			case 'lib':
    				$ower_flag = 'library';
    				break;
    			case 'con':
    				$ower_flag = 'controllers';
    				break;
    			case 'mod':
    				$ower_flag = 'models';
    				break;
    			default:
    				break;
    		}
    		$class_filep = ROOT_DIR.'/'.$ower_flag.'/'.implode('/', $cname_arr).'.php';
    		if(is_file($class_filep)) {
    			require_once($class_filep);
    		} else {
    			trigger_error('the file path is not exists!', E_USER_ERROR);
    		}
    	}
	}

	public static function user_error_handler($errno, $errstr, $errfile, $errline) {
		if(!(error_reporting() & $errno)) {
			return;
		}
		$error_type = '';
		$message = sprintf("(%s)%s in %s on line %s", $errno, $errstr, $errfile, $errline);
		switch($errno) {
			case E_WARNING:
				$error_type = 'E_WARNING';
				break;
			case E_NOTICE:
				$error_type = 'E_NOTICE';
				break;
			case E_STRICT:
				$error_type = 'E_STRICT';
				break;
			case E_USER_ERROR:
				$error_type = 'E_USER_ERROR';
				throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
				break;
			case E_USER_WARNING:
				$error_type = 'E_USER_WARNING';
				break;
			case E_USER_NOTICE:
				$error_type = 'E_USER_NOTICE';
				break;
			default:
				$error_type = 'UNKOWN_ERORR_TYPE';
				break;
		}		
		self::log($error_type.$message);
		return true;
	}

	public static function log($message, $show = false) {
		if(!empty($show)) {
			echo $message."\n";
		} else {
			$log = sprintf("%s\t%s\n", date('Y-m-d H:i:s'), $message);
			!defined('LOG_FILE_PATH')?define('LOG_FILE_PATH', ROOT_DIR.'/../logs/{date}.log'):'';
			if(stripos(LOG_FILE_PATH, '{date}') !== false) {
				$logfile = str_replace('{date}', date('Y_m_d'), LOG_FILE_PATH);	
			} else {
				$logfile = $logfile.time();
			} 
			
			if(!is_file($logfile)) {
				NutrientLib::mkfile($logfile);
				$logfile_h = "<?php exit(); ?>\n";
				file_put_contents($logfile, $logfile_h);
			}
			
			error_log($log, 3, $logfile);
		}
	}

	public static function single($class_name, $key = null) {
		if(!empty($key)) {
			$md5Key = md5($key);
		} else {
			$md5Key = md5($class_name);
		}

		if(!empty(self::$_single_obj[$md5Key])) {
			return self::$_single_obj[$md5Key];
		}
		else {
			self::$_single_obj[$md5Key] = new $class_name();
		}
		return self::$_single_obj[$md5Key];
	}

	public static function user_exception_handle($exception) {
		$message = $exception->getMessage();
		$t = $exception->getTrace();
		$t_message = $exception->getTraceAsString();
		
		if(is_array($t)) {
			$trace_output = '';
			foreach ($t as $key => $value) {
				if($value['file']) $file = basename($value['file']);
				if(is_array($value['args'])) $args = implode(',', $value['args']);
				$trace_output .= "<p>#{$key} {$value['class']}{$value['type']}{$value['function']}<span style='color:#062DFB' title='{$args}'>(...)</span><span style='color:#E3E;'>{$file}:{$value['line']}</span></p>";
			}
		}

		$output = <<<OUTPUT
		<!DOCTYPE html>
		<html>
			<head>
				<title>错误异常</title>
			</head>
			<body>
				<h1>$message</h1>
				<div>$trace_output</div>
			</body>
		</html>
OUTPUT;
		echo $output;
		exit;
	}

	public static function DB($ori = false) {
		try {
			if(!isset(self::$_db_obj)) {
			    self::$_db_obj = new ModelLib($ori);
			}
			if(self::$_db_obj instanceof ModelInterfaceLib)
				return self::$_db_obj;
			else
				throw new Exception('It has no db connection!');
		}catch(Exception $e) {
			//self::user_exception_handle();
			echo 'Message: ' .$e->getMessage();
		}
		
	}

	

}

