<?php
namespace framework;

/**
 * 单例对象构造器
 */
class SingletonConstructor
{
    private $objList = array(); // 实体列表

    /**
     * 获取一个单例对象
     * 
     * @param  string	$className  类名
     * @param  array	$args       参数数组
     * 
     * @return \$classname  obj
     */
    public function get($className, $args)
    {     		
        if (isset($this->objList[$className])) {
        	if (is_array($args) && $args) {
        	    $this->objList[$className]->__construct($args);
        	}
            return $this->objList[$className];
        }
        $object = new $className($args);     
        $this->objList[$className] = $object;       
        return $object;
    }
    
    /**
     * 魔法函数 - 方法自动加载
     * 
     * @param string	$func	方法名 
     * @param array		$args   参数列表
     * 
     * @return void
     */
    public function __call($func, $args) {	
    	if (in_array($func, array('getCtrl', 'getService', 'getDao'))) {         
            return $this->get(lcfirst(ltrim($func, 'get')) . CS . array_shift($args), array_shift($args));
    	}
    	return;
    }
}