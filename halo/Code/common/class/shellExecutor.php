<?php
namespace service;

/**
 * 远程执行shell脚本类
 * 
 * @author
 */
class shellExecutor {
	
    /**
     * 了解
     * 
     * @param $size         int         内存大小 kb
     * @return void
     */
    public function __construct($size = 1024, $mode = 'c', $permissions = 0755, $memoryId = null)
    {
        $this->memoryId = is_null($memoryId) ? mt_rand(1, 65535) : intval($memoryId);
        $this->permissions = $permissions;
        if ($this->exists($this->memoryId)) {
            $this->shmId = shmop_open($this->memoryId, $mode, $this->permissions, $size);
        }
        return;
    }
	

}