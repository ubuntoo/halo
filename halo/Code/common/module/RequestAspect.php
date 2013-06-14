<?php
namespace service;
/**
 * 请求过滤切面
 * 
 * 可用于:
 * 1. 请求参数解密
 * 2. 请求方法, 参数, op过滤验证
 * 3. 请求ip过滤验证
 * 4. 请求时间频率,次数验证(结合消息队列) 防外挂
 * 5. 请求结果加密,压缩
 * 6. 屏蔽字过滤
 */
class RequestAspect implements IRequest {
    public $extList = null; // 扩展列表 
    
    /**
     * 请求参数解密
     * 
     * @param  string  	$request  请求参数
     * 
     * @return void
     */
    public function decrypt(&$request) { 	
    	foreach($this->extList as $ext) {
            $ext->decrypt(&$request);
        }
    }
    
    /**
     * 认证
     * 
     * @param  string  	$act        请求
     * @param  array  	$params     请求参数
     * @param  string  	$op         请求op
     * 
     * @return bool
     */
    public function check($act, $params = array(), $op = null) { 	
    	foreach($this->extList as $ext) {
            $ext->check($act, $params, $op);
        }
    }
}