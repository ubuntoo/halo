<?php
namespace service;

/**
 * 队列系统
 *
 */
class Queue
{
	/**
	 * 消息队列权限
	 * 
	 * @var string
	 */
	const MESSAGE_QUEUE_PERMISSION = 0666;
	
	/**
	 * 需要监控的信号列表
	 * 
	 * @var array
	 */
	private static $monitorSignoList = array(SIGINT, SIGTERM, SIGHUP);
	
	/**
     * 任务列表
     *
     * @var array
     */
	private $taskList = array();
	
    /**
     * 任务队列状态列表[name => [pid,taskname,starttime,lastping]]
     *
     * @var array
     */
    protected $taskStatusList = array();
	
	/**
     * 当前时间
     *
     * @var int
     */
	private $now = null;
	
	/**
     * 端口号
     *
     * @var int
     */
	private static $port = null;
	
	/**
     * 消息队列的key
     *
     * @var int
     */
	private static $msgQueueKey = null;
	
	/**
     * 消息队列
     *
     * @var resource
     */
	private static $msgQueue = null;
	
	/**
     * 当前主进程id
     *
     * @var int
     */
	private static $mainQueuePid = null;
	
	/**
     * 当前的sock连接
     *
     * @var resource
     */
	private static $sock = null;
	
	/**
     * 当前连接的sock列表
     *
     * @var resource
     */
	private static $linkedSockList = array();
	
	/**
     * 当前接收到的信号
     *
     * @var int
     */
	private static $signo = null;
	
	/**
	 * 构造函数-初始化环境
	 * 
	 * @param int $port 进程绑定的端口号
     * 
     * @return void
     */
    public function __construct($port = null)
    {
    	$port = null;
    	if (is_null($port)) {
    		$port = fileinode(__FILE__);		
    		do {
    			$fs = @fsockopen("127.0.0.1", $port, $errno, $errstr, 10);
    			if (!$fs) break;
    			fclose($fs);				
    			$port ++;
    		} while ($port);
    	} 	
    	self::$port = $port;
        self::$mainQueuePid = posix_getpid();
        self::$msgQueueKey = ftok(__FILE__, 'a');
    }
    
    /**
     * 添加任务
     *
     * @param 	string   	$name     	任务名称
     * @param 	callback 	$callback	 任务回调
     * @param 	int      	$interval 	任务间隔（单位：微秒，默认为1000000，即1秒）
     *
     * @return void
     */
    public function addTask($name, $callback, $interval = 1000000)
    {
        $this->taskList[$name] = array(
                'name'     => $name,
                'callback' => $callback,
                'interval' => $interval,
            );
        return $this->taskList;  
    }
    
    /**
     * 输出格式化后的提示信息
     *
     * @param string $msg 提示信息
     *
     * @return void
     */
    protected function showPromptMsg($msg)
    {
        echo date("[Y-m-d H:i:s]", self::$now) . ' [PID:' . posix_getpid() . '] ' . $msg . PHP_EOL;
    }
    
