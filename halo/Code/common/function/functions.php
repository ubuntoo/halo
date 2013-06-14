<?php
/**
 * 类自动生成
 * 
 * @param string  $className 类名
 * @param string  $namespace 空间名首字母
 * 
 * @return void
 */
function makeClass($className, $namespace = null) 
{
	$namespaces = array_intersect(str_split('csde'), array_unique(str_split(strtolower($namespace))));
	$namespaces or $namespaces = str_split('csde');
	$names = array('c' => 'ctrl',
				   's' => 'service',
				   'd' => 'dao',
				   'e' => 'entity',
			);
	foreach ($namespaces as $namespace) {
		switch ($namespace) {
			case 'c' : $namespaceDir = CTRL_PATH;
					   $classBase = 'CtrlBase';
					   $des = '控制器';
					   break;
			case 's' : $namespaceDir = SERVICE_PATH;
					   $classBase = 'ServiceBase';
					   $des = '逻辑';
					   break;
			case 'd' : $namespaceDir = DAO_PATH;
					   $classBase = 'DaoBase';
					   $des = '数据库';
					   break;
			case 'e' : $namespaceDir = ENTITY_PATH; 
			  		   $classBase = 'EntityBase';
			  		   $des = '实体';
			  		   break;
			default: continue;
		}
		$file = $namespaceDir . $className . '.php';
		if (file_exists($file)) {
			continue;
		}
		$content = array();
		$content[] = '<?php';
		$content[] = "namespace {$names[$namespace]};";
		$content[] = '';
		$content[] = '/**';
		$content[] = " * {$className} {$des}类";
		$content[] = ' * ';
		$content[] = " * @author wei.wang";
		$content[] = ' */';
		$content[] = $classBase ? "class {$className} extends {$classBase}" : "class {$className}";
		$content[] = '{';
		if ($namespace != 'e') {
		    $content[] = "    /**";
            $content[] = "     * 主方法";
            $content[] = "     *";
            $content[] = "     * @return ";
            $content[] = "     */";
            $content[] = "    public function main()";
            $content[] = "    {";
            $content[] = "        return ;";
            $content[] = "    }";
		}
	    $content[] = "}";	
	   	file_put_contents($file, implode("\n", $content));
	    @chmod($file, 0777);
	    echo "created class $names[$namespace]\\\\$className \n";
	}
	return ;
}

/**
 * 构造实体
 * 
 * @param string $table      数据表名
 * @param string $className  类表名
 * 
 * @return void
 */
function createEntity($table, $className) 
{
	$className = ucfirst($className);
    @unlink(ENTITY_PATH . $className . '.php');
    makeClass($className, 'e');
    $file = explode("\n", file_get_contents(ENTITY_PATH . $className . '.php'));   
    while (end($file) != '}') {
       array_pop($file);
       break;
    }
    array_pop($file);   
    $daoHelper = &Framework::$DaoHelper;
    $columns = $daoHelper->fetchBySql("SHOW FULL COLUMNS FROM `{$table}`");
    $fields = array();
    $priProp = null;
    foreach ($columns as $column) {
        $Field   = '$' . $column->Field;
        $Extra   = $column->Extra;
        $Comment = $column->Comment;
        if ($column->Key == 'PRI') {
            $priProp = $column->Field;
        }
        $fields[] = "    /**";
        $fields[] = empty($Comment) ? "    *字段" :  "     * {$Comment}";
        $fields[] = "     *";
        $fields[] = "     * @var " . preg_replace('(\(\d+\))', '', $column->Type);
        $fields[] = "     */";
        $fields[] = "    protected {$Field};";
        $fields[] = "";     
    }
    $head = array();
    $head[] = "    /**";
    $head[] = "     * 主表";
    $head[] = "     *";
    $head[] = "     * @var string";
    $head[] = "     */";
    $head[] = "    const MAIN_TABLE = '{$table}';";
    if ($priProp) {
        $head[] = ""; 
        $head[] = "    /**";
        $head[] = "     * 主键";
        $head[] = "     *";
        $head[] = "     * @var int";
        $head[] = "     */";
        $head[] = "    const PRIMARY_KEY = '{$priProp}';";
        $head[] = ""; 
    }
    $file = array_merge($file, $head, $fields);
    $file[] = "}"; 
    file_put_contents(ENTITY_PATH . $className . '.php', implode("\n", $file));
    echo "created entity\\\\$className \n";
    return ;
}

/**
 * 调试
 * 
 * @param var       $var        参数
 * @param boolen    $isWrite    是否写入
 * @param boolen    $isShow     是否显示
 * 
 * @return void
 */
function e($var = null, $isWrite = false, $isShow = true) 
{
	$path = CACHE_PATH . 'log' . DS . 'ww.log';
	if (is_null($var)) {
		echo "Here~!\n";
	} elseif (is_null($isWrite)) {
		print_r($var);
		echo "\n";
	} else {
		error_log($var, 3, $path);
		if ($isShow) {
			print_r($var);
			echo "\n";
		}
	}
	return ;
}

/**
 * 计算目录的大小
 * 
 * @param  $dirPath string 目录路径
 * 
 * @param  int
 */
function getDirSize($dirPath) {
    $size = 0;
    $dir = @opendir($dirPath);
    if (!$dir) {
        return -1;
    }
    while (($file = readdir($dir)) !== false) {
        if ($file['0'] == '.') continue;
        if (is_dir($dirPath . $file)) {
            $size += getDirSize($dirPath . $file . DS);
        } else {
            $size += filesize($dirPath . $file);    
        }
            
    }
    @closedir($dir);
    return $size;
}