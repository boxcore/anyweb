<?php if ( !defined('BOMB') ) exit('No direct script access allowed');

/**
 * ========================================================================
 * 
 * 默认公共函数集
 *
 * ========================================================================
 */

/**
 * 资源链接
 *
 * @author boxcore
 * @date   2014-09-17
 * @param  string     $uri 资源链接参数
 * @return string     带域名的URL
 */
if( !function_exists('src_url') ) {
    function src_url($uri=''){
        $url = conf( 'app', 'site_domain' ).conf( 'app', 'src_url' ).$uri;
        return $url;
    }
}

/**
 * 判断REQUEST值是否存在
 *
 * @author boxcore
 * @date   2014-09-17
 * @param  string     $pram $_REQUEST键值
 * @return string/boolean
 */
function is_input($pram) {
    if(isset($_REQUEST[$pram]) && !empty($_REQUEST[$pram])){
        return $_REQUEST[$pram];
    }else{
        return false;
    }
}

/**
 * 获取input去掉html并转义
 * @param type $key
 * @return type
 */
function s_input($key){
    return strip_tags(urldecode(input($key)));
}


