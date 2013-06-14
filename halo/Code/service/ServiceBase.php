<?php
namespace service;
use Dispatch\FrameBase;

/**
 * 逻辑层抽象基类
 */
abstract class ServiceBase extends FrameBase {	
   /**
	 * 构造函数
	 * 
	 * @return void
	 */
	public function __construct() 
	{
		parent::__construct();
		return ;
	}

}