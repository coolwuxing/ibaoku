<?php namespace lotus;
function C($className)
{
	return LtObjectUtil::singleton($className);
}
