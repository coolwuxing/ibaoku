<?php
require_once('tbapitool.php');
$str = 
<<<EOF
<?xml version="1.0" encoding="utf-8" ?><item_get_response><item><cid>162104</cid><created>2011-10-10 18:35:27</created>
<detail_url>http://item.taobao.com/item.htm?id=12903878803&amp;spm=2014.12129701.0.0</detail_url>
<input_pids>1632501</input_pids><input_str>2182#</input_str><is_xinpin>false</is_xinpin><list_time>2012-04-02 18:35:27</list_time><nick>hanfeifan617</nick><num>195</num><num_iid>12903878803</num_iid>
<pic_url>http://img01.taobaocdn.com/bao/uploaded/i1/T1k99mXkVpXXbNX9vb_094908.jpg</pic_url><props>1632501:3975942;20667:29444;2915574:4049881;31610:32347404;20669:29541;2917380:27924515;20664:29535;20670:29453;20511:28342;2917619:27418118;1627207:28327;20666:29937;20666:29938;20509:28383;2917721:3269957;21541:111019;10995144:3332237</props><props_name>1632501:3975942:货号:2182#;20667:29444:款式:长袖;2915574:4049881:板型:直筒型;31610:32347404:衣长:中长款(65cm&lt;衣长&le;80cm);20669:29541:领子:立领;2917380:27924515:袖型:常规袖;20664:29535:风格:休闲;20670:29453:图案:格子;20511:28342:质地:纯棉;2917619:27418118:款式细节:多口袋装饰;1627207:28327:颜色分类:酒红色;20666:29937:季节:春季;20666:29938:季节:秋季;20509:28383:尺码:均码;2917721:3269957:适合人群:少女;21541:111019:价格区间:31-70元;10995144:3332237:年份:2011</props_name><title>秋款新特 帽子衬衫 戴帽子的女长袖衬衫2182#</title></item></item_get_response><!--top173158.cm3-->
EOF;
echo "hello world\n";
echo decode_entities_full($str)."\n";
$xml = simplexml_load_string(decode_entities_full($str));
if(!$xml) {
	print_r(libxml_get_last_error());
}
else echo "succ";
?>