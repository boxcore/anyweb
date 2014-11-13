<?php if ( !defined('BOMB') ) exit('No direct script access allowed');

class _BaseControl extends _Control
{
    public function __construct() {
        parent::__construct();
    }

    /**
     * 主渲染
     *
     * @author boxcore
     * @date   2014-11-13
     * @param  string     $tpl    模板名称
     * @param  array      $data   渲染数据
     * @param  string     $layout 默认模板
     * @return
     */
    public function layout( $tpl, $data = array(), $layout = 'main.layout') {

        $data['tpl'] = $tpl;
        
        render( $layout, $data );
    }
}