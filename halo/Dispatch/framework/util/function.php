<?php
/**
 * 类自动加载
 * 
 * @param  string  $classname  类名
 * 
 * @return void
 */
function __autoload($class) 
{
	$basePath = str_replace('\\', DS, $class) . '.php';
    $classRoots = array(
        CODE_PATH,
        FRAMEWORK_PATH . '..' . DS,
    );
    $inGoto = false;
tagForCommon:    
    $require = false;
    foreach ($classRoots as $classRoot) {
    	if (is_readable($classRoot . '' . $basePath)) { 		
    		class_exists($class) or include_once $classRoot . '' . $basePath;		
    		$require = true;
            break;
        }
    }
    if (!$require) {
        if (false == $inGoto) {
            $basePath = array_pop(explode(DS, $basePath));
            $classRoots = array(COMMON_CLASS_PATH, COMMON_MODULE_PATH);
            $inGoto = true;       
            goto tagForCommon;
        } 
        // 类[CLASS]不存在
        throw new Framework::$Exception("e.frame.Class '[CLASS]' Not Found", array('CLASS' => $class));
    }
    return true;
}

/**
 * 设置语言包
 * 
 * @param   array|string 	$domains     语言包名称
 * @param   string  		$path        语言文件路径
 * @param   string			$locale      本地语言
 * @param   string			$encode      编码（默认为'UTF-8'）
 * @param   bool			$default     是否设置为主要语言包（默认为false）
 * 
 * @return void
 */
function setLocational($domains, $path, $locale, $encode = "UTF-8", $default = null)
{
    putenv('LANG=' . $locale);
    if (!defined("LC_MESSAGES")) define("LC_MESSAGES", 5);
    setlocale(LC_MESSAGES, $locale);
    if (!is_array($domains)) $domains = array($domains);  
    foreach ($domains as $domain) {   
        bind_textdomain_codeset($domain, $encode);
        bindtextdomain($domain, $path);
        if ($default) {
            textdomain($domain);
            $default = false;
        }
    }
    return ;
}

/**
 * 解析带变量的语言包
 * 
 * @param   string 		$msgStr		语言包名称
 * @param   array		$param      匹配参数
 * @param   string		$domain     本地语言
 * 
 * @return string
 */
function _v($msgStr, $param = null, $domain = null)
{
    if (is_null($param)) $param = array();
    $msg = is_null($domain) ? gettext($msgStr) : dgettext($domain, $msgStr);   
    $keys = array();
    $values = array();
    foreach ($param as $k => $v) {
        $keys[] = "[{$k}]";
        $values[] = $v;
    }    
    return str_replace($keys, $values, $msg);
}

/**
 * 加载文件
 * 
 * @param 	string  	$fileName     文件名或文件路径或目录路径
 * @param   string		$dirPath      目录路径
 * 
 * @return boolen
 */
function loadFile($file, $dirPath = null) 
{		
	if (!is_array($file)) {
		$file = array($file); // 单一的文件 或者文件路径
	}	
	$files = array();
	foreach ($file as $fileOne) {	
		if (is_file($fileOne)) { // 文件路径
			$files[] = $fileOne;
		}	
		if (is_dir($fileOne)) { // 目录路径
			$filenames = scandir($fileOne);	
			for ($i = 0, $count = count($filenames); $i < $count; $i++) {
				$filename = $filenames[$i];				
				if (is_file($fileOne . DS . $filename) && preg_match('/.php$/', $filename)) {
					$files[] = $fileOne . DS . $filename;
				}	
			}
		}		
		if ($dirPath && $fileOne) {	
			if (is_readable($dirPath . $fileOne . '.php')) {
				$files[] = $dirPath . $fileOne . '.php';
			}
		}
	}	
	$requireNum = 0;
	foreach ($files as $file) {
		if (is_readable($file)) {	
			require_once $file;
			$requireNum ++ ;
		}
	}
	return $requireNum > 0 ? true : false;
}

/**
 * 异常处理
 * 
 * @param  Exception  $exception  异常实体
 * 
 * @return void
 */
function exceptionHandler(Exception $exception)
{
	// 请求OP
	$clientOP = \Framework::$Dispatcher ? Framework::$Dispatcher->getOp() : null;   
	// 是否数据库错误
    $isDbError = ($exception instanceof PDOException);
    $cout = array(
        'act'    => $clientOP,
        'errstr' => $isDbError ? _v('e.dberror') : $exception->getMessage()
    );
    if (ini_get('display_errors')) {
        $cout['debug'] = array(
            'exceptionType'	=> get_class($exception),
            'message'       => $exception->getMessage(),
            'code'        	=> $exception->getCode(),
            'file'        	=> $exception->getFile(),
            'line'       	=> $exception->getLine(),
            'traceString' 	=> $exception->getTraceAsString()
        );
    }
    if ($isDbError) {	
    }
    // T#1 系统错误提示模板 
    $view = new \Framework::$View();  
    $view->display('1', $cout);
    return ;
}

/**
 * 是否可遍历
 * 
 * @param 	mix  $mixedValue   参数
 * 
 * @return boolen
 */
function is_iteratable(&$mixedValue)
{
    return (is_array($mixedValue) || is_object($mixedValue));
}

/**
 * 递归转义
 * @param  array	$_value		参数
 *  
 * @return void
 */
function recursion_addslashes(&$_value)
{
    if (empty($_value)) return;
    if (is_iteratable($_value)) foreach ($_value as &$_val) {
    	recursion_addslashes($_val);
    } else {
    	$_value = addslashes($_value);
    }
    return ;
}

/**
 * 执行效率分析函数 开始
 *  
 * @return void
 */
function _s()
{
	$GLOBALS['logStartTime'] = microtime();
	return ;
}

/**
 * 执行效率分析函数  结束
 * 
 * @return void
 */
function _e()
{
	list($usecS, $secS) = explode(" ", $GLOBALS['logStartTime']);
	list($usecE, $secE) = explode(" ", microtime());
	$timeSpent = ((float)$usecE + (float)$secE) - ((float)$usecS + (float)$secS);
	echo "spent $timeSpent seconds\n";
	return ;
}

/**
 * 计算执行时间
 *
 * @param  int  $startTime  开始时间
 * 
 * @return int
 */
function getUseTime($startTime)
{
    $endTime = microtime();
    $s = explode(" ", $startTime);
    $e = explode(" ", $endTime);
    $useTime = ($e['0'] + $e['1']) - ($s['0'] + $s['1']);
    return $useTime;
}

/**
 * Xhprof性能分析 函数
 *
 * @param   string	$nameSpace		命名空间
 * @param   int		$minexectime    最小执行时间（执行时间超过此参数才会记录）
 *
 * @return void
 */
function X($nameSpace, $minexectime = 0)
{
    // 开始分析 && 保存分析结果到一个命名空间中，以方便收集和合并等处理
    if (!function_exists('xhprof_enable')) return;
    $xhprofPath = LIB_PATH . "xhprof" . DS;
    $savedPath  = CACHE_PATH . "xhprofRuns" . DS;
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
    return ;
}

/**
 * 获取资源消耗
 * 
 * @return array
 */
function runtime()
{
    $return = array();  
    if (isset($GLOBALS['runtime'])) {
        $return = array(
            'time' => number_format((microtime(true) - $GLOBALS['runtime']['startTime']), 4) . 's',
            'memory' => number_format((array_sum(explode(' ', memory_get_usage())) - array_sum(explode(' ', $GLOBALS['runtime']['memoryUsage'])))/1024) . 'kb',
        );
    }
    return $return;
}