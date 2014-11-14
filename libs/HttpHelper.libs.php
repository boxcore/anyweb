<?php
/**
 *  HTTP常用请求封装
 * @version $Id: HttpHelper.php,v 1.0 2012-8-9
 * @package library
 * @site www.jbxue.com
 */

// ---------------------------

/**
 * http请求处理
 *
 * 开发中经常需要模拟提交请求，本类封装了常用的post方法
 *
 * @author ustb80
 *
 */
class HttpHelper
{
    // 当前的user-agent字符串
    public $ua_string= "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:14.0) Gecko/20100101 Firefox/14.0.1";

    // 支持的提交方式
    public $post_type_list = array("curl", "socket", "stream");

    // 本地cookie文件
    private $cookie_file;

    // -----------------------

    /**
     * 构造函数
     *
     * @param array $params 初始化参数
     */
    public function __construct($params = array())
    {
        if(count($params) > 0)
        {
            $this->init($params);
        }
    }

    // -----------------------

    /**
     * 参数初始化
     *
     * @param array $params
     */
    public function init($params)
    {
        if(count($params) > 0)
        {
            foreach($params as $key => $val)
            {
                if(isset($this->$key))
                {
                    $this->$key = $val;
                }
            }
        }
    }

    // -----------------------

    /**
     * 提交请求
     *
     * @param string $url 请求地址
     * @param mixed $data 提交的数据
     * @param string $type 提交类型，curl,socket,stream可选
     */
    public function post($url, $data, $type = "socket")
    {
        if(!in_array($type, $this->post_type_list))
        {
            die("undefined post type");
        }
        $function_name = $type . "Post";
        return call_user_func_array(array($this, $function_name), array($url, $data));
    }

    // -----------------------

    /**
     * 更改默认的ua信息
     *
     * 本方法常用于模拟各种浏览器
     *
     * @param string $ua_string UA字符串
     */
    public function setUA($user_agent)
    {
        $this->ua_string = $user_agent;
        return $this;
    }

    // -----------------------

    /**
     * 设置本地cookie文件
     *
     * 在用curl来模拟时常需要设置此项
     *
     * @param string $cookie_file 文件路径
     */
    public function setCookieFile($cookie_file)
    {
        $this->cookie_file = $cookie_file;
        return $this;
    }

    // -----------------------

    /**
     * curl方式提交
     *
     * @param string $url 请求地址
     * @param mixed $data 提交的数据
     * @param string $user_agent 自定义的UA
     * @return mixed
     */
    public function curlPost($url, $data, $user_agent = "")
    {
        if($user_agent == "")
        {
            $user_agent = $this->ua_string;
        }

        if (!is_array($data))
        {
            $data = array($data);
        }

        $data = http_build_query($data);

        if (!function_exists("curl_init"))
        {
            die("undefined function curl_init");
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        $rs = curl_exec($ch);
        curl_close($ch);
        return $rs;
    }

    // -----------------------

    /**
     * 套接字提交
     *
     * @param string $url 请求地址
     * @param mixed $data 提交的数据
     * @param string $user_agent 自定义的UA
     * @param int $port 端口
     * @param int $timeout 超时限制
     * @return mixed
     */
    public function socketPost($url, $data, $user_agent = "", $port = 80, $timeout = 30)
    {
        $url_info = parse_url($url);
        $remote_server = $url_info["host"];
        $remote_path = $url_info["path"];
        $socket = fsockopen($remote_server, $port, $errno, $errstr, $timeout);
        if(!$socket)
        {
            die("$errstr($errno)");
        }

        if($user_agent == "")
        {
            $user_agent = $this->ua_string;
        }

        if (!is_array($data))
        {
            $data = array($data);
        }

        $data = http_build_query($data);

        fwrite($socket, "POST {$remote_path} HTTP/1.0");
        fwrite($socket, "User-Agent: {$user_agent}");
        fwrite($socket, "HOST: {$remote_server}");
        fwrite($socket, "Content-type: application/x-www-form-urlencoded");
        fwrite($socket, "Content-length: " . strlen($data) . "");
        fwrite($socket, "Accept:*/*");
        fwrite($socket, "");
        fwrite($socket, "{$data}");
        fwrite($socket, "");

        $header = "";
        while($str = trim(fgets($socket, 4096)))
        {
            $header .= $str;
        }

        $data = "";
        while(!feof($socket))
        {
            $data .= fgets($socket, 4096);
        }

        return $data;
    }

    // -----------------------

    /**
     * 文件流提交
     *
     * @param string $url 提交地址
     * @param string $data 数据
     * @param string $user_agent 自定义的UA
     * @return mixed
     */
    public function streamPost($url, $data, $user_agent = "")
    {
        if($user_agent == "")
        {
            $user_agent = $this->ua_string;
        }

        if (!is_array($data))
        {
            $data = array($data);
        }

        $data = http_build_query($data);
        $context = array(
                "http" => array(
                        "method" => "POST",
                        "header" => "Content-type: application/x-www-form-urlencoded" . " " . "User-Agent : " . $user_agent . " " . "Content-length: " . strlen($data),
                        "content" => $data
                )
        );
        $stream_context = stream_context_create($context);
        $data = file_get_contents($url, FALSE, $stream_context);
        return $data;
    }

    // -----------------------

    /**
     * 发送请求
     *
     * 本方法通过curl函数向目标服务器发送请求
     *
     * @param string $url 请求地址
     * @return mixed
     */
    public function request($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, !empty($this->ua_string)? $this->ua_string : $_SERVER["HTTP_USER_AGENT"]);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        if (isset($this->cookie_file))
        {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie_file);
        }
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
}