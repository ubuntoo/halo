<?php
/**
 * 外挂模拟器
 * 
 * @author
 */

define('RABOT_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('RABOT_LIB', RABOT_ROOT . 'lib' . DIRECTORY_SEPARATOR);
define('RABOT_CACHE', RABOT_ROOT . 'cache' . DIRECTORY_SEPARATOR);
require_once RABOT_ROOT . 'config.php';
class Rabot
{
	/**
	 * 运行方式：web
	 * 
	 * @var int
	 */
	const RABOT_RUN_TYPE_WEB = 0;
	/**
	 * 运行方式：shell
	 * 
	 * @var int
	 */
	const RABOT_RUN_TYPE_SHELL = 1;	
	/**
	 * 请求类型：get
	 * 
	 * @var int
	 */
	const RABOT_REQUEST_TYPE_GET = 1;
	/**
	 * 请求类型：post
	 * 
	 * @var int
	 */
	const RABOT_REQUEST_TYPE_POST = 2;
	/**
    	 * array('域名1'=> array
    	 *     array( //任务1
    	 *     		'op' => ,
    	 *     		'参数'    => ,
    	 *     		'开始时间' => ,
    	 *     		'结束时间' => ,
    	 *     		'时间间隔' => ,
    	 *     		'回调函数' => ,
    	 *     		'请求方式' => ,
    	 *     ),	 
    	 *     
    	 *     array( //任务2
    	 *     		'op' => ,
    	 *          '参数'    => ,
    	 *     		'开始时间' => ,
    	 *     		'结束时间' => ,
    	 *     		'时间间隔' => ,
    	 *     		'回调函数' => ,
    	 *     		'请求方式' => ,
    	 *     ),	
    	 * ),
    	 * '域名2'=> array 
    	 * ),
    	 */
	private $taskList = null; // 任务列表
	
	/**
     * 构造函数
     * 
     * @param
     * 
     * @return 
     */
    public function __construct()
    { 	
    //	include_once RABOT_ROOT . 'spider.php';
    	//exit;
  // $socketClient = new socketClient(RABOT_HOST, RABOT_PORT);
    //	$socketServer = new socketServer(RABOT_HOST, RABOT_PORT);
        return;
    }
    
    /**
     * 注册
     * 
     * @param	array	$task	任务列表
     * 
     * @return	array
     */
    public function register($task) {
    	isset($task['url']) or $task['url'] = RABOT_DEFAULT_URL;
    	isset($task['requestType']) or $task['requestType'] = self::RABOT_REQUEST_TYPE_GET;
    	if (isset($task['task']) && is_array($task['task'])) {
    		isset($this->taskList[$task['url']]) or $this->taskList[$task['url']] = array();
    		foreach ($task['task'] as $op => $param) { 				
    			$this->taskList[$task['url']][] = array(
    				'op'          => $op,
    				'requestType' => $task['requestType'],
    				'param'       => isset($param['param']) ? $param['param'] : array(),
    				'startTime'   => $task['startTime'],
    				'times'       => isset($param['times']) ? max(0, $param['times']) : 1,
    				'callback'    => $task['callback'],
    				'interval'    => isset($param['interval']) ? max(0, $param['interval']) : 0,
    			);
    		}
    	}
    	return $this->taskList;
    }
    
	/**
     * 运行
     */
    public function start() {
    	if (empty($this->taskList)) {
    		die("请先添加任务\n");
    	}	
    	foreach ($this->taskList as $url => $tasks) {
    		foreach ($tasks as $task) {   			
    			$this->simulatingRequest($url, $task['param'], $task['requestType'], 
    				array('interval' 	=> $task['interval'],
    					'times'     => $task['times'],
    					'startTime' => $task['startTime'],
    					'endTime' 	=> $task['endTime'],
    					'callback' 	=> $task['callback']));
    		}
    	}
    	return ;
    }
    
    /**
     * 模拟请求
     * 
     * @param	string		$url	域名
     * @param	array		$param	参数列表
     * @param	int			type	请求类型
     * @param	array		info	请求信息
     * 
     * @return
     */
    public function simulatingRequest($url, $param = array(), 
    	$type = self::RABOT_REQUEST_TYPE_SHELL, $info = array()) {
    	// saveXhprof($url);
        $urlInfo = parse_url($url);   	
        $query = http_build_query($param);         
    	empty($urlInfo['query']) or $query .= '&' . $urlInfo['query'];
    	switch ($type) {
			case self::RABOT_REQUEST_TYPE_GET :
				$head = 'GET ' . $urlInfo['path'] . '?' . $query . " HTTP/1.0\r\n";  
				$head .= 'Host: ' . $urlInfo['host'] . "\r\n";
				$head .= "\r\n";
				break;
			case self::RABOT_REQUEST_TYPE_POST :
				$head = 'POST ' . $urlInfo['path'] . " HTTP/1.0\r\n";
				$head .= 'Host: ' . $urlInfo['host'] . "\r\n";  
				$head .= 'Referer: http://' . $urlInfo['host'] . $urlInfo['path'] . "\r\n";  
				$head .= "Content-type: application/x-www-form-urlencoded\r\n";  
				$head .= 'Content-Length: ' . strlen(trim($query)) . "\r\n";  
				$head .= "\r\n";  
				$head .= trim($query);
				break;
			default:
				header("location:".$url);
				break;	
		}
		$price = new price();
$keywords = '诺基亚900';		
		$result = $price->getGoodsDetails($keywords);
		
		print_r($price);exit;


		
// 测试一下，curl 三个网址
$array = array(
	"http://www.weibo.com/",
	"http://www.renren.com/",
	"http://www.qq.com/"
);
$data = $this->curlHttp($array, '10');//调用
var_dump($data);//输出		
exit;		
		$pause = $info['interval'] ? true : false;
    	$callback = $info['callback'] ? true : false;
    	while ($info['times'] > 0) {
    		$requestStart = microtime(true);        // 请求开始时间
			$requestStartMem = memory_get_usage();  // 请求开始内存
			// 连接服务器	
			$fp = fsockopen($urlInfo['host'], 80, $errno, $errstr, 3);		
			socket_set_nonblock($fp);
			$write = fputs($fp, $head);
			$content = array(); //请求结果
			while (!feof($fp)) {
				$content[] = fgets($fp);		
			}
			$requestEnd = microtime(true);       // 请求结束时间
			$requestEndMem = memory_get_usage(); // 请求结束内存
			$useTime = $requestEnd - $requestStart;      // 请求耗时
			$useMem = $requestEndMem - $requestStartMem; // 请求耗时
			$info['times']--;
			$pause and usleep($info['interval']);	
			$callback and call_user_func($info['callback']); // 回调函数
            // 组装执行结果
		}
		return $content;
    }
   

   /** 
     * curl 多线程 
     *  
     * @param array $array 并行网址 
     * @param int $timeout 超时时间
     * @return array 
     */ 
    function curlHttp($array, $timeout){
    	$urls = array('京东商城'=>'http://search.360buy.com/Search?keyword=诺基亚900&enc=utf-8&area=15');
    	
    	
   	 	$multiHandle = curl_multi_init(); // 创建多个curl语柄
		$connects = array();
		foreach ($urls as $name => $url) {
			$connects[$name] = curl_init($url);
        	curl_setopt($connects[$name], CURLOPT_TIMEOUT, $this->timeout); // 设置超时时间
        	curl_setopt($connects[$name], CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        	curl_setopt($connects[$name], CURLOPT_MAXREDIRS, 7); // HTTP定向级别
			curl_setopt($connects[$name], CURLOPT_HEADER, 0); // 这里不要header，加块效率
			curl_setopt($connects[$name], CURLOPT_FOLLOWLOCATION, 1); // 302redirect
        	curl_setopt($connects[$name], CURLOPT_RETURNTRANSFER, 1);   	
        	curl_multi_add_handle($multiHandle, $connects[$name]);
    	}
    	
    	print_r($connects);exit;
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
	 	$res = array();
	 	$mh = curl_multi_init();// 创建多个curl语柄

	 	$startime = getmicrotime();
		$conn = array();
	 	foreach($array as $k=>$url){
	 		$conn[$k]=curl_init($url);
	        curl_setopt($conn[$k], CURLOPT_TIMEOUT, $timeout);//设置超时时间
	        curl_setopt($conn[$k], CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	        curl_setopt($conn[$k], CURLOPT_MAXREDIRS, 7);//HTTp定向级别
	        curl_setopt($conn[$k], CURLOPT_HEADER, 0);//这里不要header，加块效率
	        curl_setopt($conn[$k], CURLOPT_FOLLOWLOCATION, 1); // 302 redirect
	        curl_setopt($conn[$k], CURLOPT_RETURNTRANSFER, 1);
	        curl_multi_add_handle ($mh, $conn[$k]);
	 	}
	 	
print_r($conn);exit;	 	
		//防止死循环耗死cpu 这段是根据网上的写法
		do {
			$mrc = curl_multi_exec($mh, $active);//当无数据，active=true
		} while ($mrc == CURLM_CALL_MULTI_PERFORM);//当正在接受数据时
		
		while ($active and $mrc == CURLM_OK) {//当无数据时或请求暂停时，active=true
			if (curl_multi_select($mh) != -1) {
				do {
					$mrc = curl_multi_exec($mh, $active);
				} while ($mrc == CURLM_CALL_MULTI_PERFORM);
			}
		}
	 	foreach ($array as $k => $url) {
	 		curl_error($conn[$k]);
	    	$res[$k]=curl_multi_getcontent($conn[$k]);// 获得返回信息
	    	$header[$k]=curl_getinfo($conn[$k]); // 返回头信息
	    	curl_close($conn[$k]);//关闭语柄
	    	curl_multi_remove_handle($mh, $conn[$k]);   //释放资源  
		}
		curl_multi_close($mh);
		$endtime = getmicrotime();
		$diff_time = $endtime - $startime;	
		return array('diff_time' => $diff_time,
					 'return'    => $res,
					 'header'    => $header		
				);
	 	
	 }
	 
	 function taoBaoJiaGe($url){ //<li class='price'><em>
    	preg_match('/&lt;strong id="J_StrPrice" &gt;\d+.\d{2}/',file_get_contents($url),$jiaGe); //正则表示获取包含价格的 HTML 标签
    	preg_match('/\d+.\d{2}/',$jiaGe[0],$jiaGe); //正则表达式再次从结果中分离价格。
print_r($jiaGe);exit;
    	return $jiaGe[0]; //返回价格
	}
 
    
}

/**
 * 自动加载类
 * 
 * @param 
 * @param void
 */
function __autoload($class) 
{
	include_once RABOT_ROOT . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
	return ;
}

/**
 * Xhprof性能分析 函数
 *
 * @param	string	$nameSpace   	命名空间
 * @param 	int     $minexectime 	最小执行时间（执行时间超过此参数才会记录）
 *
 * @return null
 */
function saveXhprof($nameSpace, $minexectime = 0)
{
    // 开始分析 && 保存分析结果到一个命名空间中，以方便收集和合并等处理
    if (!function_exists('xhprof_enable')) return;
    $xhprofPath = RABOT_LIB . 'xhprof' . DIRECTORY_SEPARATOR;
    $savedPath  = RABOT_CACHE . 'xhprofRuns' . DIRECTORY_SEPARATOR;
    if (!is_dir($savedPath)) {
    	$r = mkdir($savedPath);
        if (!$r || !is_writable($savedPath)) return;
    }
    $GLOBALS['xhprof_stime'] = microtime(); 	    
    $shutdownFunction = function() use ($nameSpace, $xhprofPath, $savedPath, $minexectime) {
        $xhprof_data = xhprof_disable();
        if (isset($GLOBALS['xhprof_stime']) && getUseTime($GLOBALS['xhprof_stime']) > $minexectime) {
            include_once "{$xhprofPath}/xhprof_lib/utils/xhprof_lib.php";
            include_once "/{$xhprofPath}/xhprof_lib/utils/xhprof_runs.php";
            $xhprof_runs = new \XHProfRuns_Default($savedPath);
            $xhprof_runs->save_run($xhprof_data, $nameSpace);
        }
    };
    register_shutdown_function($shutdownFunction);
    xhprof_enable(XHPROF_FLAGS_NO_BUILTINS | XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
}

//计算当前时间
function getmicrotime() {
	    list($usec, $sec) = explode(" ",microtime());
	    return ((float)$usec + (float)$sec);
}