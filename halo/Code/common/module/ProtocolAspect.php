<?php
namespace service;

/**
 * 协议切面
 *
 * @author
 */
class ProtocolAspect implements IProtocol {
    public $extList = null; // 扩展列表 
    
    public function test($params) {
    	print_r($params);
    	$index = "参数不同类进可以传递改变";
    	print_r($index."\n");
        foreach($this->extList as $ext) {
        	print_r("开始执行->". get_class($ext) . "的test方法\n" );
            $ext->test($index);
        }
        return  "end!";
    }
    
}
