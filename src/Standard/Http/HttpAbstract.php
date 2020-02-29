<?php
namespace PhpApi\Standard;

/**
 * 关于Http的使用 之 接口规范
 * 因为web服务器的解析，fastcgi部分基本不会解析原始包，定义此规范，主要为了娴接request和response，模拟分化PHPapi框架需要进一步阐述的那部分
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
 */

abstract class HttpAbstract {
    
    protected $http_method = '';
    /**
     * HTTP/1.1 中可使用的方法
     */
    protected function httpGet() {

    }

    protected function httpPost() {
        
    }

    protected function httpPut() {
        
    }

    protected function httpHead() {
        
    }

    protected function httpDelete() {
        
    }

    protected function httpOptions() {
        
    }

    protected function httpTrace() {
        
    }

    protected function httpConnect() {
        
    }
    protected function httpLink() {
        
    }
    protected function httpUnlink() {
        
    }
    /**
     * 请求报文
     * {
     * 报文首部
     * 空行CR+LF
     * 报文主体
     * }
     */
    protected function requestPacket() {
        $requestPacket = array(
            '报文首部' => array(
                            '请求行',
                            '请求首部字段' => array(
                                            'Accept'         => '用户代理可处理的媒体类型',
                                            'Accept-Charset' => '优先的字符集',
                                            'Accept-Encoding'=> '优先的内容编码',
                                            'Accept-Language'=> '优先的语言(自然语言)',
                                            'Authorization'  => 'Web认证信息',
                                            'Expect'         => '期待服务器的特定行为',
                                            'From'           => '用户的电子邮箱地址',
                                            'Host'                => '请求资源所在服务器',
                                            'If-Match'            => '比较实体标记(ETag)',
                                            'If-Modified-Since'   => '比较资源的更新时间',
                                            'If-None-Match'       => '比较实体标记(与 If-Match 相反)',
                                            'If-Range'            => '资源未更新时发送实体Byte 的范围请求',
                                            'If-Unmodified-Since' => '比较资源的更新时间(与If-Modified-Since相反)',
                                            'Max-Forwards'        => '最大传输逐跳数',
                                            'Proxy-Authorization' => '代理服务器要求客户端的认证信息',
                                            'Range'               => '实体的字节范围请求',
                                            'Referer'             => '对请求中 URI 的原始获取方',
                                            'TE'                  => '传输编码的优先级',
                                            'User-Agent'          => 'HTTP 客户端程序的信息',
                                            'Accept'              => '用户代理可处理的媒体类型',
                                            'Accept'              => '用户代理可处理的媒体类型',
                            ),
                            '通用首部字段' => array(
                                            'Cache-Control' => '控制缓存的行为',
                                            'Connection'    => '逐跳首部、连接的管理',
                                            'Date'          => '创建报文的日期时间',
                                            'Pragma'        => '报文指令',
                                            'Trailer'       => '报文末端的首部一览',
                                            'Transfer-Encoding' => '指定报文主体的传输编码方式',
                                            'Upgrade'           => '升级为其他协议',
                                            'Via'               => '代理服务器的相关信息',
                                            'Warning'           => '错误通知',
                            ),
                            '实体首部字段' => array(
                                'Allow'            => '资源可支持的HTTP方法',
                                'Content-Encoding' => '实体主体适用的编码方式',
                                'Content-Language' => '实体主体的自然语言', 
                                'Content-Length'   =>  '实体主体的大小(单位:字节)',
                                'Content-Location' => '替代对应资源的URI',
                                'Content-MD5'      => '实体主体的报文摘要',
                                'Content-Range'    => '实体主体的位置范围',
                                'Content-Type'     => '实体主体的媒体类型',
                                'Expires'          => '实体主体过期的日期时间',
                                'Last-Modified'    => '资源的最后修改日期时间',  
                            ),
                            '其他'
            ),
            '空行CR+LF' => array(),
            '报文主体'   => array(),
        );
    }

