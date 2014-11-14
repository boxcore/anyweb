<?php

/*
  ----------------------------------------------------------------------
    shell脚本载入文件

    @author boxcore
  ----------------------------------------------------------------------
*/

// 记录开始运行时间
$GLOBALS['_beginTime'] = microtime(TRUE);

date_default_timezone_set('Asia/Chongqing');

// 分隔符
define( 'DS', DIRECTORY_SEPARATOR );

// 项目根目录路径
define( 'BOMB', dirname(__FILE__) . DS);

// 设置当前请求语言，默认设置为简体中文
$GLOBALS['request']['lang'] = 'zh_cn';

// 载入应用程序配置
require APP . 'conf'.DS.'config.php';
$GLOBALS['system']  = isset($_CONFIGS['system']) ? $_CONFIGS['system'] : array();
$GLOBALS['app']     = isset($_CONFIGS['app']) ? $_CONFIGS['app'] : array();
$GLOBALS['db']      = isset($_CONFIGS['db']) ? $_CONFIGS['db'] : array();

define( 'ENV', $GLOBALS['app']['environment']);

// 载入日志类
require BOMB . 'core'.DS.'Logger.lib.php';

// 载入框架核心函数库
require BOMB . 'core'.DS.'core.fn.php';

// 载入框架数据库操作函数
require BOMB . 'core'.DS.'db.fn.php';

// 载入程序全局函数（程序公用函数库）
require APP . 'funcs'.DS.'app.fn.php';

// 取消自动转义
transcribe();
