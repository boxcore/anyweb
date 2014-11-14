<?php if ( !defined('BOMB') ) exit('No direct script access allowed');

class HttpHelperModel {
    // 当前的user-agent字符串
    public $ua_string= "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:14.0) Gecko/20100101 Firefox/14.0.1";
    // Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)

    // 支持的提交方式
    public $post_type_list = array("curl", "socket", "stream");

    // 本地cookie文件
    private $cookie_file;

    public function __construct() {

    }

    public static function httpGet($url) {
        $url = trim($url);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);    //表示需要response header
        curl_setopt($ch, CURLOPT_NOBODY, FALSE); //表示需要response body
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        
        $response = curl_exec($ch);
        if ($response) {
            // curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200'
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

            $data = array();
            $data['header'] = trim(substr($response, 0, $headerSize));
            $data['body'] = htmlentities(substr($response, $headerSize));

            return $data;
        }

        // if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200') {
        //     return $result;
        // }
        
        return NULL;
    }

    /**
     * 发送HTTP请求
     *
     * @param string $url 请求地址
     * @param string $method 请求方式 GET/POST
     * @param string $refererUrl 请求来源地址
     * @param array $data 发送数据
     * @param string $contentType 
     * @param string $timeout
     * @param string $proxy
     * @return boolean
     */
    public static function send_request_by_curl($url, $data, $refererUrl = '', $method = 'GET', $contentType = 'application/json', $timeout = 30, $proxy = false) {
        $ch = null;
        if('POST' === strtoupper($method)) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER,0 );
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            if ($refererUrl) {
                curl_setopt($ch, CURLOPT_REFERER, $refererUrl);
            }
            if($contentType) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:'.$contentType));
            }
            if(is_string($data)){
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            }
        } else if('GET' === strtoupper($method)) {
            if(is_string($data)) {
                $real_url = $url. (strpos($url, '?') === false ? '?' : ''). $data;
            } else {
                $real_url = $url. (strpos($url, '?') === false ? '?' : ''). http_build_query($data);
            }

            $ch = curl_init($real_url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:'.$contentType));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            if ($refererUrl) {
                curl_setopt($ch, CURLOPT_REFERER, $refererUrl);
            }
        } else {
            $args = func_get_args();
            return false;
        }

        if($proxy) {
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
        }
        $ret = curl_exec($ch);
        $info = curl_getinfo($ch);
        $contents = array(
                'httpInfo' => array(
                        'send' => $data,
                        'url' => $url,
                        'ret' => $ret,
                        'http' => $info,
                )
        );

        curl_close($ch);
        return $ret;
    }
}