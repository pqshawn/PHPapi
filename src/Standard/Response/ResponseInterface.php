<?php
namespace PhpApi\Standard\Response;

/**
 * response interface
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */


 interface ResponseInterface {
	 
	public function setHeaders();

	public function getHeaders();

	public function security();

	public function getBody();

 	public function setBody($data = [], ...$params);
	 
	public function output();

	public function encrypt($cryptObj = null, $res = '');

	public function decrypt($cryptObj = null, $res = '');
	
	public function compress($type = 'ZLIB', $res = '');

	public function uncompress($type = 'ZLIB', $res = '');

 }