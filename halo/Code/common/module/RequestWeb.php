<?php
namespace service;
/**
 * web请求过滤
 */
class RequestWeb implements IRequest 
{    
    /**
     * 请求参数解密
     * 
     * @param  string  	$request  请求参数
     * 
     * @return void
     */
    public function decrypt(&$request) 
    {
    	// 解密
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
    public function check($act, $params = null, $op = null) 
    {
    	// 过滤act 
    	// 过滤参数
    	// 过滤op
    }
}