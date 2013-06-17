<?php
namespace framework\dispatcher;
use Framework;

/**
 * 请求调度的基类
 */
class DispatcherBase implements IDispatcher 
{
	/**
     * 单例
     *
     * @var obj
     */
    protected static $Locator;
	
	/**
     * 控制器类名
     *
     * @var string
     */
    protected static $ctrlName;

    /**
     * 控制器方法名
     *
     * @var string
     */
    protected static $methodName;
        
    /**
     * 请求
     *
     * @var string
     */
    static protected $request = array('act' => null, 'params' => null, 'op' => null);

    /**
     * 构造函数 
     * 
     * @return void
     */
    public function __construct() 
    {  
        self::$Locator = empty(\Framework::$Locator) ? null : \Framework::$Locator;        
        if (preg_match('/^([a-z_\\\\]+)\.([a-z_0-9]+)$/i', self::$request->act, $items)) {
        	self::$methodName = array_pop($items);    	
            self::$ctrlName  = array_pop($items);         
        } else { 	
            // 请求参数[ACT]错误           
            \Framework::$Exception->error("e.fram.Request '[ACT]' Invalid", array('ACT' => self::$request['act']));
        }
        return ;
    }
	
	/**
     * 获取请求分发参数
     *
     * @return obj
     */
    public function getParams() 
    {
        return self::$request->params;
    }
    
	/**
	 * 请求调度
	 * 
	 * @return array
	 */
    public function distribute()
    {  	   	
		if (self::$Locator) {
			$ctrlobj = self::$Locator->getCtrl(self::$ctrlName);		
		} else {
			// 控制器Framework::Locator没找到
			throw new Framework::$Exception("e.frame.Framework::Locator Not Found");
		}		
        if (is_null($ctrlobj)) {
        	// 控制器对象[CLASS] 没找到
        	throw new Framework::$Exception("e.frame.frame.CtrlObj '[CLASS]' Not Found", array('CLASS' => self::$ctrlName));
        }
        $method = self::$methodName;     
        if (!is_callable(array($ctrlobj, $method))) {
         	// 控制器方法[CLASS].[METHOD] 没找到
            throw new Framework::$Exception("e.frame.CtrlMethod '[CLASS].[METHOD]' Not Found", array('CLASS' => self::$ctrlName, 'METHOD' => $method));
        }
        return $ctrlobj->$method();
    }
    
   /**
     * 获取操作op
     *
     * @return string
     */
	public function getOp() 
	{
		return self::$request->op;
	}
}