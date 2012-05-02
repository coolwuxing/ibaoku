<?php
class ShopAction extends Action{
    public function index() {
        echo "shop tools: [ info ]\n";
    }
    public function info() {
        echo "id: ".$_GET["id"]."\n";
        $shop = D('shop');
        $shop->find($_GET["id"]);
        echo $shop->tb_nick."\n";
        import('@.taobao.TbTestApi');
        $tbapi = new TbTestApi;

		$items = $tbapi->ItemsGet($shop->tb_nick);
		if(!$items) {
			echo $tbapi->ErrorMsg()."\n";
			continue;
		}
		print_r ($items);
    }

    public function genNav(){
        $div_list = array();
        $div_list[0]["name"] = "首页";
        $m_shop = M('shop');
        $shop_list = $m_shop->field('id,name,div,section,addrno,mobile,qq')->order('`div` asc, section asc, addrno asc')->select();
        if (!$shop_list) {
            echo 'query shops failed.';
            return;
        }
        $last_div = '';
        $last_sec = '';
        $i = 0; $j = 0; $k = 0;
        $m_div = M('div');
        foreach($shop_list as $shop) {
            if($shop['div'] != $last_div) {
                $i++; $j = 0; $k = 0;
                $m_div->field('name')->find($shop['div'] );
                $div_list[$i]["name"] = $m_div->name;
                $div_list[$i]["id"] = $shop['div'];
                $div_list[$i]["sec"] = array();
                $div_list[$i]["sec"][0]["name"] = $shop['section'];
                $div_list[$i]["sec"][0]["shop"] = array();
                $last_div = $shop['div'];
                $last_sec = $shop['section'];
            }
            if($shop['section'] != $last_sec) {
                $j++; $k = 0;
                $div_list[$i]["sec"][$j]["name"] = $shop['section'];
                $div_list[$i]["sec"][$j]["shop"] = array();
                $last_sec = $shop['section'];
            }
            $div_list[$i]["sec"][$j]["shop"][$k] = $shop;
            $k++;
        }

        $this->assign('div_list', $div_list);
        $this->display('nav');
    }

    public function trimsec() {
        $m_shop = M('shop');
        $shop_sections = $m_shop->Distinct(true)->field('section')->select();
        foreach($shop_sections as $section) {
            echo "Deal '{$section['section']}' ...\n";
            $trimed_sec = trim($section['section']);
            $data['section'] = $trimed_sec;
            $m_shop->where('section="'.$section['section'].'"')->save($data);
        }
    }

    //删除db中的重复项
    public function rmdup() {
        $m_shop = M('shop');
        $shop_list = $m_shop->field("id,tb_nick,17zwd_id")->where("id > 981")->select();
        if (!$shop_list) {
            echo 'select failed.'."\n";
        }
        foreach($shop_list as $shop) {
            echo "Deal '{$shop["id"]}' : \n";
            $dupshop = $m_shop->where('tb_nick="'.$shop['tb_nick'].'" and 17zwd_id="'.$shop['17zwd_id'].'"')->select();
            if (!$dupshop) {
                echo 'find tb_nick failed'."\n";
                continue;
            }
            if(count($dupshop) > 1) {
                echo "duplicated id, remove it! \n";
                $m_shop->where('id='.$shop['id'])->delete();
                echo "remove items too\n";
                $m_item = M('item');
                $m_item->where('shop_id = '.$shop["id"])->delete();
            }
        }
    }

    //生成分类表
    public function genCatsNav() {
        //topsdk
        Vendor('taobao.TopSdk');
        $req = new ItemcatsGetRequest;
        $c = new TopClient;

        //读取db中所有商品的cid
        $m_item = M('item');
        $rst = $m_item->Distinct(true)->field('tb_cid')->select();
        $order_by_cnt_cids = array();
        foreach($rst as $one) {
            $db_cid_cnt = $m_item->where('tb_cid='.$one['tb_cid'])->count();
            $cid_piece['cid'] = $one['tb_cid'];
            $cid_piece['cnt'] = $db_cid_cnt;
            array_push($order_by_cnt_cids, $cid_piece);
        }
        usort($order_by_cnt_cids, 'item_cats_compare');
        //print_r($order_by_cnt_cids);
        $cids = array();
        $parent_cids = array();
        $all_cats = array();
        $i = 0;
        $cid_cnt = count($rst);

        //每20个为一组去调用topsdk取得分类名称，以及上级分类id
        foreach($order_by_cnt_cids as $one) {
            array_push($cids, $one['cid']);
            $i++;
            if ($i > 30) break;
            if ($i % 20 == 0 || $i >= $cid_cnt || $i >= 30/*length*/)
            {
                $req->setCids(join(',', $cids));

                $rsp = $c->execute($req);
                if (isset($rsp->code)) {
                    halt('exec top request failed:'.$rsp->msg);
                    return;
                }
                foreach ($rsp->item_cats->item_cat as $cat) {
                    $str_pcid = trim((string)$cat->parent_cid);
                    $str_name = trim((string)$cat->name);
                    $str_cid = trim((string)$cat->cid);

                    if (!is_array($all_cats[$str_pcid])) $all_cats[$cat->parent_cid] = array();
                    if (!is_array($all_cats[$str_pcid]['child'])) $all_cats[$str_pcid]['child']=array();

                    $thiscat = array();
                    $thiscat["name"] = $str_name;
                    $thiscat["cid"] = $str_cid;

                    $db_cid_cnt = $m_item->where('tb_cid='.$str_cid)->count();

                    $thiscat["cnt"] = $db_cid_cnt;

                    array_push($all_cats[$str_pcid]['child'], $thiscat);
                    array_push($parent_cids, $str_pcid);
                }
                $cids = array();
                if($i >= 30) {
                    break;
                }
            }
        }
        $parent_cids = array_unique($parent_cids);

        //父分类的名字
        $cid_cnt = count($parent_cids);
        $i = 0;
        $cids = array();
        while ($one = current($parent_cids)) {
            array_push($cids, $one);
            $i++;

            if ($i % 20 == 0 || $i >= $cid_cnt) {
                $req->setCids(join(',', $cids));
                $rsp = $c->execute($req);
                if (isset($rsp->code)) {
                    halt('exec top request failed:'.$rsp->msg);
                    return;
                }
                foreach ($rsp->item_cats->item_cat as $cat) {
                    $str_name = trim((string)$cat->name);
                    $str_cid = trim((string)$cat->cid);
                    $all_cats[$str_cid]['name'] = $str_name;
                    $db_cid_cnt = $m_item->where('tb_parent_cid='.$str_cid)->count();
                    $all_cats[$str_cid]['cnt'] = $db_cid_cnt;
                    $all_cats[$str_cid]['cid'] = $str_cid;
                }
                $cids = array();
            }
            next($parent_cids);
        }
        usort($all_cats, 'cats_child_compare');

        $i = 0;
        $cats_cnt = count($all_cats);
        $rst_cats = array();
        $single_cats = array();
        for ($i = 0; $i < $cats_cnt; $i++) {
            if (count($all_cats[$i]['child']) == 1) {
                array_push($single_cats, $all_cats[$i]);
                continue;
            }
            $rst_cats[$i] = $all_cats[$i];
        }

        $this->assign('cats', $rst_cats);
        $this->assign('single_cats', $single_cats);
        $this->display('cats_nav');
    }
}
?>