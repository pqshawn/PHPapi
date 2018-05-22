<?php
namespace PhpApi;


class Exception {
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
}