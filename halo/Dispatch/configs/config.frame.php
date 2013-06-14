<?php
/**
 * 框架配置
 */
define('SWITCH_DEBUG_MODE', true); 														// 调试模式开关

// 定义路径
define('DS', DIRECTORY_SEPARATOR);  													// 路径分割符
define('CS', '\\');  																	// 类分割符
define('LIB_PATH', ROOT_PATH . 'Lib' . DS); 											// 第三方库存放路径

if (!defined('DISPATCH_PATH')) define('DISPATCH_PATH', ROOT_PATH . 'Dispatch' . DS); 	// 调度路径

define('FRAMEWORK_PATH', DISPATCH_PATH . 'framework' . DS);								// 框架路径
define('FRAMEWORK_UTIL_PATH', FRAMEWORK_PATH . 'util' . DS); 							// 框架工具包
define('FRAMEWORK_DB_PATH', FRAMEWORK_PATH .'db' . DS); 								// 框架db包
define('FRAMEWORK_VIEW_PATH', FRAMEWORK_PATH . 'view' . DS); 							// 框架视图包
define('FRAMEWORK_CACHE_PATH', FRAMEWORK_PATH . 'cache' . DS); 							// 框架缓存包
define('FRAMEWORK_EXCEPTION_PATH', FRAMEWORK_PATH . 'exception' . DS); 					// 框架异常处理包
define('FRAMEWORK_DISPATCHER_PATH', FRAMEWORK_PATH . 'dispatcher' . DS); 				// 框架请求调度包
define('FRAMEWORK_MESSAGE_PATH', FRAMEWORK_PATH . 'message' . DS);                      // 框架消息处理包

define('DISPATCH_CONFIGS_PATH', DISPATCH_PATH . 'configs' . DS); 						// 调度配置路径
define('DISPATCH_CORE_PATH', DISPATCH_PATH . 'core' . DS); 								// 调度核心路径

define('CONFIGS_PATH', ROOT_PATH . 'Configs' . DS); 									// 项目配置

define('CACHE_PATH', ROOT_PATH . 'cache' . DS);                                         // 缓存层

define('CODE_PATH', ROOT_PATH . 'Code' . DS); 											// 代码区

define('CTRL_PATH', CODE_PATH . 'ctrl' . DS); 											// 控制层
define('SERVICE_PATH', CODE_PATH . 'service' . DS); 									// 逻辑层
define('DAO_PATH', CODE_PATH . 'dao' . DS); 											// 数据库层
define('ENTITY_PATH', CODE_PATH . 'entity' . DS); 										// 实体层
define('COMMON_PATH', CODE_PATH . 'common' . DS); 										// 工具包
define('COMMON_FUNCTION_PATH', COMMON_PATH . 'function' . DS);                          // 工具包 - 方法
define('COMMON_CLASS_PATH', COMMON_PATH . 'class' . DS);                                // 工具包 - 类
define('COMMON_MODULE_PATH', COMMON_PATH . 'module' . DS);                              // 工具包 - 模块

define('TEMPLATE_PATH', CODE_PATH . 'template' . DS); 									// 模板层
define('HTML_PATH', TEMPLATE_PATH . 'html5' . DS); 										// html 层
define('CSS_PATH', TEMPLATE_PATH . 'css' . DS); 										// css 层
define('RESOURCE_PATH', TEMPLATE_PATH . 'resource' . DS); 								// resource 层

define('LOCATIONAL_PATH', ROOT_PATH . 'locational' . DS);                               // 语言包目录

// 功能配置
define('TYPE_CACHE', 'Memcache');  // Memcached  Redis
define('TYPE_VIEW', 'Smarty');
define('TYPE_DAOHELPER', 'PDO');
define('TYPE_EXCEPTION', 'Halo'); 

if (in_array(TYPE_VIEW, array('Template', 'Smarty'))) {
	if (!defined('SMARTY_SPL_AUTOLOAD')) define('SMARTY_SPL_AUTOLOAD', 1);
 	define('LIB_SMARTY_PATH', LIB_PATH . 'smarty' . DS); 								// 第三方库 smarty 存放路径
	define('SMARTY_LEFT_DELIMITER', '[{'); 												// smarty 左分割符
	define('SMARTY_RIGHT_DELIMITER', '}]'); 											// smarty 右分割符
	define('CACHE_SMARTY_PATH', CACHE_PATH . 'smarty' . DS); 							// smarty 缓存路径
	define('SMARTY_CACHE_DIR', CACHE_SMARTY_PATH . 'cache' . DS); 						// smarty 缓存路径
	define('SMARTY_TEMPLATE_DIR', TEMPLATE_PATH); 					  					// smarty 模板路径
	define('SMARTY_COMPILE_DIR', CACHE_SMARTY_PATH . 'compile' . DS); 					// smarty 编译路径	
	
	define('CSS', "../Code/template/css"); 
	define('JS', "../Code/template/js");
	define('IMAGES', "../Code/template/images");
}

// 时区设置
define('TIME_ZONE', 'Asia/Shanghai');
ini_set('date.timezone', TIME_ZONE);

// 设置编码
header("Content-Type:text/html;charset=utf-8");  										// 设置系统的输出字符为utf-8

// 国际化配置
define("LOCATIONAL_LANGUAGE", "zh_CN");
define("LOCATIONAL_CODESET", "UTF-8");
define("LOCATIONAL_DEFAULT_DOMAIN", "common");

// SESSION存储路径
if (defined('SESSION_SAVE_HANDLER')) ini_set('session.save_handler', SESSION_SAVE_HANDLER);
if (!defined('SESSION_PATH')) define('SESSION_PATH', CACHE_PATH . 'session');
ini_set('session.save_path', SESSION_PATH);
return;