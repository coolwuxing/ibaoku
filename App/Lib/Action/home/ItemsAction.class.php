<?php
class ItemsAction extends Action{
    public function _empty() {
        $this->index();
    }
    public function info() {
        header('Content-Type: text/html; charset=utf-8');
        $shop_id = $_GET["id"];
        $shop = M('shop');
        if (empty($shop_id) || !$shop->find($_GET["id"])) {
            $this->index();
        }

        //显示店铺相关信息
        echo $shop->tb_nick."\n";

        $mItem = M('item');
        $itemList = $mItem->page('1,3')->where('shop_id='.$shop->id)->select();
        if ($itemList) {
            //显示商品列表
		    print_r ($itemList);
        }
        else {
            $this->index();
        }
    }

    public function div() {
        $div_id = $_GET['id'];
        $sec_id = $_GET['sec'];
        $p = $_GET['p'];
        if(empty($div_id)) $div_id = 2;
        if(empty($sec_id)) $sec_id = 0;
        if(empty($p)) $p = 1;
        $mItem = M('item');
        import('ORG.Util.Page');
        $count = $mItem->join(' shop on item.shop_id = shop.id')->
            where('shop.`div`='.$div_id)->count();
        $Page = new Page($count, 16);
        $Page->setConfig('header', '个宝贝');
        $page_show = $Page->show();
        $itemList = $mItem->join(' shop on item.shop_id = shop.id')->
            page($p.',16')->where('shop.`div`='.$div_id)->select();

        $this->assign("div_name", $div_id);
        $this->assign("items", $itemList);
        $this->assign("page", $page_show);
    }

    public function cat() {
        $pcid = $_GET["cat"];
        if (empty($pcid)) $pcid = '16'; //girls
        $p = $_GET["p"];
        if(empty($p)) $p = 1;

        $mItem = M('item');
        import('ORG.Util.Page');
        $count = $mItem->where('tb_parent_cid='.$pcid)->count();
        $Page = new Page($count, 16);
        $Page->setConfig('header', '个宝贝');
        $page_show = $Page->show();
        $itemList = $mItem->page($p.',16')->where('tb_parent_cid='.$pcid)->select();

        $list_type = "cat";
        $this->assign('list_type', $list_type);
        $data['cat'] = $pcid;
        //TODO name config auto read
        $data['cat_name'] = $pcid;
        $this->assign('data', $data);
        $this->assign("items", $itemList);
        $this->assign("page", $page_show);
        $this->assign("ad_item", generate_ads_items('cat', 2));

        $this->display('list');
    }

    public function subcat() {
        $cid = $_GET["cat"];
        if (empty($cid)) $cid = '50010850'; //girls
        $p = $_GET["p"];
        if(empty($p)) $p = 1;

        $mItem = M('item');
        import('ORG.Util.Page');
        $count = $mItem->where('tb_cid='.$cid)->count();
        $Page = new Page($count, 16);
        $Page->setConfig('header', '个宝贝');
        $page_show = $Page->show();
        $itemList = $mItem->page($p.',16')->where('tb_cid='.$cid)->select();

        $this->assign('list_type', 'subcat');
        $data['subcat'] = $cid;
        //TODO name config auto read
        $data['cat_name'] = $cid;
        $this->assign('data', $data);
        $this->assign("items", $itemList);
        $this->assign("page", $page_show);
        $this->assign("ad_item", generate_ads_items('cat', 2));

        $this->display('list');
    }

    public function shop() {
        $shop_id = $_GET["id"];
        $p = $_GET["p"];
        if(empty($p)) $p = 1;
        $m_shop = M('shop');
        $shop = $m_shop->find($_GET["id"]);
        if (empty($shop_id) || !$shop) {
            R('Index/index');
        }
        $div = M('div');
        $div->field('name')->find($shop->div);

        $mItem = M('item');
        import('ORG.Util.Page');
        $count = $mItem->where('shop_id='.$shop['id'])->count();
        $Page = new Page($count, 16);
        $Page->setConfig('header', '个宝贝');
        $page_show = $Page->show();
        $itemList = $mItem->page($p.',16')->where('shop_id='.$shop['id'])->select();

        $list_type = "shop";
        $this->assign("list_type", $list_type);
        $this->assign("shop", $shop);
        $this->assign("sec_id", 1);
        $this->assign("div_name", $div->name);
        $this->assign("items", $itemList);
        $this->assign("page", $page_show);
        $this->assign("ad_item", generate_ads_items('cat', 2));

        $this->display('list');
    }
}
?>