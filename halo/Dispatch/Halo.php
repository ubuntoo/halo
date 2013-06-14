<?php
define('ROOT_PATH', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR);
define('DISPATCH_PATH', ROOT_PATH . 'Dispatch' . DIRECTORY_SEPARATOR);

/**
 * halo 框架调度中心
 */
final class Halo
{
	/**
     * 框架运行模式: web
     * 
     * @var int
     */
	const HALO_WEB = 1;
	/**
     * 框架运行模式: shell
     * 
     * @var int
     */
	const HALO_SHELL = 2;
	
    /**
     * 运行
     * 
     * @param	int  $type  运行模式
     *
     * @return void
     */
    public static function run($type)
    {
    	self::init($type);	
    	Framework::requestDispatcher($type == self::HALO_WEB ? 'web' : 'shell');
    	return ;
    }
    
    /**
     * 初始化
     * 
     * @param	int  $type  运行模式
     * 
     * @return void
     */
    private static function init($type) {
    	// 加载框架配置
		array_walk(glob(DISPATCH_PATH . 'configs' . DIRECTORY_SEPARATOR . 'config.*.php'), function ($file){require $file;});		
		// 报错模式
		ini_set('display_errors', defined('SWITCH_DEBUG_MODE') && SWITCH_DEBUG_MODE ? 'On' : 'Off');		
		// 加载框架
		require FRAMEWORK_PATH . 'Framework.php';		
		// 框架参数
		$frameworkArgs = array();
		// 数据库
		$dbArgs = array();
		if (defined('DB_HOST'))     $dbArgs['host']        = DB_HOST;
		if (defined('DB_PORT'))     $dbArgs['port']        = DB_PORT;
		if (defined('DB_LIBR'))     $dbArgs['dbname']      = DB_LIBR;
		if (defined('DB_USER'))     $dbArgs['username']    = DB_USER;
		if (defined('DB_PASS'))     $dbArgs['password']    = DB_PASS;
		if (defined('DB_SOCKET'))   $dbArgs['unix_socket'] = DB_SOCKET;
		if (defined('DB_PCONNECT')) $dbArgs['pconnect']    = DB_PCONNECT;
		$frameworkArgs['db'][defined('TYPE_DAOHELPER') && TYPE_DAOHELPER ? TYPE_DAOHELPER : 'PDO'] = array('args' => $dbArgs);
		// 视图
		$frameworkArgs['view'][defined('TYPE_VIEW') && TYPE_VIEW ? TYPE_VIEW : 'Json'] = array('creatObj' => false);
		// 缓存
		$frameworkArgs['cache'][defined('TYPE_CACHE') && TYPE_CACHE ? TYPE_CACHE : 'Memcached'] = array('args' => array(
    		'switch'    => defined('SWITCH_CACHE')    ? SWITCH_CACHE    : false,
    		'host'      => defined('CACHE_HOST')      ? CACHE_HOST      : false,
    		'port'      => defined('CACHE_PORT')      ? CACHE_PORT      : false,
    		'outTime'   => defined('REDIS_OUTTIME')   ? REDIS_OUTTIME   : 0,
    		'serialize' => defined('REDIS_SERIALIZE') ? REDIS_SERIALIZE : false,
    		'database'  => defined('DB_LIBR')         ? DB_LIBR         : null,
		));
		// 异常
        $frameworkArgs['exception'][defined("TYPE_EXCEPTION") ? TYPE_EXCEPTION : 'HaloException'] = array('creatObj' => false);		
		// 框架预置
		Framework::init($frameworkArgs);
		
		// 加载框架基类 ,项目配置
		array_walk(array_merge(
    		glob(DISPATCH_CORE_PATH . '*.php'),
			glob(CONFIGS_PATH . 'config.*.php'),
			glob(COMMON_FUNCTION_PATH . '*.php')
		), function($file){require $file;});
		// 默认调用类
    	define('SCRIPT_NAME', $type == self::HALO_WEB ? 'Index' : 'Shell');
		return true;
    }
}