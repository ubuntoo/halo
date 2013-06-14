<?php
namespace framework\cache;
use \Klext;
/**
 +----------------------------------------
 * Klext缓存
 +----------------------------------------
 * @package framework\cache\KlextCache
 +----------------------------------------
 */
class KlextCache extends Klext implements ICache
{
    protected $switch;
    private static $switch;  // 缓存是否已经开启

    public function get($key)
    {
        if (!self::$switch) return false;
        return parent::get($key);
    }

    public function set($key, $value=false, $expire=false)
    {
        if (!self::$switch) return false;
        return parent::set($key, $value, $compress, $expire);
    }

    public function add($key, $value=false, $expire=false)
    {
        if (!self::$switch) return false;
        return parent::add($key, $value, $compress, $expire);
    }

    public function flush()
    {
        if (!self::$switch) return false;
        return parent::flush();
    }

    public function __construct(array $args)
    {
        $switch = $args['switch'];
        self::$switch = $switch;
    }
}