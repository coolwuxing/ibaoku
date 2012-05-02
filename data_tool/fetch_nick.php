<?php
$f = fopen("input","r");
while(!feof($f)) {
	$line = fgets($f);
// 初始化一个 cURL 对象
$curl = curl_init();

// 设置你需要抓取的URL
echo "-->{$line}\n";
curl_setopt($curl, CURLOPT_URL, 'http://17zwd.com/ds.asp?id='.$line);

// 设置header
curl_setopt($curl, CURLOPT_HEADER, 1);

// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

// 运行cURL，请求网页
$data = curl_exec($curl);

// 关闭URL请求
curl_close($curl);

if (!$link = mysql_connect('localhost', 'ibaoku', '')) {
    echo 'Could not connect to mysql';
    exit;
}
mysql_query("set names UTF8;");

if (!mysql_select_db('ibaokucn', $link)) {
    echo 'Could not select database';
    exit;
}

// 显示获得的数据
if(preg_match('/ga.asp\?ww=(\S+)/',$data,$m)) {
	print($m[1]."\n");
	$sql="update shop set tb_nick='{$m[1]}' where 17zwd_id={$line};";
	$rst = mysql_query($sql);
	if(!$rst) {
		echo "INSERTFAILED {$line} {$m[1]}\n";
	}
	else echo "SUCC {$line} {$m[1]}\n";
	
}
else echo "failed! {$line}\n";
}
fclose($f);
?>
