<?php
namespace framework\dispatcher ;
use Framework;
use framework\AOP;

/**
 * shell请求调度接口
 */
class ShellDispatcher extends DispatcherBase {
    /**
     * 构造函数 
     * 
     * @return void
     */
    public function __construct() {
    	$request = array_slice($_SERVER['argv'], 1);
    	$act = array_shift($request);
    	self::$request = (object)self::$request;
    	if ($act) {
    		if (isset($GLOBALS['ACTION_MAP'][$act])) {
    			self::$request->op = $act;
    			$act = $GLOBALS['ACTION_MAP'][$act];
    		}
    	} else {
    		$act = SCRIPT_NAME;
    	}
    	strrchr($act, '.') or $act .= '.main';
    	$aop = AOP::add(array('request' => array(new \service\RequestShell())));
        self::$request->act = $act;
        self::$request->params = (object)$request;     
    	$aop->check(self::$request->act, self::$request->params, self::$request->op);
        parent::__construct();
        return ;
    }
}