<?php if ( !defined('BOMB') ) exit('No direct script access allowed');

class TaskModel {
    public function __construct() {

    }

    public function getList($configs = array(), $limit = '') {
        $_order = ' ORDER BY `id` DESC ';
        $_where = $this->__getWhere($configs);
        $sql = 'SELECT * FROM `task_list` '. $_where . $_order . $limit;
        $data = get_data($sql);
        return $data;
    }

    /**
     * 通过标记名获取ID
     *
     * @author boxcore
     * @date   2014-09-17
     * @param  string     $mark 标记
     * @return int        task_id
     */
    public function getIdByMark($mark='') {
        $id = 0;
        $mark = trim($mark);
        if( !empty($mark) ){
            $sql = prepare('SELECT `id` FROM `task_list` WHERE `mark` = ?s LIMIT 1', array($mark) );
            $id = get_var($sql);
        }

        return $id;
    }

    /**
     * 获取组装条件
     *
     * @author boxcore
     * @date   2014-06-02
     * @param  array      $configs [description]
     * @return [type]              [description]
     */
    private function __getWhere($configs = array() ) {
        $where = ' WHERE 1=1 ';

        if( isset($configs['task_id']) && !empty($configs['task_id']) ) {
            $where .= " AND `id`={$configs['task_id']} ";
        }

        if( isset($configs['task_ids']) && !empty($configs['task_ids']) ) {
            $where .= " AND `id` IN( {$configs['task_ids']} ) ";
        }

        if ( isset($configs['cat_id']) && !empty($configs['cat_id']) ) {
            $where .= prepare(' AND `cat_id` = ?i ', array( $configs['cat_id'] ) );
        }

        return $where;
    }
}