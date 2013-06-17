<?php
/**
 * 外挂模拟器
 * 
 * @package rabotSimulator
 * @author  王伟<ubunto@sina.cn>
 * @since   v1.0
 */

set_time_limit(0);
error_reporting(E_ALL);
require 'rabot.php';
$rabot = new Rabot();

// 任务
$task = array(
	'url'	      => 'http://h4.qjp.g.1360.com/',  	    				// 域名
	'task'        => array(
		'201' => array(														// op
			'param'    => array(),											// 参数
			'interval' => 1000000,										    // 请求间隔时间(微妙)
			'times'	   => 3,												// 请求总次数
		),
		
		'201' => array(														// op
			'param'    => array(),											// 参数
			'interval' => 1000000,										    // 请求间隔时间(微妙)
			'times'	   => 3,												// 请求总次数
		),
	),
	'requestType' => Rabot::RABOT_REQUEST_TYPE_POST, 						// 请求方式
	'startTime'   => '2013-01-01 00:00:00',									// 任务执行开始时间
	'callback'	  => 'showResult'											// 回调函数 
);
$rabot->register($task);


$rabot->start();
//http://h4.qjp.g.1360.com/test.php
exit;
$rabot->simulatingRequest($url, $param);