<?php
namespace service;
/**
 * TCP协议
 *
 * @author
 */
class TCP implements IProtocol {
    
	public function test($params) {
		$params .= __CLASS__;
		echo "     进入到TCP类执行 test方法: 参数  {$params}\n";
    
    }

}