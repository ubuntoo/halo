<?php
namespace service;
/**
 * UDP协议
 *
 * @author
 */
class UDP implements IProtocol {
    public function test($params) {
    	$params .= __CLASS__;
    	echo "     进入到UDP类执行 test方法: 参数  {$params}\n";
    }

}