<?php
namespace service;
/**
 * shell请求过滤
 *
 * @author
 */
class RequestShell implements IRequest {
	
   /**
     * 请求参数解密
     * 
     * @param  string  	$request  请求参数
     * 
     * @return void
     */
    public function decrypt(&$request) { 	
    	return ;
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
    	return ;
    }
}