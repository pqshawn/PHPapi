<?php
namespace PhpApi\Crypt;

/**
 * AES 对称加密
 * 
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author Yzwu <distil@163.com>
 */


 class AesCrypt {

    protected $cipher = 'AES-128-CBC';

    protected $key = '000001';

    protected $hashType = 'sha256';
     /**
	 * 不同content-type不同处理，加密
	 * AES-128-CBC 对称加密
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
 }