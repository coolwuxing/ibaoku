<?php
class IndexAction extends Action{

    public function index() {

        import('@.User.OAuth');
        $o = new OAuth('taobao');
        $this->assign("login_url", $o->getLoginUrl());
        $this->assign("ad_item_new", generate_ads_items('home', 10));
        $this->assign("ad_item_hot", generate_ads_items('home', 10));

        $this->display();
    }
}
?>