<?php

require_once 'tbapitool.php';

header('Content-Type: text/html; charset=utf-8');

$tb = new TbApiTool;
$shop = $tb->ShopGet('coolwuxing');
if(!$shop) {
	echo $tb->ErrorMsg();
}
else print_r($shop);

$items = $tb->ItemsGet('a大调b小调');
if(!$items) {
	echo $tb->ErrorMsg();
}
else print_r($items);

$item = $tb->ItemGet('12916063598');

if(!$item) {
	echo $tb->ErrorMsg();
}
else print_r($item);

?>