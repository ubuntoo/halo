<?php
namespace service;

/**
 * HTTP协议
 *
 * @author
 */
class HTTP implements IProtocol {
    
    public function test($params) {
    	$params .= __CLASS__;
    	echo "     进入到http类执行 test方法: 参数  {$params}\n";
    }
}
