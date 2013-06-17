<?php
namespace framework\view;
use Framework;

/**
 *  Smarty视图
 */
class SmartyView extends ViewBase {
	const TYPENAME = 'smarty';
	
	private static $smarty ; // smarty 对象
	private static $tpl ;    // 模板
	private static $args ;   // 参数
	
	/** 
     * 构造函数
     * 
     * @param  array $args  参数
     *  
     * @return Smarty
     */
	public function __construct() {	
		if (!self::$smarty && loadFile('Smarty.class', LIB_SMARTY_PATH)) {
			$smarty = new \Smarty();
			$smarty->cache_dir       = SMARTY_CACHE_DIR;
            $smarty->compile_dir     = SMARTY_COMPILE_DIR;
            $smarty->template_dir    = HTML_PATH;
            $smarty->left_delimiter  = SMARTY_LEFT_DELIMITER;
            $smarty->right_delimiter = SMARTY_RIGHT_DELIMITER;      
            self::$smarty = $smarty;
	       	self::setAutoloadFunction();
        }
        return ;
	}

	/**
	 * 设置自动加载函数
	 * 
	 * @param  $functionName   string  函数名
	 * 
	 * @return 	void
	 */
	private static function setAutoloadFunction($functionName = '__autoload') 
	{
		$registeredAutoLoadFunctions = spl_autoload_functions();	
		// 注销所有的加载函数 
        if (!isset($registeredAutoLoadFunctions[$functionName])) {	
            foreach ($registeredAutoLoadFunctions as $func) {
				spl_autoload_unregister($func);
			}
			spl_autoload_register($functionName);
      	}
      	return ;
	}
	
	/**
     * 展示 
     * 
     * @param string $tpl  模板编号
     * @param array  $args 参数
     * 
     * @return void
     */
	public function display($tpl = null, $args = array()) {				
		$tpl = !$tpl ? self::$tpl : (preg_match("/^(\d+)/", $tpl, $items) ? array_pop($items) : $tpl);		
		if (is_numeric($tpl) && isset($GLOBALS['templet'][$tpl])) {
		    $tpl = $GLOBALS['templet'][$tpl];
		}		
		if (!in_array($tpl, array_values($GLOBALS['templet']))) {
			// 模板[TPL]没找到
            throw new Framework::$Exception("e.Template '[TPL]' Not Found", array('TPL' => $tpl));
		}	
		$args = !$args ? self::$args : $args;       
		$smarty = self::$smarty;	
		$args and $smarty->assign($args);      
		$templateName = preg_match('/\.html$/', $tpl) ? $tpl : $tpl . '.html';		
		if ($templateName && file_exists(HTML_PATH . $templateName)) {
			self::setAutoloadFunction('smartyAutoload');
			$smarty->display($templateName);
			self::setAutoloadFunction();
        } else {
        	// 模板[TPL]没找到
            throw new Framework::$Exception("e.Template '[TPL]' Not Found", array('TPL' => $tpl));
        }
        return ;
	}
	
	public  function __set($name, $arg) 
	{
		self::$$name = $arg;	
	}
	
	public function __get($arg) 
	{
		if (isset(self::$args)) return self::$args;	
	}
}