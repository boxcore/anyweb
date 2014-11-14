<?php if ( !defined('BOMB') ) exit('No direct script access allowed');

/**
 * ========================================================================
 * 
 * 采集函数集
 *
 * ========================================================================
 */

/**
 * 模拟浏览器请求
 * @param string $url
 * @return string
 */
function http_client_request( $url ) {

    $ch = curl_init(); // 初始化
    curl_setopt ( $ch, CURLOPT_URL, $url ); // 要访问的网址
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); //不直接输入内容
    curl_setopt( $ch,CURLOPT_TIMEOUT, 10 ); //设置超时为10秒
    curl_setopt( $ch, CURLOPT_REFERER, $url ); // 设置来路 
    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)" );

    $res = curl_exec($ch); // 保持结果
    curl_close ($ch); //关闭
    return $res;
}

/**
 * 生成前导零数字字符串
 *
 * @author boxcore
 * @date   2014-06-02
 * @param  array      $arr  要添加前导零的数字
 * @param  integer    $leng 数字长度
 * @return array            添加前导零后的数字字符串
 */
function prefix_zero($arr = array() , $leng=0) {
    if( $leng>1 && is_array($arr) ){
        $base_num = pow(10, $leng);
        $list_num = array();
            
        foreach($arr as $v){
            $str = $base_num + intval($v);
            $list_num[] = substr( $str, 1 );
        }
        return $list_num;
    }else{
        return $arr;
    }
}

/**
 * [get_link_list description]
 *
 * @author boxcore
 * @date   2014-06-02
 * @param  [type]     $rule [description]
 * @return [type]           [description]
 */
function get_link_list($rule){
    $allow_type = array('batch_link', 'text_link', 'rss_link', 'mixed');
    $type = isset($rule['list_type']) ? $rule['list_type'] : '';
    $result = array();

    if( in_array($type, $allow_type) ) {
        if( ($type == 'text_link') || ($type == 'mixed') ) {
            $text_link = $rule['text_link'];
            $list['text_rule'] = !empty($text_link) ? $text_link : array();
            $result = array_merge($result, $list['text_rule']);
        }
        if( ($type == 'batch_link') || ($type == 'mixed') ) {
            $batch_rule = !empty($rule['batch_link']) ? $rule['batch_link'] : array();
            $list['batch_link'] = get_batch_link($batch_rule['regexurl'], $batch_rule['start_id'], $batch_rule['end_id'], $batch_rule['id_len']);
            $result = array_merge($result, $list['batch_link']);
        }
        if( ($type == 'rss_link') || ($type == 'mixed') ) {
            $rss_link = $rule['rss_link'];
            $list['rss_link'] = !empty($rss_link) ? $rss_link : array();
            $result = array_merge($result, $list['rss_link']);
        }
    }

    return $result;
}

/**
 * 获取批量链接
 *
 * @author boxcore
 * @date   2014-06-02
 * @param  string     $link     批量链接格式
 * @param  int        $start_id 开始id
 * @param  int        $end_id   结束id
 * @param  int        $id_len   数字固定长度
 * @return array
 */
function get_batch_link($link, $start_id, $end_id, $id_len) {
    $link_list = array();
    $num_list = array();
    $start_id = intval($start_id);
    $end_id = intval($end_id);
    $id_len = intval($id_len);

    if( $start_id && $end_id && ($end_id > $start_id) && ($id_len>=1) ) {
        $tmp_num = range($start_id, $end_id);
        $num_list = prefix_zero( $tmp_num, $id_len );

        foreach($num_list as &$v){
            $link_list[] = str_replace('(*)', $v, $link);
        }
    }

    return $link_list;
}

/**
 * 从html中获取链接
 *
 * @author boxcore
 * @date   2014-06-03
 * @param  [type]     $content [description]
 * @param  [type]     $area    [description]
 * @return [type]              [description]
 */
function get_content_url_list($content, $area ){
    $allow_type = array('regex', 'query', 'xpath', 'none');
    $type = $area['match_type'];
    $content_links = array();
    $content = trim($content);
    $links = array();
    $result = array();

    if(  in_array($type, $allow_type) ){
        if ($type == 'regex') {
            preg_match($area[$type]['rules'], $content, $out_content);
            $content = $out_content[1];
        }

        elseif ($type == 'query') {

        }

        elseif ($type == 'xpath') {
            
        }

        // preg_match_all('#<a(.*?)href=["\'](.*?)["\'](.*?)>#i', $content, $out_links);
        // $links = $out_links[2];

        preg_match_all( $area['preg_links'], $content, $match );
        // print_r($match);
        $result = ! empty( $match[1] ) ? $match[1] : array();
        $result = array_unique( $result );

        $domain = trim($area['prex_domain']);
        if($domain){
            foreach($result as &$v){
                $v = $domain.$v;
            }
        }
    }

    return $result;
}



