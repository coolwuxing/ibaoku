<?php
return array(
//'配置项'=>'配置值'

//不区分大小写
'URL_CASE_INSENSITIVE' => true,

//分组设置
'APP_GROUP_LIST' => 'home,admin,tool',
'DEFAULT_GROUP'  => 'home',

//扩展配置
//'LOAD_EXT_CONFIG' => '',
//自动加载类库
//'APP_AUTOLOAD_PATH' =>'@.Common,@.Tool',
'LOAD_EXT_FILE' => 'errdefine',

'IBAOKU_ENTER' => '',
//db
'DB_DSN' => 'mysql://ibaoku:@localhost:8889/ibaokucn',
'DB_PREFIX' => '',

//ThinkPHP支持URL路由功能，要启用路由功能，需要设置URL_ROUTER_ON 参数为true
'URL_ROUTER_ON'=>true,
//路由定义
'URL_ROUTE_RULES'=> array(
    'tool/shop/info/:id' => 'tool/shop/info',
    'home/shop/:id' => 'home/shop/index',
    'shop/:id' => 'home/shop/index',
),

);
?>