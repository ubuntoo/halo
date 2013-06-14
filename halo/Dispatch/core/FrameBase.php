<?php
namespace Dispatch;
use Framework;

/**
 * 框架根基类 
 */
abstract class FrameBase
{
    /**
     * 单例构造器
     * 
     * @var SingletonLocator
     */
    protected $locator;
    
    /**
     * 缓存器
     * 
     * @var ICache
     */
    protected $cache;
    
    /**
     * 异常器
     * 
     * @var exception
     */
    protected $exception;
     
    /**
     * 数据库操作器
     * 
     * @var $daoHelper
     */
    protected $daoHelper;
    
    /**
     * 框架标准数组
     * 
     * @var array
     */
    protected $frame;
    
    /**
     * 构造函数  初始化环境
     * 
     * @param  string 继承子类的令名空间
     * 
     * @return void
     */
    public function __construct($childNameSpace = null)
    {
        switch ($childNameSpace) {
        	case 'ctrl':
        		$this->view = &Framework::$View;		
        		$this->dispatcher = &Framework::$Dispatcher;
        		break;
        	case 'dao':
        		$this->daoHelper = &Framework::$DaoHelper;
        		$this->cache = &Framework::$Cache;
        		if (!$this->cache->status()) {
        			unset($this->cache);
        		}
        		break;		
        	default:
        		break;		
        }
        $this->locator   = &Framework::$Locator;
        $this->frame     = &Framework::$Frame;
        $this->exception = &Framework::$Exception;
        return;
    }
    
    /**
     * 初始化类环境    已废弃
     * 
     * @param   $args   array   参数
     * 
     * @return obj
     */
    static public function newSelf($args) {
        $className = get_called_class();
        $reflectionClass = new \ReflectionClass($className);
        $construct = $reflectionClass->getMethod('__construct');
        $parameters = $construct->getParameters();
        $param_arr = array(); // 参数
        foreach($parameters as $key => $param) {
            $paramName = $param->getName(); // 获取方法的参数
            $param_arr[] = isset($args[$key]) ? $args[$key] : 
                (($param->isDefaultValueAvailable()) ? $param->getDefaultValue() : null); // 获取默认值
        }
//>>> 动态参数传递方式待处理        写 call_user_class_array 扩展
        return new $className($param_arr);
    }
}