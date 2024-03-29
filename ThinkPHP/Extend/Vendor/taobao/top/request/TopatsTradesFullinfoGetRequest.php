<?php
/**
 * TOP API: taobao.topats.trades.fullinfo.get request
 * 
 * @author auto create
 * @since 1.0, 2012-04-14 12:37:27
 */
class TopatsTradesFullinfoGetRequest
{
	/** 
	 * 可以返回taobao.trade.fullinfo.get允许的所有字段。
	 **/
	private $fields;
	
	/** 
	 * 交易订单号tid列表，多个tid之间用半角分号分隔。tid个数的取值范围是：1~100个。由于这个接口限制每个应用的调用量是1万次/天，所以强烈建议采用尽可能多的tid，以取到更多的交易数据。
	 **/
	private $tids;
	
	private $apiParas = array();
	
	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}

	public function getFields()
	{
		return $this->fields;
	}

	public function setTids($tids)
	{
		$this->tids = $tids;
		$this->apiParas["tids"] = $tids;
	}

	public function getTids()
	{
		return $this->tids;
	}

	public function getApiMethodName()
	{
		return "taobao.topats.trades.fullinfo.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->fields,"fields");
		RequestCheckUtil::checkNotNull($this->tids,"tids");
	}
}
