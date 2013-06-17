<?php
namespace ctrl;

/**
 * 主页
 * 
 * @author
 */
use service\Crontab;

class Index extends CtrlBase
{
	/**
	 * 主函数
	 * 
	 * @return 
	 */
	public function main() {
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
		
	
		
		print_r("hello world");
	}
}