<?php
namespace ctrl;

/**
 * 测试  实例 类 
 * 
 * @author
 */
use service\Crontab;

use service\Client;

use framework\AOP;



class Test extends CtrlBase
{
	function writeData($path, $mode, $data)
	{ 
		$fp = fopen($path, $mode); 

		$retries = 0; 
		$max_retries = 100; 
		do { 
			if ($retries > 0) {
				usleep(rand(1, 10000)); 
			} 
			$retries += 1; 
		}while (!flock($fp, LOCK_EX) and $retries <= $max_retries); 

		if ($retries == $max_retries) { 
			return false; 
		} 
		fwrite($fp, "$data\n"); 
		flock($fp, LOCK_UN);
		fclose($fp); 
		return true; 
	}
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * 主函数
	 * 
	 * @return void
	 */
	public function main()
	{
		
		
		
		$path = realpath(dirname(__FILE__)) . '/test.log';
		$mode = 'wb';
		$data = "this is a test";
		$this->writeData($path, $mode, $data);
		exit;
		
		$a = 'adfdfaskkd.php';
		print_r(substr($a, 5, 3));exit;
		
		
		$a = 'this is a test';

		print_r(count_chars($a));exit;
		
		$b = base64_encode($a);
		print_r(base64_decode($b));exit;		
		
		
		$a = array(1, 2, 3, 2, 5, 'a','c');
		
		$b = array(1,2,3,5,'b');
		
		
		$a = array('a'=>1,'b'=>1,'c'=> 2);
		$k = array_search(2, $a);
		
	
		
print_r($k);exit;
		exit;
		
		
		
		
		
//		print_r(runtime());
	$type = 15;
		switch ($type) {
			case 1: // 调通框架
				return $this->run();
				break;
            case 2: // 缓存
                $this->cache(); 
                break;
            case 3: // 参数
                $this->param();
                break;
            case 4: // 调用组件
                $this->useAOP();
                break;
            case 5: // 异常处理
                $this->exception(); 
                break;
            case 6: // 性能分析
                $this->x(); 
                break;
            case 7: // 数据库
                $this->db();
                break;
            case 8: // 视图
                $this->view();
                break;
            case 9: // 单例
                $this->locator();
                break;
            case 10: // class - 示例
                $this->classInstantiate();
                break;
            default:
            	$this->test();
            	break;                                                     	
		}
		exit;
		return ;
    //  makeClass('wangwei');

	}
	
   /**
     * 调通框架
     * 
     * @return void
     */
    public function run()
    {
    	echo "进入ctl层\n";
    	echo "请求参数:\n";
    	$param = $this->parms;
    	var_dump($param);
    	echo "运行情况:\n";
    	print_r(runtime());
    	echo "框架标准:\n";
    	$frame = $this->frame;
    	print_r($frame);
    	$testSv = $this->locator->getService('Test');
    	$testSv->run(1, 'string2', array(3));
    	echo "框架已调通!\n";
    	echo "返回结果!\n";
        return array('a' =>1, 'b' => 2, '#'=> 3);
    }
    
   /**
     * 使用组件-aop切面编程
     * 
     * @return void
     */
    public function useAOP()
    {
        $http = new \service\HTTP();
        $tcp  = new \service\TCP();
        $udp  = new \service\UDP();
        $ipx  = new \service\IPX();
        $aop = AOP::add(array('protocol' => array($http, $tcp, $udp, $ipx)));
        $result = $aop->test("start:\n");
        var_dump($result);
        return ;
    }
    
   /**
     * 缓存
     * 
     * @return void
     */
    public function cache()
    {
    	echo "在空间：" . __NAMESPACE__ . "不能使用缓存\n";
    	var_dump($this->cache);
    	$testSv = $this->locator->getService('Test');
    	$testSv->cache();
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
    	throw new $this->exception("异常测试");
    	$testSv = $this->locator->getService('Test');
    	$testSv->exception();
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
    	$testSv = $this->locator->getService('Test');
    	$testSv->locator();
        return ;
    }
    
   /**
     * 参数
     * 
     * @return void
     */
    public function param()
    {
    	echo "在空间：" . __NAMESPACE__ . "可调用参数\n";
    	var_dump($this->parms);
    	$testSv = $this->locator->getService('Test');
    	$testSv->param();
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
     	$testSv = $this->locator->getService('Test');
    	$testSv->db();
        return;
    }
    
