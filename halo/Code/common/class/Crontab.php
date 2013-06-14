<?php
declare(ticks=1);
namespace service;

/**
 * 计划任务
 *
 * 用php代替linux-crontab处理时间验证机制，可管理多条任务，
 * 需要为linux-crontab增加一条每分钟执行一次此功能的配置。
 *
 * 示例代码：
 * <code>
 * $cron = new Cron;
 * $cron->addTask("yourTaskName", "* * * * *", "callbackFunc");
 * $cron->execute();
 * </code
 *
 * @package
 * @author
 * @since   v1.0
 */
class Crontab
{
	/**
     * 当前时间戳
     *
     * @var int
     */
    protected $now;
    
    /**
     * 任务列表
     *
     * 格式：array("name" => array("* * * * *", "callbackFunc"))
     *
     * @var array
     */
    public static $tasks = array(
        //"moniterHour" => array("0 * * * *", "Monitor.perHour"),
        //"moniterDay"  => array("0 0 * * *", "Monitor.perDay"),
    );

    /**
     * 输出格式化后的提示信息
     *
     * @param string $msg 提示信息
     *
     * @return void
     */
    protected function showmsg($msg)
    {
        echo date("[Y-m-d H:i:s]", $this->now) . ' [PID:' . posix_getpid() . '] ' . $msg . PHP_EOL;
    }

    /**
     * 检查任务是否满足时间条件
     *
     * @param string $str 任务间隔（格式类似crontab）
     *
     * @return bool
     */
    protected function checkInterval($str)
    {
        list($i, $H, $d, $m, $w) = array_pad(explode(" ", $str), 5, "*");
        // 分钟 不符合
        if (!$this->checkOneInterval($i, "i")) return false;
        // 小时 不符合
        if (!$this->checkOneInterval($H, "H")) return false;
        // 日 不符合
        if (!$this->checkOneInterval($d, "d")) return false;
        // 月 不符合
        if (!$this->checkOneInterval($m, "m")) return false;
        // 星期 不符合
        if (!$this->checkOneInterval($w, "w")) return false;
        return true;
    }

    /**
     * 按类型检查单条间隔是否满足
     *
     * @param string $str  时间格式的字符串
     * @param string $type 格式类型（枚举i,H,d,m,w，默认i）
     *
     * @return bool
     */
    protected function checkOneInterval($str, $type)
    {
        // 星号，直接通过
        if ($str == "*") {
            return true;
        } else if (is_numeric($str)) {
            // 数字，判断是否相等
            return intval($str) == $this->getNowByType($type);
        } else if (($sp = explode(",", $str)) && count($sp) > 1) {
            // 由“,”分割为多个条件，拆开判断每个单独的条件，“或”的关系
            foreach ($sp as $s) {
                if (!$this->checkOneInterval($s, $type)) continue;
                return true;
            }
            return false;
        } else if (($sp = explode("/", $str)) && count($sp) == 2) {
            // 由“/”分割时间和间隔，先判断时间是否在范围内，再判断间隔是否能整除
            if (!$this->checkOneInterval($sp[0], $type))
                return false;
            return $this->getNowByType($type) % $sp[1] == 0;
        } else if (($sp = explode("-", $str)) && count($sp) == 2) {
            // 由“-”分割，判断时间是否在范围内
            $v = $this->getNowByType($type);
            if ($sp[0] > $sp[1])
                return $v >= $sp[0] || $v <= $sp[1];
            else // 开始大于结束的时候，“或”关系，否则“与”关系
                return $v >= $sp[0] && $v <= $sp[1];
        }
        // 默认不通过
        return false;
    }

    /**
     * 按格式类型获取当前时间
     *
     * @param string $type 格式类型（枚举i,H,d,m,w，默认i）
     *
     * @return int
     */
    protected function getNowByType($type = null)
    {
        if (!in_array($type, array("i","H","d","m","w")))
            $type = "i";
        return intval(date($type, $this->now));
    }

    /**
     * 构造方法，初始化逻辑需要的参数
     *
     */
    public function __construct()
    {
        $this->now = time();
    }

    /**
     * 新增任务
     *
     * @param string   $name     任务名称（唯一标识）
     * @param string   $interval 任务间隔（Linux-Crontab格式）
     * @param callback $callback 回调函数
     *
     * @return void
     */
    public function addTask($name, $interval, $callback)
    {
        self::$tasks[$name] = array($interval, $callback);
        return;
    }

    /**
     * 入口方法，请设置为每分钟执行一次
     *
     * @return void
     */
    public function execute()
    {
        $waitPids = array();
        foreach (self::$tasks as $name => $task) {
        	$interval = $task['0'];
            if (!$this->checkInterval($interval)) continue;
            $callback = $task[1];
            // fork出子进程执行任务，防止由于前边报错而卡住后边的任务
            $taskPid = pcntl_fork();
            if ($taskPid < 0) {
                $this->showmsg("任务“{$name}”的子进程fork失败!");
                continue;
            } else if ($taskPid == 0) {
            	call_user_func($callback);
                exit;
            } else {
                // 父进程记录子进程ID
                $waitPids[] = $taskPid;
            }
        }
        // 等待所有子进程退出后，主进程才退出
        foreach ($waitPids as $pid) {
            $status = null;
            pcntl_waitpid($pid, $status);
        }
        return ;
    }
}