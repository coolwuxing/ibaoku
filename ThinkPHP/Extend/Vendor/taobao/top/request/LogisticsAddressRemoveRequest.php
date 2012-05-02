<?php
/**
 * TOP API: taobao.logistics.address.remove request
 * 
 * @author auto create
 * @since 1.0, 2012-04-14 12:37:27
 */
class LogisticsAddressRemoveRequest
{
	/** 
	 * 地址库ID
	 **/
	private $contactId;
	
	private $apiParas = array();
	
	public function setContactId($contactId)
	{
		$this->contactId = $contactId;
		$this->apiParas["contact_id"] = $contactId;
	}

	public function getContactId()
	{
		return $this->contactId;
	}

	public function getApiMethodName()
	{
		return "taobao.logistics.address.remove";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->contactId,"contactId");
	}
}
