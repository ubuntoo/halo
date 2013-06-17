<?php
namespace service;

/**
 * IPX协议
 *
 * @author
 */
class IPX implements IProtocol {
	
    public function test($params) {
    	$params .= __CLASS__;
        echo "     进入到IPX类执行 test方法: 参数  {$params}\n";
    }
}