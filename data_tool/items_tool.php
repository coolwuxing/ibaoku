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
	$sql = "select id,tb_nick from shop where id>=684 limit {$begin},100";
	$rst = mysql_query($sql, $link);
	if (!$rst) {
		echo "query failed: {$sql}\n";
		exit;
	}
	$rst_num = 0;
	while ($row = mysql_fetch_row($rst)) {
		$nick = $row[1];
		echo "----Dealing id: {$row[0]} nick: {$nick}----\n";
		$rst_num++;
		echo "-> Query shop {$row[0]} items !\n";

		$items = $tb->ItemsGet($nick);
		if(!$items) {
			echo "shop:".$row[0]."-> ".$tb->ErrorMsg();
			continue;
		}
		sleep(2);

		foreach($items as $item) {
			$item_detail = $tb->ItemGet($item['num_iid']);
			if(!$item_detail) {
				echo "item:".$item['num_iid']."-> ".$tb->ErrorMsg();
				continue;
			}
			$item = array_merge($item, $item_detail);
			print_r($item);
			sleep(2);

			$sql = "insert into item (`tb_num_iid`, `tb_pic_url`, `tb_cid`, `tb_price`, `tb_title`, `tb_volume`, `tb_detail_url`, `tb_props_name`, `tb_created`, `tb_delist_time`, `tb_input_str`, `tb_input_pids`, `tb_num`, `tb_list_time`,`shop_id`,`tb_nick`) values ('{$item['num_iid']}','{$item['pic_url']}', '{$item['cid']}',{$item['price']}, '".addslashes($item['title'])."', {$item['volume']}, '{$item['detail_url']}', '{$item['props_name']}', '{$item['created']}', '{$item['delist_time']}', '{$item['input_str']}', '{$item['input_pids']}', {$item['num']}, '{$item['list_time']}',{$row[0]},'{$nick}'); ";
			if (!mysql_query($sql, $link) && mysql_errno($link) != 1062) {
				$errno = mysql_errno($link);
				$emsg = mysql_error($link);
				echo "query failed {$errno}: {$emsg}\n{$sql}\n";
				exit;
			}

			echo "Updated shop {$row[0]} item {$item['num_iid']} info.\n";
		}
	}
	$begin += 100;
	mysql_free_result($rst);
}
mysql_close($link);

?>