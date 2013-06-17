<?php
namespace ctrl;

/**
 * shell主调度
 * 
 * @author
 */
class Shell extends CtrlBase
{
	/**
	 * 主函数
	 * 
	 * @return 
	 */
	public function main () {
		self::help();	
	}
	
    /**
	 * 帮助信息
	 * 
	 * @var array
	 */
    private static $HELP_INFO = <<<EOT
Shell.help             			帮助信息
Shell.flushCache       			清空缓存
Shell.flushFileCache   			清空文件缓存
Shell.flushConfig      			刷新配置
Shell.flushOnline      			统计在线人数
Shell.kickUser  	  [NAME]	踢用户下线
EOT;
    
    /**
     * 帮助信息
     * 
     * @return void
     */
    private static function help()
    {
        echo str_repeat("-", 70)."\n";
        echo "Usage: php " . ROOT_PATH . "Shell/main.php [cmd] [args]\n\n";
        echo "[cmd] options:\n\n";
        echo self::$HELP_INFO . "\n";
        echo "\nExample: php Shell/main.php Shell.flushCache\n";
        echo str_repeat("-", 70)."\n";
    }
}