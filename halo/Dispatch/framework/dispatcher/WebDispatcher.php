<?php
namespace framework\dispatcher;
use framework\AOP;

use Framework;

/**
 * web请求调度接口
 */
class WebDispatcher extends DispatcherBase {
    /**
     * 构造函数 
     * 
     * @return void
     */
    public function __construct() {   	
    	if (!isset($_COOKIE['PHPSESSID']) || $_COOKIE['PHPSESSID'] != '00000') {
            session_start();
        }
        header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header ("Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT");
        header ("Cache-Control: no-store, no-cache, must-revalidate");
        header ("Cache-Control: post-check=0, pre-check=0", false);
        header ("Pragma: no-cache");
        $aop = AOP::add(array('request' => array(new \service\RequestWeb())));
        $aop->decrypt($_REQUEST);    
        self::$request = (object)self::$request;           
        $act = isset($_REQUEST['act']) && isset($GLOBALS['ACTION_MAP'][$_REQUEST['act']]) 
        	? $GLOBALS['ACTION_MAP'][$_REQUEST['act']] : (empty($_REQUEST['act']) ? SCRIPT_NAME : $_REQUEST['act']);       
        strrchr($act, '.') or $act .= '.main';
   
        self::$request->act = $act;   
        if (isset($_REQUEST['act']) && isset($GLOBALS['ACTION_MAP'][$_REQUEST['act']])) {  	
            self::$request->op = $_REQUEST['act'];
        }
        unset($_REQUEST['act']);
        self::$request->params = (object)$_REQUEST;
        $aop->check(self::$request->act, self::$request->params, self::$request->op);
        parent::__construct();
        return ;
    }
    
    /**
	 * 请求调度
	 * 
	 * @return void
	 */
    public function distribute()
    {
		$model = parent::distribute();		
        if ($model && self::$request->op) {
            $count = self::setOp(self::$request->op, $model);
            if (isset($model['#'])) {
            	$view = new \Framework::$View();
    			$view->display($model['#'], $count);
            }
        }
        return ;
    }
    
    /**
     * 添加OP到输出模式MODEL数组中
     * 
     * @param  string  	$op        操作码
     * @param  array  	$model     显示模型
     * 
     * @return array
     */
    private static function setOp($op, $model)
    {      
        return array_merge(array('op' => $op), $model);
    }
}