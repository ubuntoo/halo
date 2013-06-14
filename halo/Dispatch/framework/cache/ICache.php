<?php
namespace framework\cache;

/**
 * 缓存接口 
 */
interface ICache
{
    public function __construct(array $args);
    public function get($key); // 读
    public function set($key, $value = false, $expire = false);  // 写
    public function flush();   // 刷新
    public function status();  // 缓存服务器的状态
}