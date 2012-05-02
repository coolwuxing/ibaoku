<?php
header('Content-Type: text/html; charset=utf-8');
//$filename = $_GET['f'];
$div_arr = array(
	"slidingList1" => "推荐",
	"slidingList2" => "大西豪",
	"slidingList3" => "女人街",
	"slidingList4" => "西街",
	"slidingList5" => "沙河周边",
	"slidingList6" => "新潮都",
	"slidingList7" => "北城",
	"slidingList8" => "网络品牌",
	
);

if (!$link = mysql_connect('localhost', 'ibaoku', '')) {
    echo 'Could not connect to mysql';
    exit;
}
mysql_query("set names UTF8;");

if (!mysql_select_db('ibaokucn', $link)) {
    echo 'Could not select database';
    exit;
}

if (file_exists('17zwd_data.xml')) {
	$xml = simplexml_load_file('17zwd_data.xml');
	$i = 0;
	$sec = "";
	foreach($xml->children() as $div) {
		$i++;
		echo '<h2>'.$div['id']."</h2><br/>\n";
		if($i == 1) continue;
		foreach($div->ul as $ul) {
			foreach ($ul->li as $li) {
				if ($li['id'] == 't_3') {
					echo '<h3>'.$li."</h3>\n";
					$sec = $li;
				}
				$title = $li->a['title'];
				if (empty($title)) {
					$title = $li->a;
					if (empty($title)) {
						continue;
					}
					echo $title.' -> '.$li->a['href']."<br/>\n";
					continue;
				}
				if (preg_match('/^([^\[]+)\[([^\|]+)\|([^\]]+)\][^\d]+(\d+)_QQ:(\d+)[^=]+=(\d+)/',
					$title.$li->a['href'],$matches))
				{
					echo <<<EOF
addr: {$matches[1]} price: {$matches[2]} name: {$matches[3]} 
mobile: {$matches[4]} qq: {$matches[5]} 17id: {$matches[6]}<br>
EOF;
echo "\n";
					continue;
					echo "-->fetch nick...\n";
					
// 初始化一个 cURL 对象
$curl = curl_init();

// 设置你需要抓取的URL
curl_setopt($curl, CURLOPT_URL, 'http://17zwd.com/ds.asp?id='.$matches[6]);

// 设置header
curl_setopt($curl, CURLOPT_HEADER, 1);

// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

// 运行cURL，请求网页
$data = curl_exec($curl);

// 关闭URL请求
curl_close($curl);

// 显示获得的数据
if(preg_match('/ga.asp\?ww=(\S+)/',$data,$m)) {
	print("<-- ".$matches[6]." ".$m[1]."\n");
}
else
{
	print("NICKFAILED ".$matches[6]."\n");
}

					//save to db
					$sql = <<<EOF
						insert into `shop` (`div`,section,addrno,discount,name,mobile,qq,17zwd_id,tb_nick)
						values({$i},'{$sec}','{$matches[1]}','{$matches[2]}','{$matches[3]}',
						'{$matches[4]}',{$matches[5]},{$matches[6]},'{$m[1]}');
EOF;
					$rst = mysql_query($sql);
					if (!$rst) {
						exit( "DB query error: {$sql} <br>".mysql_error());
					}
					else print("insert succ.\n");
				}
			}
		}
	}
} else {
	exit('Failed to open file.');
}
?>