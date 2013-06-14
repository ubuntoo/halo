<?php
namespace framework\cache;
use \Memcache;

/**
 * Memcache缓存
 */
class MemcacheCache extends Memcache implements ICache
{
    private static $switch;  // 缓存是否已经开启
    
   /**
     * 构造方法   连接缓存数据库
     * 
     * @param array $args  连接参数
     * 
     * @return void
     */
    public function __construct(array $args)
    {
       // parent::__construct();
        $switch = $args['switch'];
        $host   = $args['host'];
        $port   = $args['port'];

        if ($switch) {
            $result = $this->addServer($host, $port);
            if (empty($result)) $switch = false;
        }
        self::$switch = $switch;
        return ;
    }
    
    /**
     * 获取缓存服务器的状态
     * 
     * @return boolen
     */
    public function status()
    {
        return self::$switch;
    }
    
   /**
     * 缓存刷新
     * 
     * @return boolen
     */
    public function flush()
    {
        if (!self::$switch) return false;
        return parent::flush();
    }
    
    /**
     * 读取缓存
     * 
     * @param $key  string    缓存key
     * 
     * @return mixed
     */
    public function get($key)
    {
        if (!self::$switch) return false;
        return parent::get($key);
    }
    
    /**
     * 写入缓存
     * 
     * @param $key        string    缓存key
     * @param $value      mixed     缓存值      
     * @param $expire     int       到期时间
     * 
     * @return boolen
     */
    public function set($key, $value = false, $expire = false)
    {
        if (!self::$switch) return false;    
        if(false === $value or is_null($value)) {
            return parent::delete($key);
        } 
        return parent::set($key, $value, $expire);
    }
    
    /**
     *  向一个新的key下面增加一个元素
     * 
     * @param $key        string    缓存key
     * @param $value      mixed     缓存值      
     * @param $expire     int       到期时间
     * 
     * @return boolen
     */
    public function add($key, $value=false, $expire=false)
    {
        if (!self::$switch) return false;
        return parent::add($key, $value, $expire);
    }
}
?>