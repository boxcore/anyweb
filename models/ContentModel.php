<?php if ( !defined('BOMB') ) exit('No direct script access allowed');

/**
 * 获取采集内容模型
 *
 */

class ContentModel {

    public function __construct() {

    }

    /**
     * 获取目标链接列表
     *
     * @author boxcore
     * @date   2014-10-23
     * @param  array      $configs    配置条件
     * @param  string     $limit
     * @param  string     $table_name 表名
     * @return array|bool
     */
    public function getUrlList( $configs, $limit='LIMIT 20 ', $table_name = 'task_url' ){
        $where = $this->__getWhere($configs);
        $sql = 'SELECT * FROM '.$table_name.' '.$where.
                'ORDER BY id '.$limit;
        $data = get_data($sql);
        
        return $data;
    }

    /**
     * 过滤条件
     *
     * @author boxcore
     * @date   2014-10-23
     * @param  array     $configs 过滤条件
     * @return string
     */
    private function __getWhere( $configs ){
        $where = 'WHERE TRUE ';

        if(isset($configs['need_push'])){
            $where .= prepare('AND `need_push` = ?s ', array($configs['need_push']));
        }

        if(isset($configs['url'])){
            $where .= prepare('AND `url` = ?s ', array($configs['url']));
        }

        if(isset($configs['task_list_id'])){
            $where .= prepare('AND `task_list_id` = ?i ', array($configs['task_list_id']));
        }

        if(isset($configs['type'])){
            $where .= prepare('AND `type` = ?i ', array($configs['type']));
        }

        if(isset($configs['start_goods_id'])){
            $where .= prepare('AND `goods_id` >= ?i ', array($configs['start_goods_id']));
        }

        if( isset($configs['price']) ){
            if($configs['price']){
                $where .= 'AND `price` > 0 ';
            } else {
                $where .= 'AND `price` = 0 ';
            }
        }

        if(isset($configs['date'])){
            $where .= prepare('AND `created_time` >= ?s AND `created_time` <= ?s ', array( $configs['date']." 00:00:00", $configs['date']." 23:59:59"));
        }

        return $where;
    }
}