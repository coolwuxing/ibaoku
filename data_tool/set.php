<?php

if (!$link = mysql_connect('localhost', 'ibaoku', '')) {
    echo 'Could not connect to mysql';
    exit;
}
mysql_query("set names UTF8;");

if (!mysql_select_db('ibaokucn', $link)) {
    echo 'Could not select database';
    exit;
}
	$sql="update shop set tb_nick='{$argv[2]}' where 17zwd_id={$argv[1]};";

	$rst = mysql_query($sql);
	if(!$rst) {
		echo "INSERTFAILED {$argv[1]} {$argv[2]}\n";
	}
	else echo "SUCC {$argv[1]} {$argv[2]}\n";
	?>