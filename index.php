<?php
//调试
define('APP_DEBUG', true);

//定义项目名称和路径
define('APP_NAME', 'App');
define('APP_PATH', './App/');

//topsdk config
define("TOP_SDK_WORK_DIR", "./App/Runtime/topsdk/");
define("TOP_SDK_DEV_MODE", true);

// 加载框架入口文件
require( "ThinkPHP/ThinkPHP.php");

?>
