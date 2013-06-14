<?php
namespace framework;
use Framework;

/**
 * 面向切面组件工厂
 */
class AOP {
  /**
     * 构造函数，构造消息模式
     * 
     * @return void
     */
    public function __construct() {}
    
    private static $extList = null; // 扩展列表
    
    /**
     * 添加扩展
     * 
     * @param  array	$extList	控制实体列表
     * 
     * @return void
     */
    public static function add($extList) {
        $aspect = &Framework::$Locator->getService(ucfirst(array_shift(array_keys($extList))) . 'Aspect'); // 切面实体
    	foreach ($extList as $key => $exts) {
            foreach ($exts as $ext) {
    		    $extName = get_class($ext);  // 类名
                if (isset(self::$extList[$key]) && isset(self::$extList[$key][$extName])) {
                    continue;
                }
                self::$extList[$key][$extName] = $ext;
    	    }
    	    break;    	  
    	} 	
    	$aspect->extList = self::$extList[$key];  	
    	return $aspect;
    }
    
    /** 
     * 删除扩展
     * 
     * @param $classNames array 控制类名列表
     * 
     * @return void
     */
    public static function remove($classNames) {
        foreach ($classNames as $className) {
            if (isset(self::$extList[$className])) {
                unset(self::$extList[$className]);
            }
        }
    	return ;
    }
    
   /** 
     * 删除所有扩展
     * 
     * @return void
     */
    public static function removeAll() {
        if (!is_null(self::$extList)) {
           unset(self::$extList);
        }
        return ;
    }
}