    /**
     * 视图
     * 
     * @return void
     */
    public function view()
    {
    	echo "在空间：" . __NAMESPACE__ . "可使用视图对象\n";
    	$view = new $this->view;
     	$testSv = $this->locator->getService('Test');
    	$testSv->view();
        return;
    }
    
    /**
     * 性能分析工具
     * 
     * @return void
     */
    public function x()
    {
    	echo "在空间：" . __NAMESPACE__ . "使用性能分析工具\n";
    	X('test'); // 性能分析工具
        return ;
    }
    
    /**
     * class 示例
     * 
     * @return void
     */
    public function classInstantiate()
    {
    	// 以单例的形式获取
    	$clientSv = $this->locator->getService('Client');
    	// 以常规类的发生获取
    	$clientSv = new Client();
    	#var_dump($clientSv);exit;
        return ;
    }
    
    /**
     * crontab 示例
     * 
     * @return void
     */
    public function crontab()
    {
    	$crontab = new Crontab();
		$showlog1 = function() {
            for ($i = 0; $i < 10; $i ++) {
                error_log(date("Y-m-d H:i:s") . " One car come one car go,two car bang~ bang~ one man die!\n", 3,
                    "/tmp/test.cron1.log");
                sleep(1);
            }
        };
        $showlog2 = function() {
            error_log(date("Y-m-d H:i:s") . " Oppa gangnam style!\n", 3,
                    "/tmp/test.cron2.log");
        };

        $crontab->addTask("car", "* * * * *", $showlog1);// 每分钟执行一次，每次执行10秒
        $crontab->addTask("per2min", "*/2 * * * *", $showlog2);// 每两分钟执行一次
        $crontab->execute();
        return ;
    }
    
	/**
     * 后台队列功能入口
     *
     * @return bool
     */
    public function queue()
    {
        $showlog = function() {
            error_log(date("Y-m-d H:i:s") . " haha\n", 3, "/tmp/test.queue.log");
        };
        $showlog2 = function() {
            error_log(date("Y-m-d H:i:s") . " hoho\n", 3, "/tmp/test.queue2.log");
        };
        $q = new \service\Queue("10999");
        $q->addTask('yourTaskName', $showlog);
        $q->addTask('yourTaskName2', $showlog2, 100000);
        $q->execute();
        return false;
    }
    
function is_utf8($word)
{
 if (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$word) == true)
 {
 return true;
 }
 else
 {
 return false;
 }
}
    
	/**
     * 测试方法
     *
     * @return bool
     */
    public function test()
    {
    	header("content-type;text/html;charset=utf8");
    	$text = "鏁板瓧鏉傚織"; 
    echo $text;
    exit;	
    $e=mb_detect_encoding($text, array('UTF-8', 'GBK'));
    
    print_r($e);exit;
switch($e){
case 'UTF-8' : //如果是utf8编码
break;
case 'GBK': //如果是gbk编码
break;
}
    	
    	$string = "不要迷恋哥"; 
    	
   // 	$a = iconv("UTF-8", "GB2312", "$string"); 
    		$a = iconv("GB2312", "UTF-8", $string); 
    print_r($a);exit;	
    	
$length = strlen($string); 
for($i=0;$i<$length;$i++){ 
if(ord($string[$i])>127){ 
$result[] = ord($string[$i]).' '.ord($string[++$i]); 
} 
} 
var_dump($result); exit;
    	
    	
    	
    //	header("content-Type: text/html; charset=Utf-8");
    header("content-Type: text/html; charset=gb2312");  
echo mb_convert_encoding("你是我的好朋友","UTF-8","GBK");
exit;
    	
    	$char = '鐢靛瓙涔?';

echo ord($char), "\n";

$char = decbin(ord($char));

echo $char;
exit;
    	
    	
    	$word = "电子书刊";
    	
    	$a = $this->is_utf8($word);
    	
   print_r($a);exit;	  	
    	if(is_utf8($liehuo)==1) { 
			$liehuo = iconv("utf-8","gbk",$liehuo); 
		}
    	$liehuo = iconv("utf-8","gbk",$word); 
    	
    	print_r($liehuo);exit;
    $encode = mb_detect_encoding($keytitle, array('ASCII', 'GB2312', GBK, UTF-8)); 
    
   // $keytitle = iconv("UTF-8″, GBK, $keytitle); 
    	print_r($encode);exit;
        exit;
    }
}