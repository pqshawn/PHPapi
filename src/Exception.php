<?php
namespace PhpApi;

/**
 * 异常错误处理类
 * 
 * 关闭调试模式时，不可返回给端，应记入系统日志里，或日志服务器，或什么都不做
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */

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
		
		$error = array(
			'ret' => 0,
			'msg' => $message,
			'error_type' => "{$errno} - {$error_type}"
		);
		$Di = \PhpApi\Di::single();
		$Di->response = '\\PhpApi\\Response';
		$Di::single()->response->setBody($error, []);
		$Di::single()->response->output();
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

	/**
	 * 运行时错误 捕获函数
	 * 1.Fatal error 和 eval()的ParseError错误可以在这里抓到
	 * (注意：因为error_reporting的关闭可能非影响自定义错误，所以系统并没有error_reporting=0)
	 * 2.如果异步可以提醒错用中断函数
	 * 
	 * 
	 * 类似 (T_LNUMBER), expecting identifier (T_STRING)  编译性错误还是抓不到，如解决此缺陷则升级到php7.3用try catch解决
	 * ParseError extends CompileError as of PHP 7.3.0
	 */
	public static function callRegisteredShutdown() {
		error_reporting(0);
		$last_error = error_get_last();
		if (isset($last_error['type'])) {
			
			print_R($last_error);
		} else {
			// 记进其他日志文件或系统，不可抛出或打印
			
		}
		
	}
}