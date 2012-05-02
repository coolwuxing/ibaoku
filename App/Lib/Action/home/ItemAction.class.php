<?php

class ItemAction extends Action{
    public function _empty() {
        $this->index();
    }

    public function index() {
        $item_id = $_GET['id'];
        if(empty($item_id)) $item_id = '12961';

        $mItem = M('item');
        if(!$mItem->find($item_id)) {
            halt('server error.');
        }

        $data['url'] = $mItem->tb_detail_url;
        $this->assign('data', $data);
        $this->display();
    }
}