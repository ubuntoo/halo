<?php
namespace service;

/**
 * 测试  逻辑 类 
 * 
 * @author
 */
class Test extends ServiceBase
{
	
	/**
	 * 运行框架
	 * 
	 * @param  int 		param1    参数1
	 * @param  string   param2    参数2
	 * @param  array    param3    参数3
	 * 
	 * @return void
	 */
    public function run($param1, $param2, $param3) {
    	echo "进入service层\n";
    	echo "运行情况:\n";
    	print_r(runtime());
    	echo "框架标准:\n";
    	$frame = $this->frame;
    	print_r($frame);
    	$testDao = $this->locator->getDao("Test");
    	$testDao->run($param1, $param2, $param3);
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
     	$testDao = $this->locator->getDao('Test');
    	$testDao->view();
        return;
    }
	
    /**
	 * 缓存
	 * 
	 * @return void
	 */
    public function cache() {
    	echo "在空间：" . __NAMESPACE__ . "不能使用缓存\n";
    	var_dump($this->cache);
    	$testDao = $this->locator->getDao('Test');
    	$testDao->cache();
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
    	$testDao = $this->locator->getDao('Test');
    	$testDao->param();
        return ;
    }
    
    /**
     * 数据库
     * 
     * @return void
     */
    public function db()
    {
    	echo "在空间：" . __NAMESPACE__ . "不能使用数据库操作对象\n";
    	var_dump($this->daoHelper);
     	$testDao = $this->locator->getDao('Test');
    	$testDao->db();
        return ;
    }
	
    /**
     * 自定义异常处理
     * 
     * @return void
     */
    public function exception()
    {
    	echo "在空间：" . __NAMESPACE__ . "可使用异常对象\n";
    	var_dump($this->exception);
    	$testDao = $this->locator->getDao('Test');
    	$testDao->exception();
        return ;
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
    	$testDao = $this->locator->getDao('Test');
    	$testDao->locator();
        return ;
    }
	
	
   /**
     * 示例  获取实体
     * 
     * @return Object
     */
    public function testGetNewEntity() 
    {
        $testDao = $this->locator->getDao("Test");
        $testEntity = $testDao->getNewEntity();
        e($testEntity);exit;
        return $testEntity;
    }
	
   /**
     * 示例  增
     */
    public function testCreate() 
    {
        $testDao = $this->locator->getDao("Test");
        $testEntity = $testDao->getNewEntity();
        $testEntity->testFieldOne   = 'fieldOneValue';
        $testEntity->testFieldtwo   = '2';
        $testEntity->testFieldthree = '3';
        $testEntity->testFieldfour  = '4';
        $testEntity->testFieldFive  = '5';      
        $testDao->create($testEntity);
        echo "创建实体成功";
    }
    
   /**
     * 示例  删
     */
    public function testRemove() 
    {
    	$testDao = $this->locator->getDao("Test");
    	$id = 2;
        $testEtt = $testDao->read($id);
        $testDao->remove($testEtt);       
    }
    
   /**
     * 示例  读
     */
    public function testRead() 
    {
        $testDao = $this->locator->getDao("Test");
        $id = 1;
        $testEtt = $testDao->read($id);
        $testEtt = $testDao->read($id, 'testId');
        $testEtt = $testDao->read($id, array('testId'));
        $testEtt = $testDao->read($id, array('testId', 'testFieldFive'));
        $testEtt = $testDao->read($id, 'testId,testFieldFive');
        
        $where = "testId = 2";
        $where = 'testId=13, testFieldFive=777';
        $where = '13, testFieldFive=777';
        $where = array(3);
        $where = array('testId = 2');
        $where = array('testId' => 5);
        $where = array(
            13,
            'testFieldFive' => 777
        );
        $where = array(
            'testId'        => 13,
            'testFieldFive' => 777
        );
        $where = array('testId=13', 'testFieldFive=777');
        $where = array('testFieldFive=777', 13);
        $testEtt = $testDao->readList($where);
        print_r($testEtt);
    }
    
   /**
     * 示例  改
     */
    public function testUpdate() 
    {
        $testDao = $this->locator->getDao("Test");
        $id = 3;
        $testEtt = $testDao->read($id);
        $testEtt->set('testFieldOne', 'change');
        $testEtt->add('testFieldthree', 10);
        $testDao->update($testEtt);
        exit;  
    }
}