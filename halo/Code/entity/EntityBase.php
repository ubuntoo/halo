<?php
namespace entity;

/**
 * 实体层抽象基类
 *  
 * @author
 */
abstract class EntityBase
{  
	private static $setArr = array();  // 设置数组
	private static $addArr = array();  // 添加数组
    private static $minArr = array();  // 数值维度下限数值
    private static $maxArr = array();  // 数值维度上限限数值
    
   /**
     * 写入数据库后清除改变状态
     * 
     * @return void
     */
    public function clearChange()
    {
        self::$setArr = array();
        self::$addArr = array();
        self::$minArr = array();
        self::$maxArr = array();
        return ;
    }
    
	/**
	 * 魔术方法  获取属性值
	 * 
	 * @param  var     $attribute 属性
	 * 
	 * @return string  属性值
	 */
    public function __get($name)
    {
        $methodName = "get" . ucfirst($name);
        return method_exists($this, $methodName)
            ? $this->$methodName()
            : (method_exists($this, "getter")
                ? $this->getter($name)
                : (isset($this->$name)
                    ? $this->$name
                    : (isset($name) ? self::$$name : null)
                )
            );
    }
    
    /**
     * 魔术方法，调用一个不可见的方法
     * 
     * @param $method 方法名
     * @param $args   参数列表
     * 
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (in_array($method, array('set', 'add'))) {
            $name = $args['0'];
            $methodName = $method . ucfirst($name);
            $unitive = ($method == "set") ? "setter" : "adder";
            if (method_exists($this, $methodName)) {
                array_shift($args);
                return call_user_func_array(array($this, $methodName), $args);
            } else if (method_exists($this, $unitive)) {
            	return call_user_func_array(array($this, $unitive), $args);
            } else {
                return call_user_func_array(array($this, $method), $args);
            }
        } else {
            return false;
        }
    }
    
    /**
     * 属性统一设置方法
     * 
     * @param string $name 属性名
     * @param mixed  $val  属性值
     * @param mixed  $min  下限（默认为null）
     * @param mixed  $max  上限（默认为null）
     * 
     * @return void
     */
    final protected function set($name, $val, $min = null, $max = null)
    {
        $this->$name = $val;   
        self::$setArr[$name] = $val;      
        if (!is_null($min)) {
            $this->$name = max($min, $this->$name);
            self::$minArr[$name] = $min;
        }
        if (!is_null($max)) {
            $this->$name = min($max, $this->$name);
            self::$maxArr[$name] = $max;
        } 
        return ;
    }
    
   /**
     * 属性统一增加（传负值减少）方法
     * 
     * @param   string  $name   属性名
     * @param   mixed   $val    增加值
     * @param   mixed   $min    下限（默认为null）
     * @param   mixed   $max    上限（默认为null）
     * 
     * @return void
     */
    final protected function add($name, $val, $min = null, $max = null)
    {
        if (empty(self::$addArr[$name])) self::$addArr[$name] = 0;

        $this->$name += $val;
        self::$addArr[$name] += $val;

        if (!is_null($min)) {
            $this->$name = max($min, $this->$name);
            self::$minArr[$name] = $min;
        }
        if (!is_null($max)) {
            $this->$name = min($max, $this->$name);
            self::$maxArr[$name] = $max;
        }
        return ;
    }
}