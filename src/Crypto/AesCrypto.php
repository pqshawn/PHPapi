<?php
namespace PhpApi\Crypto;

/**
 * AES 对称加密
 * 
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author Yzwu <distil@163.com>
 */


 class AesCrypto {

	protected $cipher = 'AES-128-CBC';

	// $key, $iv 参数自己生成, 可写进config里，如果客户端支持，建议iv自动生成，并hmac校验
	protected $key = '0123456789ABCDEF';
	
	protected $iv = 'FEDCBA9876543210';

	protected $hashType = 'sha256';
	
     /**
	 * 不同content-type不同处理，加密
	 * AES-128-CBC 对称加密
	 * 
	 */
	public function encrypt($response) {

		$ivlen = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        
		$ciphertextRaw = openssl_encrypt($response, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac($this->hashType, $ciphertextRaw, $this->key, $as_binary=true);
        
        $cryptResponse = base64_encode($iv.$hmac.$ciphertextRaw);
        
        return $cryptResponse;
	}

    /**
	 * AES-128-CBC 对称解密
	 */
	public function decrypt($request) {

        $decodeRes = base64_decode($request);
		$ivlen = openssl_cipher_iv_length($this->cipher);
		
        $iv = substr($decodeRes, 0, $ivlen);
        $hmac = substr($decodeRes, $ivlen, $sha2len=32);
		$ciphertextRaw = substr($decodeRes, $ivlen+$sha2len);

		$decryptReq = openssl_decrypt($ciphertextRaw, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac($this->hashType, $ciphertextRaw, $this->key, $as_binary=true);
        
		if (hash_equals($hmac, $calcmac))
		{
			return $decryptReq;
        }

        return false;
	}




	/**
	 * 通用加密,加入签名判断
	 * 
	 * 动态小写16进制字符串，如果客户端支持，可用encrypt()二进制传输
	 * key和iv自动生成或手动编写，机器生成可参照: base64_encode(openssl_random_pseudo_bytes(16));
	 * 这个函数里的key，iv都是固定的，只有签名是动态的
	 * @param string $response
	 * 
	 * @return string $cryptResponse
	 */
	public function comEncrypt($response) {
		$ivlen = openssl_cipher_iv_length($this->cipher);
		$dynamicIv = substr(bin2hex(openssl_random_pseudo_bytes($ivlen)), 0, $ivlen);

		$sign = hash_hmac($this->hashType, $dynamicIv . $response, $this->key, false);
		// 前$ivlen位替换成$dynamicIv
		$sign = substr_replace($sign, $dynamicIv, 0, $ivlen);
		// 预置位替换
		$response = str_replace('{sign}', $sign, $response);

		$ciphertextRaw = openssl_encrypt($response, $this->cipher, $this->key, OPENSSL_RAW_DATA, $this->iv);

        $cryptResponse = base64_encode($ciphertextRaw);
        
        return $cryptResponse;
	}

	// c15936265d1ca6581eaa882add05fa8516b9c6b906c281ca4589d1c6e65eedd9
	// 29da65efad82968b1eaa882add05fa8516b9c6b906c281ca4589d1c6e65eedd9
	// {"sign":"29da65efad82968b1eaa882add05fa8516b9c6b906c281ca4589d1c6e65eedd9","ret":10000,"msg":"\u6210\u529f","data":{"title":"test1","content":"test2"}}
    /**
	 * AES-128-CBC 对称解密
	 * 
	 * @return array
	 */
	public function comDecrypt($request) {
		$decodeRes = base64_decode($request);

		$decryptReq = openssl_decrypt($decodeRes, $this->cipher, $this->key, OPENSSL_RAW_DATA, $this->iv);
		$requestArray = json_decode($decryptReq, true);

		// 抽出dynamicIv
		$sign = $requestArray['sign'];
		$ivlen = openssl_cipher_iv_length($this->cipher);
		$dynamicIv = substr($sign, 0, $ivlen);
		$remainHmac = substr($sign, $ivlen);

		// 还原No sign json & 计算剩下签名正确与否
		$requestArray['sign'] = '{sign}';
		$calcmac = substr(hash_hmac($this->hashType, $dynamicIv . json_encode($requestArray), $this->key, false), $ivlen);
		
		if ($remainHmac == $calcmac)
		{
			return $requestArray;
        }

        return false;
	}

    
	/**
	 * simple encrypt
	 * 固定iv
	 */
	public function simpleEncrypt($response) {

		$ciphertextRaw = openssl_encrypt($response, $this->cipher, $this->key, OPENSSL_RAW_DATA, $this->iv);
		
        $cryptResponse = base64_encode($a.$ciphertextRaw);
        
        return $cryptResponse; 
	}

    /**
	 * AES-128-CBC 对称解密
	 * 固定iv
	 */
	public function testDecrypt($request) {

		$decodeRes = base64_decode($request);

		$decryptReq = openssl_decrypt($decodeRes, $this->cipher, $this->key, OPENSSL_RAW_DATA, $this->iv);
        
		return $decryptReq;
   
	}


 }