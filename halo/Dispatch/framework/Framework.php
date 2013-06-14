<?php
/**
 * 框架
 */
defined('FRAMEWORK_PATH') or define("FRAMEWORK_PATH", realpath(dirname(__FILE__) . CS));
final class Framework
{
    private static $initialized = false; 	 // 框架是否已初始化	
	private static $configuration = array(); // 框架配置参数
	
	// 框架功能模块
    public static $Locator;    // 定位器
    public static $Cache;      // 缓存
    public static $Dispatcher; // 调度器
    public static $DaoHelper;  // 数据库操作对象
    // 不构建实体,只获取类名
    public static $View;       // 视图
  	public static $Exception;  // 异常
  	
    public static $Frame =  array(); // 框架标准
	
    /**
     * 构造函数设为私有属性,禁止实例化
     */
    private function __construct() {}
    
    /**
     * 框架预置
     * 
     * @param   $configuration  array   框架参数
     * 
     * @return void
     */
    public static function init(array $configuration)
    { 
    	if (self::$initialized) return;
    	self::setConfiguration($configuration);        
        require_once FRAMEWORK_UTIL_PATH . 'function.php'; // 引入框架工具           
        set_exception_handler('exceptionHandler'); // 自定义异常处理函数
        $options = array();      
        foreach (self::$configuration as $option => $args) {      	
        	$options[$option] = self::loadOption($option, $args->type, $args->args, $args->creatObj);
        }
        self::$DaoHelper = isset($options['db'])	    ? $options['db']	: null;
        self::$Cache     = isset($options['cache'])     ? $options['cache']	: null;
        self::$View      = isset($options['view'])      ? $options['view']	: null;
        self::$Exception = isset($options['exception']) ? $options['exception']	: null;      
        $singleClass = 'framework' . CS . 'SingletonConstructor';      
        self::$Locator = new $singleClass;         // 保存单例定位器      
        // 设置语言包
        setLocational(array(LOCATIONAL_DEFAULT_DOMAIN), LOCATIONAL_PATH, LOCATIONAL_LANGUAGE, LOCATIONAL_CODESET, true);
        
        // 初始化框架标准
        self::initCriterion();
        self::initRuntime();
        self::$initialized = true;
        return;
    }
    
    /**
     * 请求调度
     * 
     * @param   $entranceType   string  请求入口
     * @param   $addslashes     boolen  是否转义
     * 
     * @return void
     */
    public static function requestDispatcher($entranceType, $addslashes = true) 
    {
    	if (!self::$initialized) die("Please execute 'Framework::init();' first");	
    	if ($addslashes && !get_magic_quotes_gpc()) {
    		recursion_addslashes($_GET);
            recursion_addslashes($_COOKIE);
            recursion_addslashes($_POST);
            recursion_addslashes($_REQUEST);
        }          
        $dispatcherClassName = 'framework' . CS . 'dispatcher' . CS . ucfirst($entranceType) . 'Dispatcher';
        self::$Dispatcher = new $dispatcherClassName();
        self::$Dispatcher->distribute();
        return ;
    }
    
    /**
     * 设置框架参数
     * 
     * @param 	array  $configuration   框架参数
     * 
     * @return void
     */
    private static function setConfiguration(array $configuration) 
    {		
    	if (self::$configuration) return;
    	foreach ($configuration as $option => $args) {  		
    		if ($args) foreach ($args as $type => $info) {
    			if (isset($info['args']['switch']) && !$info['args']['switch']) continue;
        		self::$configuration[$option] = (object)array(
        			'type' 		=> $type, 
        			'args' 		=> isset($info['args']) ? $info['args'] : null,
        			'creatObj' 	=> isset($info['creatObj']) ? $info['creatObj'] : true,
        		);
        		break;
    		}
    	}
    	return;
    }
    
   /**
     * 加载功能模块
     * 
     * @param  string		$option    	功能名 
     * @param  string 		$type      	类型 
     * @param  array|obj	$args       参数列表
     * @param  bool			$creatObj	是否返回实体
     * 
     * @return obj|void
     */
    private static function loadOption($option, $type, $args = null, $creatObj = true)
    {    	
    	$class = 'framework' . CS . $option . CS . $type . ucfirst($option);	
    	if ($creatObj) {
        	return new $class($args);
        } elseif ($args && function_exists($class::init)) { // 模块提供初始化的静态接口init
        	$class::init($args);
        }
        return $class;
    }
    
   /**
     * 初始化框架标准
     * 
     * @return void
     */
    private static function initCriterion() 
    {
    	self::$Frame['now'] = time();
        self::$Frame = (object)self::$Frame;
        return;
    }
    
   /**
     * 初始化运行信息
     * 
     * @return void
     */
    private static function initRuntime() 
    {
        $GLOBALS['runtime'] = array(
            'memoryUsage' => isset($GLOBALS['runtime']['memoryUsage']) 
        						 ? $GLOBALS['runtime']['memoryUsage'] : memory_get_usage(),
            'startTime'   => isset($GLOBALS['runtime']['startTime'])
        						 ? $GLOBALS['runtime']['startTime'] : microtime(true),
        );      
        return;
    }
}