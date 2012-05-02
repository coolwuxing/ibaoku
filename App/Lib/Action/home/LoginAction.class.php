<?php
import('@.User.OAuth');

class LoginAction extends Action{

    public function jump() {

        $goto = $_GET['goto'];
        if (empty($goto)) {
            $goto = C('IBAOKU_ENTER').'/';
        }
        $o = new OAuth('taobao');
        //login callback
        //Array (
        //[taobao_user_id] => 50127587
        //[taobao_user_nick] => coolwuxing
        //[re_expires_in] => 0
        //[hra_expires_in] => 1800
        //[expires_in] => 86400
        //[token_type] => Bearer
        //[refresh_token] => 6202009cd61dff062ZZ86b3005ce68b664a8435de39cc2950127587
        //[access_token] => 62026093d6558827fhja881520c8ff94530550fffc8401950127587 )
        if(!empty($_GET['code'])) {
            $json = json_decode($o->getToken($_GET['code']), true);
            //cookie('tb_nick', $json['taobao_user_nick']);
            if (!isset($json['taobao_user_nick'])) {
                session('tb_nick', null);
                session('shop_id', null);
                cookie('access_token', null);
            }
            session('tb_nick', $json['taobao_user_nick']);
            cookie('access_token', $json['access_token'], 1800);

            //如果是档口老板，读取shop id写入cookie
            $shop = D('shop');
            $id = $shop->where('tb_nick="'.$json['taobao_user_nick'].'"')->getField('id');
            if(!empty($id)) {
                //cookie('shop_id', $id);
                session('shop_id', $id);
            }
        }
        redirect(base64_decode($goto));
    }

    public function verify() {

        $rsp = array();

        //已登录，存在session
        if(session('?tb_nick')) {

            $rsp['ret'] = 0;
            $rsp['tb_nick'] = session('tb_nick');
            $rsp['shop_id'] = session('shop_id');

        } else { //session失效，需重新验证

            //cookie中没有token
            $token = cookie('access_token');
            if(empty($token)) {
                $rsp['ret'] = ERR_GENERAL_NOT_LOGIN;
                $rsp['msg'] = 'not login';

            } else { //用此token向top发起请求，获得昵称

                Vendor('taobao.TopSdk');
                $req = new UserGetRequest;
                $req.setFields('nick');
                $c = new TopClient;

                $xml = $c->execute($req, $token);
                if (isset($xml->code)) {
                    $rsp['ret'] = ERR_GENERAL_TOKEN_FAILED;
                    $rsp['msg'] = $xml->sub_code;
                } else {
                    session('tb_nick', $xml->user->nick);
                    //如果是档口老板，读取shop id写入cookie
                    $shop = D('shop');
                    $id = $shop->where('tb_nick="'.$xml->user->nick.'"')->getField('id');
                    if(!empty($id)) {
                        session('shop_id', $id);
                    }
                    $rsp['ret'] = 0;
                    $rsp['tb_nick'] = session('tb_nick');
                    $rsp['shop_id'] = session('?shop_id');
                }
            }
            if ($rsp['ret'] != 0) {
                $o = new OAuth('taobao');
                $rsp['login_url'] = $o->getLoginUrl("/login/jump?goto=".base64_encode($_POST['url']));
            }
        }

        echo json_encode($rsp);
    }
}
?>