function get_content( $content, $area ){
    $allow_type = array('regex', 'query', 'xpath', 'none');
    $type = $area['type'];

    $content = trim($content);
    if( isset($area['replace']['pattern']) && !empty($area['replace']['pattern']) ){
        $content = preg_replace( $area['replace']['pattern'], $area['replace']['replacement'], $content);
    }

    $result = array();

    if(  in_array($type, $allow_type) ){
        if ($type == 'regex') {

            if(is_array($area['regex']['content'])){
                foreach( $area['regex']['content'] as $k=>$v) {
                    $match = array();
                    preg_match( $v, $content, $match );
                    $tmp_content[$k] = $match[1];
                }
                foreach($tmp_content as &$vc){
                    $vc = trim($vc);
                }
                $_save['content'] = join('<br />',$tmp_content);
            }else{
                $match = array();
                preg_match( $area['regex']['content'], $content, $match );
                $_save['content']  = $match[1];
            }

            if( isset($area['regex']['pic']) && !empty($area['regex']['pic']) ){
                $match = array();
                if( preg_match( $area['regex']['pic'], $content, $match ) ) {
                    $_save['pic'] = $match[1];
                }else{
                    $_save['pic'] = '';
                }

            }
        }

        elseif ($type == 'query') {

        }

        elseif ($type == 'xpath') {
            
        }

        if( isset($_save['content']) && !empty($_save['content']) ) {
            $result = $_save;
        }
    }

    return $result;
}


/**
 * 记录采集日志
 * @param string $url
 * @param string $msg
 */
function insert_log( $url, $msg ) {

    return insert( 'task_log', array( 'url' => $url, 'msg' => $msg ) );
}

/**
 * 添加 url 标识为已抓取过的
 * @param array $urls
 * @param string $type
 * @param boolean   $shell_output 是否打印shell通知
 * @param string    $table_name 表名,默认为`task_url`表
 * @return bool
 */
function insert_urls( $urls, $task_list_id, $shell_output=false, $table_name = 'task_url' ) {

    $sql    = 'SELECT 1 FROM `'.$table_name.'` WHERE `hash` = ?s LIMIT 1';
    $result = 0;
    if( empty( $urls ) ) { return; }

    foreach( $urls as $v ) {

        $hash = md5( $v );
        if( get_var( prepare( $sql, array( $hash ) ) ) != '1' ) {
            if($shell_output){ system("echo -e '\\033[32m保存内容链接:\\033[0m \\033[34m".$v."\\033[0m'"); }
            $inserts = array(
                'url'   => $v,
                'hash'  => $hash,
                'task_list_id'  => $task_list_id,
            );

            if( insert( $table_name, $inserts ) !== FALSE ) {

                $result ++;
            }
        }else{
            if($shell_output){ system("echo -e '\\033[31m重复内容链接:\\033[0m \\033[34m".$v."\\033[0m'"); }
        }
    }
    close_db();

    return $result;
}


/**
 * 添加 url 标识为已抓取过的
 */
function insert_ec_urls( $data, $task_list_id=0, $shell_output=false, $table_name = 'task_url' ) {

    $sql    = 'SELECT 1 FROM `'.$table_name.'` WHERE `hash` = ?s LIMIT 1';
    $result = 0;
    if( empty( $data ) ) { return; }

    foreach( $data as $v ) {

        $hash = md5( $v['url'] );
        if( get_var( prepare( $sql, array( $hash ) ) ) != '1' ) {
            if($shell_output){ system("echo -e '\\033[32m保存内容链接:\\033[0m \\033[34m".$v['url']."\\033[0m'"); }
            $inserts = array(
                'url'   => $v['url'],
                'hash'  => $hash,
                'task_list_id'  => $task_list_id,
                'thumb_img_org' => $v['thumb_img_org'],
            );

            if( insert( $table_name, $inserts ) !== FALSE ) {

                $result ++;
            }
        }else{
            if($shell_output){ system("echo -e '\\033[31m重复内容链接:\\033[0m \\033[34m".$v['url']."\\033[0m'"); }
        }
    }
    close_db();

    return $result;
}

/**
 * 获取链接信息
 *
 * @author boxcore
 * @date   2014-11-13
 * @param  [type]     $url [description]
 * @return [type]          [description]
 */
function url_info($url) {
    $info = array();
    $url = trim(urldecode($url));

    if( !preg_match("#^(https?://)#i", $url) ){
        $url = 'http://'.$url;
    }

    $url_regx = "#^(https?)\://([a-z0-9A-Z\_\-\.]+)/?(.*?)$#i";
    if(preg_match($url_regx, $url, $matches)){
        $info['scheme'] = $matches[1];
        $info['host'] = $matches[2];
        $info['uri'] = $matches[3];
        $info['url'] = $url;
        return $info;
    }
    return null;
}
