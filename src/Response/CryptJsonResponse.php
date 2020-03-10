<?php
namespace PhpApi\Response;

use PhpApi\Standard\Response\ResponseAbstract;

/**
 * 加密响应
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */


class CryptJsonResponse extends ResponseAbstract {

    protected $contentType = 'application/crypt-json';

    protected $cryptType = 'AesCrypt';
    /**
     * @var 默认压缩
     */
    protected $compressFlag = true;

    protected $compressType = 'ZLIB';

    protected $data = [];

    /**
     * @var 数组转化成的字符串
     */
    protected $dataWrapperString = '{}';
        
    protected function setHeaders() {
        @header('Content-type: ' . $this->contentType . ';charset=utf-8');
    }

    protected function getHeaders() {

    }

    protected function security() {

    }

    /**
     * 加密适配
     */
    protected function encrypt($cryptObj = null, $res = '') {
        return $cryptObj->encrypt($res);
    }

    /**
     * 解密适配
     */
    protected function decrypt($cryptObj = null, $res = '') {
        return $cryptObj->decrypt($res);
    }

    /**
     * 压缩
     * 暂支持ZLIB类型
     */
    protected function compress($type = 'ZLIB', $res = '') {
        $result = $res;
        if ($this->compressFlag && $type == 'ZLIB') {
            $result = gzcompress($res, -1,  ZLIB_ENCODING_DEFLATE);
        }
        return $result;
    }

    protected function uncompress($type = 'ZLIB', $res = '') {
        $result = $res;
        if ($type == 'ZLIB') {
            $result = gzuncompress($res);
        }
        return $result;
    }
       
    public function output() {
        $dataWrapper = $this->getBody();
        $this->dataWrapperString = json_encode($dataWrapper);
        // 加密
        $cryptType = $this->cryptType;
        $cryptClassName = '\\PhpApi\\Crypt\\' . $cryptType;
        $cryptObj = new $cryptClassName();
        $cryptString = $this->encrypt($cryptObj, $this->dataWrapperString);
        
        // 压缩
        $result = $this->compress($this->compressType, $cryptString);
        echo ($result);
    }

 }