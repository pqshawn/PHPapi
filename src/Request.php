<?php
/**
 * requst
 * reques the data from net 
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author: Yzwu <distil@163.com>
 */

namespace library;

 class RequestLib {
 	
 	public $_parameter = array();

 	public function __construct() {
 		
 	}

 	public function __destruct() {

 	}

 	public function set_param($parameter = '', $default = '') {
 		if(is_array($parameter) && !empty($parameter)) {
 			$this->_parameter = $parameter;
 		} else {
 			if(is_array($default) && !empty($default)) {
 				$this->_parameter = $default;
 			}
 		}
 		$this->_parameter = $this->filter($this->_parameter);
 	}

 	public function get_param() {
 		return $this->_parameter;
 	}

    public function filter($request = array()) {
    	if(empty($request)) {
    		$request = $_REQUEST;
            if(empty($_SERVER['PATH_INFO'])) {
                $parse_url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
                if($parse_url) {
                    parse_str($parse_url, $request);
                }
            }
    	}
        foreach ($request as $key=>$var) 
        { 
            if (is_numeric($var)) {
                $request[$key] = intval($var);
            } else {
                if (!get_magic_quotes_gpc()) { 
                    $var = addslashes($var); 
                } 
                $request[$key] = trim($var); 
            } 
        } 
        return $request;   
    }

 	
 }