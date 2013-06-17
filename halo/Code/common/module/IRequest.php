<?php
namespace service;
/**
 * 请求认证接口
 */
interface IRequest 
{
	// 请求参数解密
	public function decrypt(&$request);
	// 请求认证
	public function check($act, $params, $op);
}