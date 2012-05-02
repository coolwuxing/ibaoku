<?php
class EmptyAction extends Action{
    public function _empty() {
        $this->assign('path', $_SERVER['PATH_INFO']);
        $this->display('./App/Tpl/404.html');
    }
}
?>