<?php

class OAuth {

    public $paras = array();

    private $config = array(
        'default' => array(
        	'client_id'    => '12575617',
            'client_secret'=> '2a940f761a1986d0161dd9b4a548e730',
            'redirect_uri' => 'http://www.ibaoku.cn',
            'scope'        => 'item,promotion,usergrade',
            'state'        => '1',
            'view'         => 'web',
            'auth_url'     => 'https://oauth.taobao.com/authorize',
            'token_url'    => 'https://oauth.taobao.com/token',
            'logoff_url'   => 'https://https://oauth.taobao.com/logoff'
        ),
        'taobao' => array(
        	//'client_id'    => '12603167',
            //'client_secret'=> '33b9d8efb7ee4ecdf2445e6517bcb819',
        	'client_id'    => '12575617',
            'client_secret'=> '2a940f761a1986d0161dd9b4a548e730',
            'redirect_uri' => 'http://www.ibaoku.cn',
            'scope'        => 'item,promotion,usergrade',
            'state'        => '1',
            'view'         => 'web',
            'auth_url'     => 'https://oauth.taobao.com/authorize',
            'token_url'    => 'https://oauth.taobao.com/token',
            'logoff_url'   => 'https://https://oauth.taobao.com/logoff'
        )
    );

	public function getLoginUrl($path = '') {

	    $p = $this->_buildQueryArray(array('redirect_uri', 'client_id', 'scope', 'state', 'view'));
	    $p['response_type'] = 'code';
	    $p['redirect_uri'] .= $path;
	    return $this->paras['auth_url'].'?'.http_build_query($p);
	}

	public function getToken($code) {

	    $p = $this->_buildQueryArray(array('client_id', 'client_secret', 'redirect_uri', 'scope', 'state', 'view'));
	    $p['grant_type'] = 'authorization_code';
	    $p['code'] = $code;
	    try {
	        return $this->_curlPost($this->paras['token_url'], $p);
	    } catch (Exception $e) {
	        //TODO log
	        return false;
	    }

	}

	public function refreshToken($refresh_token) {

	    $p = $this->_buildQueryArray(array('client_id', 'client_secret', 'scope', 'state', 'view'));
	    $p['grant_type'] = 'refresh_token';
	    $p['refresh_token'] = $refresh_token;

	    try {
	        return $this->_curlPost($this->paras['token_url'], $p);
	    } catch (Exception $e) {
	        //TODO log
	        return false;
	    }

	}

	public function exitLogin($path) {

	    $p = $this->_buildQueryArray(array('client_id', 'redirect_uri', 'view'));
	    $p['redirect_uri'] .= $path;

	    try {
	        return $this->_curlPost($this->paras['token_url'], $p);
	    } catch (Exception $e) {
	        //TODO log
	        return false;
	    }

	}

    public function __construct($platform) {

        if (is_string($platform) && isset($this->config[$platform])) {
            $this->paras = $this->config[$platform];
        } else {
            $this->paras = $this->config['default'];
        }

    }

    private function _buildQueryArray($fields) {

        if (!is_array($fields)) return false;
        $query = array();
        foreach($fields as $one) {
            $query[$one] = $this->paras[$one];
        }
        return $query;

    }

    private function _curlPost($url, $postFields = null)
    {
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_FAILONERROR, false);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	//https 请求
    	if(strlen($url) > 5 && strtolower(substr($url,0,5)) == "https" ) {
    		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    	}

    	if (is_array($postFields) && 0 < count($postFields))
    	{
    		$postBodyString = "";
    		$postMultipart = false;
    		foreach ($postFields as $k => $v)
    		{
    			if("@" != substr($v, 0, 1))//判断是不是文件上传
    			{
    				$postBodyString .= "$k=" . urlencode($v) . "&";
    			}
    			else//文件上传用multipart/form-data，否则用www-form-urlencoded
    			{
    				$postMultipart = true;
    			}
    		}
    		unset($k, $v);
    		curl_setopt($ch, CURLOPT_POST, true);
    		if ($postMultipart)
    		{
    			curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    		}
    		else
    		{
    			curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString,0,-1));
    		}
    	}
    	$reponse = curl_exec($ch);

    	if (curl_errno($ch))
    	{
    		throw new Exception(curl_error($ch),0);
    	}
    	else
    	{
    		$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//    		if (200 !== $httpStatusCode)
//    		{
//    			throw new Exception($reponse,$httpStatusCode);
//    		}
    	}
    	curl_close($ch);
    	return $reponse;
	}

}
?>