    /**
     * 响应报文
     * {
     * 报文首部
     * 空行CR+LF
     * 报文主体
     * }
     */
    protected function responsePacket() {
        $responsePacket = array(
            '报文首部' => array(
                            '状态行',
                            '响应首部字段' => array(
                                    'Accept-Ranges' => '是否接受字节范围请求',
                                    'Age'           => '推算资源创建经过时间',
                                    'ETag'          => '资源的匹配信息',
                                    'Location'      => '令客户端重定向至指定URI',
                                    'Proxy-Authenticate' => '代理服务器对客户端的认证信息',
                                    'Retry-After'        => '对再次发起请求的时机要求',
                                    'Server'             => 'HTTP服务器的安装信息',
                                    'Vary'               => '代理服务器缓存的管理信息',
                                    'WWW-Authenticate'   => '服务器对客户端的认证信息',
                            ),
                            '通用首部字段' => array(
                                'Cache-Control' => '控制缓存的行为',
                                'Connection'    => '逐跳首部、连接的管理', // HTTP/1.1 版本的默认连接都是持久连接,1.0加Keep-Alive
                                'Date'          => '创建报文的日期时间', // 创建HTTP报文的日期和时间
                                'Pragma'        => '报文指令', // 兼容1.0 no-cache
                                'Trailer'       => '报文末端的首部一览', // 主体后记录了哪些首部字段。该首部字段可应用在 HTTP/1.1 版本分块传输编码时。
                                'Transfer-Encoding' => '指定报文主体的传输编码方式', // 传输报文主体时采用的编码方式
                                'Upgrade'           => '升级为其他协议', // 用于检测HTTP协议及其他协议是否可使用更高版本进行通信,需要额外指定 Connection:Upgrade
                                'Via'               => '代理服务器的相关信息', // 追踪客户端与服务器之间的请求和响应报文的传输路径
                                'Warning'           => '错误通知', // 一些与缓存相关的问题的警告
                            ),
                            '实体首部字段' => array(
                                    'Allow'            => '资源可支持的HTTP方法',
                                    'Content-Encoding' => '实体主体适用的编码方式',
                                    'Content-Language' => '实体主体的自然语言', 
                                    'Content-Length'   =>  '实体主体的大小(单位:字节)',
                                    'Content-Location' => '替代对应资源的URI',
                                    'Content-MD5'      => '实体主体的报文摘要',
                                    'Content-Range'    => '实体主体的位置范围',
                                    'Content-Type'     => '实体主体的媒体类型',
                                    'Expires'          => '实体主体过期的日期时间',
                                    'Last-Modified'    => '资源的最后修改日期时间',  
                            ),
                            '其他'
            ),
            '空行CR+LF' => array(),
            '报文主体'   => array(),
        );
    }

    /**
     * 压缩种类
     * gzip,compress,deflate(zlib)，identity
     */
    protected function compressType() {
        
    }

    /**
     * 多种数据集合
     * form-data
     * 206状态 byteranges，
     */
    protected function multipart() {

    }

    /**
     * 通信数据转发程序
     * 代理、网关、隧 道
     */
    protected function getTransfarWay() {

    }

    /**
     * 端到端外的首部，即Hop-by-hop Header 逐跳首部
     * 
     */
    protected function hopByHopHeader() {
        $hopByHopHeader = ['Connection', 'Keep-Alive', 'Proxy-Authenticate', 'Proxy-Authorization', 'Trailer', 'TE', 'Transfer-Encoding', 'Upgrade'];
    }
    
    /**
     * 通用首部字段-Cache-Control
     */
    protected function cacheControlHeader() {
        $request = array(
            'no-cache'   => '不缓存过期的资源：代理需转发',
            'no-store'   => 'no-store 才是真正地不进行缓存',
            'max-age'    => '客：没超过代理给；服：期间不要确认，1.1优先于Expire',
            'max-stale'  => '过期多少秒照接收',
            'min-fresh'  => '还可保鲜多少秒,过期不响应',
            'no-transform'   => '缓存都不能改,变实体主体的媒体类型',
            'only-if-cached' => '取缓存代理本地',
            'cache-extension'
        );

        $response = array(
            'public',
            'private',
            'no-cache' => 'a.如果源服务器加之则通知代理不确认，不缓存; b.加参Location响应，让客端不能发起缓存',
            'no-store',
            'no-transform' => '缓存都不能改,变实体主体的媒体类型,防缓存或压缩图片等',
            'must-revalidate' => '向源服务器再次验证',
            'proxy-revalidate' => '所有的缓存服务器在接收到客端带有该指令的请求返回响应之前，必须再验证缓存有效性',
            'max-age',
            's-maxage' => '多台缓存服务多用户',
            'cache-extension'
        );
    }

    /**
     * 通用首部字段-Connection
     */
    protected function connectionHeader() {

    }







}