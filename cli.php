<?php
//调试
//define('APP_DEBUG', true);
//define('MODE_NAME', 'cli');

//定义项目名称和路径
define('APP_NAME', 'App');
define('APP_PATH', './App/');

//putenv('PATH_INFO=/tool/'.$argv[1]);
//echo "".getenv('PATH_INFO');
$_SERVER["PATH_INFO"] = '/tool/'.($argc>0?$argv[1]:'');
$_SERVER["REQUEST_URI"] = '/tool/'.($argc>0?$argv[1]:'');

// 加载框架入口文件
require( "ThinkPHP/ThinkPHP.php");

?>