    /**
     * 绑定端口并建立sock连接
     *
     * @param int $port 端口号
     *
     * @return resource
     */
    protected function bindPort($port)
    {
        $sock = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);   
        if (!(@socket_bind($sock, "127.0.0.1", $port) && @socket_listen($sock))) {
           $this->showPromptMsg("Bind port[{$port}] failed! Exit!");
           socket_close($sock);
           exit; 
        }
        return $sock;
    }
    
    /**
     * 信号处理器
     *
     * @param int $signo 信号常量
     *
     * @return void
     */
    protected function signalHandler($signo)
    {
        self::$signo = $signo;
        echo "抓到一个信号\n";
    }
    
   /**
     * 进程发送心跳包
     *
     * @param	int    	$pid    	进程ID
     * @param 	string	$taskName 	任务名称
     *
     * @return void
     */
    protected function ping($pid, $taskName = null, $pingtime = null)
    {
        // 发送心跳消息
        if ($pid != self::$mainQueuePid) {
            $lastping = is_null($pingtime) ? self::$now : $pingtime;
            try {
            	$messageType = 1;
                $this->sendMsg($messageType, array(
                    "pid"      => $pid,
                    "taskname" => $taskName,
                    "lastping" => $lastping,
                ));
            } catch (Exception $e) {
                $this->showPromptMsg("message queue write error!");
                exit;
            }
        } else {
            // 主进程：监控进程
            while (true) {
            	$msgtype = null;
            	$maxsize = 1024;
            	$desiredmsgtype = 0;
            	$message = $this->receiveMsg($msgtype, $maxsize, $desiredmsgtype);
            	if (empty($message)) {
            		break;
            	}
                if (!empty($message['taskname']) && !empty($message['lastping'])) {
                    $this->taskStatusList[$message['taskname']]['lastping'] = $message['lastping'];
                }
            }
            
            // 关闭长时间未更新心跳时间的进程
            foreach ($this->taskStatusList as $name => $status) {
                // 如果心跳包超时60秒，则发送终止命令并重新启动
                if ($status['lastping'] + 60 < self::$now) {
                    posix_kill($status['pid'], SIGTERM);
                    unset($this->taskStatusList[$name]);
                }
                continue;
            }
        }
        return ;
    }
    
    /**
     * 开始执行队列
     * 
     * @return void
     */
    public function execute()
    {
    	// 绑定端口，防止重复启动
        if (!is_null(self::$port) && is_null(self::$sock)) {
            // 必须将返回值socket资源赋值给一个变量，否则socket将断开
            self::$sock = $this->bindPort(self::$port);
            socket_set_nonblock(self::$sock);
        }
        // 初始化消息队列
        if (is_null(self::$msgQueue)) {
        	self::$msgQueue = $this->createMsgQueue(self::$msgQueueKey);
        }
        
        // 注册信号处理回调方法
        $signalCallback = array($this, "signalHandler");
        foreach (self::$monitorSignoList as $monitorSigno) {
        	 pcntl_signal($monitorSigno, $signalCallback);
        }     
        // 进程监控循环
        while (true) {
            // 处理信号
            if (!empty(self::$signo) && in_array(self::$signo, self::$monitorSignoList)
            ) {
                $this->showPromptMsg("Signal {self::$signo} catched!");
                break;
            }
            // 更新当前时间戳
            $this->now = time();      
            // 发送心跳包
            $this->ping(self::$mainQueuePid);            
 print_r(self::$mainQueuePid);exit;     
            // 遍历任务列表，处理任务
            foreach ($this->taskList as $name => $task) {
                // 已有PID的任务，跳过
                if (isset($this->taskStatusList[$name])) {
                	continue;
                }
                $childTaskPid = pcntl_fork();  
                if ($childTaskPid == -1) {
                    // fork失败
                    $this->showPromptMsg("Taskname:{$name} could not fork!");
                } else if ($childTaskPid == 0) {
                    // fork出的子进程的执行逻辑
                    if (!posix_setsid()) {
                        $this->showPromptMsg("Taskname:{$name} could not detach from terminal!\n");
                        exit;
                    }
                    // 循环执行Task
                    $this->executeTask($task['name'], $task['callback'], $task['interval']);
                    exit;
                } else if ($childTaskPid > 0) {
                    // 队列主进程的执行逻辑
                    $this->taskStatusList[$name] = array(
                        "pid"       => $childTaskPid,
                        "taskname"  => $name,
                        "starttime" => self::$now,
                        "lastping"  => self::$now,
                    );
                } 
            }
            
            // 接受socket连接
            try {      	
                if (($connection = @socket_accept(self::$sock)) !== false) {
                    $linkKey = count(self::$linkedSockList);
                    self::$linkedSockList[$linkKey] = $connection;
                    $this->showPromptMsg("Socket client[{$linkKey}] connected!");
                    $this->responseSocket($connection, "help");
                }
            } catch (Exception $e) {
            }
 
            // 处理每个socket连接的请求
            foreach (self::$linkedSockList as $linkKey => $lsock) {
                $sockbuf = null;
                try {
                    $rlen = socket_recv($lsock, $sockbuf, 8096, MSG_DONTWAIT);
                    if (empty($rlen)) {
                        unset(self::$linkedSockList[$linkKey]);
                        $this->showPromptMsg("Socket client[{$linkKey}] closed!");
                        socket_close($lsock);
                    } else {
                        $this->responseSocket($lsock, trim($sockbuf));
                    }
                } catch (Exception $e) {
                }
            }
            
        }
        // 收拾资源
        $this->removeMsgQueue();
        return ;
    }
    
    /**
     * 反馈socket请求
     *
     * @param resource $sock socket连接句柄
     * @param string   $req  socket请求信息
     *
     * @return void
     */
    protected function responseSocket($sock, $req)
    {
        switch ($req) {
	        case 'help':
	            $helpStr = "";
	            $helpStr .= "帮助信息：\n";
	            $helpStr .= "  help   查看当前帮助信息\n";
	            $helpStr .= "  status 查看状态\n";
	            $helpStr .= "  quit   退出\n";
	            socket_write($sock, $helpStr);
	            break;
	        case 'status':
	            $statusStr = "";
	            $statusStr .= "  监控队列进程ＩＤ：{$this->queuePid}\n";
	            $statusStr .= "  监控队列启动时间：" . date("Y-m-d H:i:s", $this->starttime) . "\n";
	            $statusStr .= "\n";
	            $statusStr .= "  队列任务状态：\n";
	            foreach ($this->taskStatusList as $name => $status) {
	                $statusStr .= "  任务名称：{$name}\n";
	                $statusStr .= "    进程ＩＤ：{$status['pid']}\n";
	                $statusStr .= "    启动时间：" . date("Y-m-d H:i:s", $status['starttime']) . "\n";
	                $statusStr .= "    上次ping：" . date("Y-m-d H:i:s", $status['lastping']) . "\n";
	            }
	            socket_write($sock, $statusStr);
	            break;
	        case 'quit':
	            socket_shutdown($sock);
	            break;
	        default:
	            socket_write($sock, "无效的命令 {$req}，请输入\"help\"查看帮助命令。\n");
	    }
        return ;
    }
    
    
    /**
     * 子进程执行逻辑
     *
     * @param string   $name     任务名称
     * @param callback $callback 任务回调
     *
     * @return void
     */
    protected function executeTask($name, $callback, $interval)
    {	
        $childPid = posix_getpid();
        $this->showPromptMsg("Fork a child:{$name}[{$childPid}]");
        //for ($i=1; $i<=10; $i++) {
        while (true) {
            // 处理退出信号
   
            if (!empty(self::$signo) && in_array(self::$signo, self::$monitorSignoList)) {
     $this->showPromptMsg("抓到信号:{self::$signo} [{$name}] [{$childPid}]");    
            	$this->showPromptMsg("Signal {self::$signo} catched!");
                // 将心跳包时间更新为57秒钟之前，3秒后监控队列会重启此队列
                $this->ping($childPid, $name, self::$now - 57);
                exit;
            }

            // 更新时间
            self::$now = time();

            // 发送心跳包
            $this->ping($childPid, $name);

            // 主要业务逻辑
            call_user_func($callback);
            usleep($interval);
        }
        
        
        return ;
    }
    
   /**
     * 队列停止
     * 
     * @return void
     */
    public function stop()
    {
    	echo "dafds";
    	exit;
    
    }
    
   /**
     * 队列状态
     * 
     * @return void
     */
    public function status()
    {
    	echo "dafds";
    	exit;
    
    }
    
   /**
     * 队列重启
     * 
     * @return void
     */
    public function restart()
    {
    
    }
    
    /**
     * 析构函数，清理环境
     * 
     * @return void
     */
    public function __destruct()
    {
    }
    
   /**
     * 创建消息队列
     * 
     * @param int $msgQueueKey 消息队列id
     * 
     * @return resource
     */
    protected function createMsgQueue($msgQueueKey)
    {
    	return msg_get_queue($msgQueueKey, self::MESSAGE_QUEUE_PERMISSION);
    }
    
   /**
     * 销毁消息队列
     * 
     * @return void
     */
    public function removeMsgQueue()
    {
    	msg_remove_queue(self::$msgQueue);
    }
    
   /**
     * 向消息队列发送消息
     * 
     * @return void
     */
    public function sendMsg($messageType, $message)
    {
        @msg_send(self::$msgQueue, $messageType, $message, true, true, $msg_err);
        if ($msg_err) {
            print_r($msg_err);exit;
        }
    }
    
    /**
     * 从消息队列中接收消息
     * 
     * @return void
     */
    public function receiveMsg($messageType, $maxsize = 1024, $desiredmsgtype = 0)
    {
     	$message = null;
    	msg_receive(self::$msgQueue, 0, $messageType, 1024, $message, true, MSG_IPC_NOWAIT);
    	return $message;
    }
    
    /**
     * 创建sock
     * 
     * @return void
     */
    public function createSock()
    {
    	self::$msgQueue = msg_get_queue(ftok(__FILE__, 'a'), self::MESSAGE_QUEUE_PERMISSION);
    }
    
    
    // 创建主队列
    
    // 在主队列下创建子队列
    
    // 执行队列
    
    // 获取队列状态
    
    // 杀掉主队列
    
    // 杀掉子队列
    
    
}