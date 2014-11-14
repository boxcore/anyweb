<?php if ( !defined('BOMB') ) exit('No direct script access allowed');

require APP . 'controls/BaseControl.php';
require APP . 'funcs/spider.fn.php';
require APP . 'models/HttpHelperModel.php';
require APP . 'et/iplocation/IpLocation.class.php';


/**
 * 
 */

class IndexControl extends _BaseControl
{
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data['meta'] = array(
            'title'       => '首页',
            'keywords'    => '关键字',
            'description' => 'desc',
        );
        $this->layout('index',$data);
    }

    public function httpHelper() {
        $url = input('url');

        $data = array(
            'flag' => false,
            'message' => '未知错误',
        );
        $url_info = url_info($url);

        if(!empty($url_info)){
            $data['url_info'] = $url_info;
            $data['flag'] = true;
            $data['head_info'] = @get_headers($url_info['url'], 1);
            $data['content'] = HttpHelperModel::httpGet($url_info['url']);
            $data['ip'] = gethostbyname($url_info['host']);

            $IpLocation = new IpLocation();
            $ip_info = $IpLocation->getlocation($data['ip']);
            $data['ip_info']['country'] = iconv("GB2312", "UTF-8", $ip_info['country']);
            $data['ip_info']['area'] = iconv("GB2312", "UTF-8", $ip_info['area']);
        }else{
            $data['message'] = 'URL不正确';
        }
        

        $data['meta'] = array(
            'title'       => "“{$url_info['host']}”的HTTP请求分析",
            'keywords'    => '关键字',
            'description' => 'desc',
        );
        $this->layout('httpHelper',$data);

    }
}