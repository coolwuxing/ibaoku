<?php
class IndexAction extends Action{
    public function index() {
        $this->assign('hello','admin-index');
        $this->display();
    }
}
?>