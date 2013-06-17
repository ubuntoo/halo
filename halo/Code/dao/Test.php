<?php
namespace dao;

/**
 * 测试  数据库操作 类 
 * 
 * @author
 */
class Test extends DaoBase
{
    /**
	 * 框架运行
	 * 
	 * @param  int 		param1    参数1
	 * @param  string   param2    参数2
	 * @param  array    param3    参数3
	 * 
	 * @return void
	 */
	public function run($param1, $param2, $param3) 
	{
		echo "进入dao层\n";
		echo "运行情况:\n";
    	print_r(runtime());
    	echo "框架标准:\n";
    	$frame = $this->frame;
    	print_r($frame);
		return;
	}
	
    /**
	 * 缓存
	 * 
	 * @return void
	 */
    public function cache() {
    	echo "在空间：" . __NAMESPACE__ . "可使用缓存\n";
    	var_dump($this->cache);
    	echo "使用规范：\n";
    	$set = $this->cache->set('key', 123);
    	var_dump($set);
    	$get = $this->cache->get('key');
		var_dump($get);
    	$add = $this->cache->add('key', 13);
		var_dump($add);
    	$get = $this->cache->get('key');
		var_dump($get);
    	$flush = $this->cache->flush();
		var_dump($flush);
    	$status = $this->cache->status();
		var_dump($status);
    	return;
	}
	
   /**
     * 参数
     * 
     * @return void
     */
    public function param()
    {	
    	echo "在空间：" . __NAMESPACE__ . "不可调用参数\n";
    	var_dump($this->parms);
        return ;
    }
    
   /**
     * 数据库
     * 
     * @return void
     */
    public function db()
    {
    	echo "在空间：" . __NAMESPACE__ . "可使用数据库操作对象\n";
    	var_dump($this->daoHelper);
        return ;
    }
    
    /**
     * 视图
     * 
     * @return void
     */
    public function view()
    {
    	echo "在空间：" . __NAMESPACE__ . "不可使用视图对象\n";
    	$view = $this->view;
    	var_dump($view);
        return;
    }
    
    /**
     * 自定义异常处理
     * 
     * @return void
     */
    public function exception()
    {
    	echo "在空间：" . __NAMESPACE__ . "可使用异常对象\n";
    	var_dump($this->exception);;
        return;
    }
    
    /**
     * 单例
     * 
     * @return void
     */
    public function locator()
    {
    	echo "在空间：" . __NAMESPACE__ . "可使用单例构造器对象\n";
    	var_dump($this->locator);
        return ;
    }
}