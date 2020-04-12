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
		$exNames = explode('/', $errfile);
		$fileName = array_pop($exNames);
		$message = sprintf("(%s)%s in %s on line %s", $errno, $errstr, $fileName, $errline);
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
				throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
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

		self::exceptionToResponse($error);
    }

    public static function exceptionToResponse($error = array()) {
		$Di = \PhpApi\Di::single();
		$Di->response = '\\PhpApi\\Response\\ResponseFactory';
		$Di::single()->response->setBody($error, []);
		$Di::single()->response->output();
		exit;
    }
    
    public static function user_exception_handle($exception) {
		$code = $exception->getCode();
		$message = $exception->getMessage();
		$t = $exception->getTrace();
		$t_message = $exception->getTraceAsString();
		$data = [];
		if(is_array($t)) {
			$trace_output = '';
			foreach ($t as $key => $value) {
				$class = isset($value['class'])? $value['class'] : '';
				$function = isset($value['function'])? $value['function'] : '';
				$type = isset($value['type'])? $value['type'] : '';
				$file = isset($value['file'])? $value['file'] : '';
				$line = isset($value['line'])? $value['line'] : '';

				$data[] = "{$class}{$type}{$function}(...args) from file: {$file} in line {$line}";
				
			}
		}
		// 部分内容要处理进日志,不能返回到端@todo
		// 如果用户自定义了错误了，返回用户自定义的message和ret
		$configObj = \PhpApi\Di::single()->config;
		$curAppStatus = $configObj->codeStatus[$configObj->appName];
		$statusKey = array_search($code, $configObj->sysCodeStatus);
		$error = array(
			'ret' => ($statusKey !== false && isset($curAppStatus[$statusKey]))? $curAppStatus[$statusKey]: $code,
			'msg' => isset($configObj->lang[$statusKey])? $configObj->lang[$statusKey]: $message,
			'dev_msg' => $message, // 开发人员预留备用
			// 'data' => json_encode($data)
		);

		self::exceptionToResponse($error);

// 		$output = <<<OUTPUT
// 		<!DOCTYPE html>
// 		<html>
// 			<head>
// 				<title>错误异常</title>
// 			</head>
// 			<body>
// 				<h1>$message</h1>
// 				<div>$trace_output</div>
// 			</body>
// 		</html>
// OUTPUT;
// 		echo $output;
		exit;
	}

	/**
	 * 运行时错误 捕获函数
	 * 1.Fatal error 和 eval()的ParseError错误可以在这里抓到
	 * (注意：因为error_reporting的关闭可能非影响自定义错误，所以系统并没有error_reporting)
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