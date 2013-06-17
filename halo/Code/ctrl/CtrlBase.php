<?php
namespace ctrl;
use Dispatch\IController;
use Dispatch\FrameBase;

/**
 * 控制器层抽象基类
 */
abstract class CtrlBase extends FrameBase implements IController 
{	
	/**
	 * 在执行控制器方法之前执行的过滤方法，该方法返回布尔类型值，当返回结果为true时，继续执行请求的方法，当返回结果为false时，终端请求的执行。
	 * 该方法可用于用户认证或者请求加锁等。
	 * 
	 * @return boolean 
	 */
	public function beforeFilter() {}
	
	/**
	 * 在执行控制器方法之后执行，无论beforeFilter是否返回为true，该方法都会得到执行。
	 * 该方法可用于日志记录或者请求解锁等。
	 * 
	 * @see beforeFilter();
	 */
	public function afterFilter() {}
	
	/**
	 * 请求参数
	 * 
	 * @var obj
	 */
	protected $parms;
	
	/**
	 * 构造函数
	 * 
	 * @return void
	 */
	public function __construct() 
	{
		parent::__construct(__NAMESPACE__);
		$this->parms = $this->dispatcher->getParams();
		return ;
	}
}