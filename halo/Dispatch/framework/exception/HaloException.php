<?php
namespace framework\exception;
use Exception;

/**
 * 自定义异常类
 */
class HaloException extends Exception
{       
    /**
     * 抛出异常
     * 
     * @param   $msgStr    string   异常消息
     * @param   $param     array    特殊替换参数
     * 
     * @return void
     */
    public function __construct($msgStr = null, $param = array()) {
    	throw new Exception(_v((empty($msgStr) || !is_string($msgStr) ? null : $msgStr), $param)); 
    }
}