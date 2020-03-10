<?php
namespace PhpApi\Response;

use PhpApi\Standard\Response\ResponseAbstract;
use PhpApi\Crypt\AesCrypt;
/**
 * 标准json响应
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */


class JsonResponse extends ResponseAbstract {

    protected $contentType = 'application/json';

    protected $cryptType = '';
    /**
     * @var 默认压缩
     */
    protected $compressFlag = false;

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
        
    }

    /**
     * 解密适配
     */
    protected function decrypt($cryptObj = null, $res = '') {
        
    }

    /**
     * 压缩
     * 暂支持ZLIB类型
     */
    protected function compress($type = 'ZLIB', $res = '') {
        
    }

    protected function uncompress($type = 'ZLIB', $res = '') {
        
    }
       
    public function output() {
        // 设置头
        $this->setHeaders();
        // 获取&处理数据
        $this->getBody();
        echo json_encode($this->dataWrapper);
    }

 }