<?php

require_once "tbapitool.php";

header('Content-Type: text/html; charset=utf-8');

if (!$link = mysql_connect('localhost', 'ibaoku', '')) {
	echo 'Could not connect to mysql';
	exit;
}
mysql_query("set names UTF8;");

if (!mysql_select_db('ibaokucn', $link)) {
	echo 'Could not select database';
	exit;
}
$tb = new TbApiTool;

$begin = 0;
$rst_num = 101;
while ($rst_num >= 100) {
	$sql = "select id,tb_nick,tb_sid from shop where status=0 limit {$begin},100";
	$rst = mysql_query($sql, $link);
	if (!$rst) {
		echo "query failed: {$sql}\n";
		exit;
	}
	$rst_num = 0;
	while ($row = mysql_fetch_row($rst)) {
/*
		if (empty($row[2])) {
			$nick = iconv('GBK', 'UTF-8', urldecode($row[1]));
		} else $nick = $row[1];
*/
		$nick = $row[1];		
		echo "----Dealing id: {$row[0]} nick: {$nick}----\n";
		$rst_num++;
		echo "-> Query shop {$row[0]} info:\n";
		$shop = $tb->ShopGet($nick);
		if (!$shop) {
			echo $tb->ErrorMsg();
			exit;
		}
		else print_r($shop);
		$sql = "update shop set tb_nick='{$nick}',tb_sid='{$shop['sid']}',tb_cid='{$shop['cid']}',tb_title='{$shop['title']}',tb_desc='{$shop['desc']}',tb_bulletin='{$shop['bulletin']}',tb_pic_path='{$shop['pic_path']}',tb_created='{$shop['created']}',status=1 where id={$row[0]};";
		if (!mysql_query($sql, $link)) {
			echo "query {$row[0]} failed: {$sql}\n";
			exit;
		}
		echo "Updated shop {$row[0]} info.\n";
	}
	mysql_free_result($rst);
}
mysql_close($link);

?>