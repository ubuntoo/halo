<?php
namespace framework\cache;
use \Redis;

/**
 +----------------------------------------
 * Redis缓存
 +----------------------------------------
 * @package framework\cache\RedisCache
 +----------------------------------------
 */
class RedisCache extends Redis /*implements ICache*/
{
    /**
     * 连接实例
     *
     * @var \Redis
     */
    private $client;
    
    private static $switch;  // 缓存是否已经开启

    /**
     * 构造函数 连接
     *
     * @param int $args  连接参数
     * 
     * @return void
     */
    public function __construct(array $args)
    {
    	$switch  = $args['switch'];
        if ($switch) {
            try {
            	$this->client = @$this->connect($args['host'], $args['port'], $args['outTime']); 
            	if ($args['serialize']) {
        			$this->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
        		}
    			if ($args['database']) {
        			$this->select($args['database']);
        		}
            } catch (\RedisException $exception) {}       
            if (empty($this->client)) $switch = false;
        }   
        self::$switch = $switch;
        return ;
    }

    /**
     * 设置指定键名的数据
     *
     * @param string    $key          键
     * @param mixed     $value        值
     * @param int       $expiration   生存时间
     * 
     * @return bool
     */
    public function set($key, $value, $expiration = 0)
    {
        return $expiration > 0 ? $this->setex($key, $expiration, $value) : parent::set($key, $value);
    }
    
   /**
     * 判断是否重复的，写入值
     *
     * @param string    $key          键
     * @param mixed     $value        值
     * 
     * @return bool
     */
    public function setUnique($key, $value)
    {
        return $this->setnx($key, $value);
    }

    /**
     * 设置多个键名的数据
     *
     * @param  array  $items <key => value>  键值列表
     * 
     * @return bool
     */
    public function setList($items)
    {
        return $this->mset($items);
    }

    /**
     * 获取指定键名的数据
     *
     * @param string    $key  键
     * 
     * @return mixed
     */
    public function get($key)
    {
        return parent::get($key);
    }

    /**
     * 获取指定键名序列的数据
     *
     * @param array     $keys  键列表  
     * 
     * @return array   <key => value, key => value> 
     */
    public function getList($keys)
    {
        $values = $this->getMultiple($keys);
        return array_combine($keys, $values);
    }

    /**
     * 增加指定键名的值并返回结果
     *
     * @param   string    $key     键
     * @param   int       $step    步长
     * 
     * @return  int       值
     */
    public function addValue($key, $step = null)  // 未调通
    {
        return is_null($step) ? $this->incr($key) : $this->incrBy($key, $step);
    }
    
    /**
     * 减少指定键名的值并返回结果
     *
     * @param   string    $key     键
     * @param   int       $step    步长
     * 
     * @return  int       值
     */
    public function reduceValue($key, $step = null)  // 未调通
    {
        return is_null($step) ? $this->decr($key) : $this->decrBy($key, $step);
    }
    

    /**
     * 设置指定键名的数据并返回原数据
     *
     * @param string    $key
     * @param mixed     $value
     * @return int
     */
    public function getSet($key, $value)
    {
        return parent::getSet($key, $value);
    }

    /**
     * 删除指定键名的数据
     *
     * @param string    $key    键
     * 
     * @return bool
     */
    public function delete($key)
    {
        return parent::delete($key);
    }

    /**
     * 判断指定键名是否存在
     *
     * @param string    $key  键
     * 
     * @return bool
     */
    public function exists($key)
    {
        return parent::exists($key);
    }

    /**
     * 得到一个key的生存时间
     *
     * @param string    $key  键
     * 
     * @return bool
     */
    public function expiration($key)
    {
        return parent::ttl($key);
    }
    
    /**
     * 设置指定哈希指定属性的数据
     *
     * @param string    $key
     * @param string    $prop
     * @param mixed     $value
     * @return bool
     */
    public function hashSet($key, $prop, $value)
    {
        return $this->hSet($key, $prop, $value);
    }

    /**
     * 设置指定哈希多个属性的数据
     *
     * @param string    $key
     * @param array     $props <$prop => $value>
     * 
     * @return bool
     */
    public function hashSetList($key, $props)
    {
        return $this->hMset($key, $props);
    }

    /**
     * 获取指定哈希指定属性的数据
     *
     * @param string    $key
     * @param string    $prop
     * @return mixed
     */
    public function hashGet($key, $prop)
    {
        return $this->hGet($key, $prop);
    }

    /**
     * 获取指定哈希多个属性的数据
     *
     * @param string    $key
     * @param array     $props
     * @return array    <$prop => $value>
     */
    public function hashGetList($key, $props)
    {
        return $this->hMGet($key, $props);
    }

    /**
     * 删除指定哈希指定属性的数据
     *
     * @param string    $key
     * @param string    $prop
     * @return bool
     */
    public function hashDel($key, $prop)
    {
        return $this->hDel($key, $prop);
    }

    /**
     * 获取指定哈希的长度
     *
     * @param string    $key
     * @return int
     */
    public function hashLength($key)
    {
        return $this->hLen($key);
    }

    /**
     * 获取指定哈希的所有属性
     *
     * @param string    $key
     * @return array
     */
    public function hashProps($key)
    {
        return $this->hKeys($key);
    }

    /**
     * 获取指定哈希的所有属性的值
     *
     * @param string    $key
     * @return array
     */
    public function hashValues($key)
    {
        return $this->hVals($key);
    }

    /**
     * 获取指定哈希的所有属性和值
     *
     * @param string    $key
     * @return array
     */
    public function hashGetAll($key)
    {
        return $this->hGetAll($key);
    }

    /**
     * 清空当前数据库
     *
     * @return bool
     */
    public function flush()
    {
        return $this->flushDB();
    }

    /**
     * 获取服务器统计信息
     *
     * @return array
     */
    public function info()
    {
        return parent::info();
    }

    /**
     * 设置过期时间（TTL）
     * @param string    $key
     * @param int       $seconds
     * @return bool
     */
    public function expire($key, $seconds)
    {
        return parent::expire($key, $seconds);
    }

    /**
     * 设置过期时间（TIMESTAMP）
     * @param string $key
     * @param int $ts
     * @return bool
     */
    public function expireAt($key, $ts)
    {
        return parent::expireAt($key, $ts);
    }

    /**
     * 对象设置：hash
     * @param string $key
     * @param Object $obj
     * @param int $seconds
     * @return bool
     */
    public function objSet($key, $obj, $seconds)
    {
        if(DEBUG_MODE and !is_object($obj)) {
            throw new NewException(NewException::CODE_ERROR_BUG, 'cacheNeedObject');
        }

        unset($obj->_fromCache);
        $ok = $this->hMset($key, (array)$obj);
        if($ok) {
            $obj->_fromCache = 840123;
            if($seconds) {
                $ok = $this->expire($key, $seconds);
            }
        }
        return $ok;
    }

    /**
     * 对象获取：hash
     * @param string $key
     * @param string $class
     * @return Object
     */
    public function objGet($key, $class)
    {
        $obj = null;
        $arr = $this->hGetAll($key);
        if($arr) {
            $obj = new $class;
            foreach($arr as $k => $v) { // TODO hsq 优化？
                $obj->$k = $v;
            }
            $obj->_fromCache = 840123;
        }
        return $obj;
    }

    /**
     * 对象属性更新，可能全新set：hash
     * @param string $key
     * @param Object $obj
     * @param array(string) $props
     * @param int $seconds
     * @return bool
     */
    public function objUpdate($key, $obj, $props, $seconds)
    {
        if(DEBUG_MODE and !is_object($obj)) {
            throw new NewException(NewException::CODE_ERROR_BUG, 'cacheNeedObject');
        }

        if(isset($obj->_fromCache) and $obj->_fromCache == 840123) {
            // NOTE 如果更新单个属性，可通过其命令(hSet)返回值判别
            $tmp = array();
            foreach($props as $prop) {
                $tmp[$prop] = $obj->$prop;
            }
            $props = $tmp;

            return parent::hMset($key, $props);
        } else {
            return $this->objSet($key, $obj, $seconds);
        }
    }

    /**
     * 取排序集合某范围数据
     * @param string $key
     * @param int $start
     * @param int $end
     * @param bool $withScores
     * @param bool $desc
     * @return array(id)|array(id => score)
     */
    public function sortRange($key, $start, $end, $withScores = false, $desc = true)
    {
        return ($desc
            ? parent::zRevRange($key, $start, $end, $withScores)
            : parent::zRange($key, $start, $end, $withScores));
    }

    /**
     * 取排序集合指定成员的分数
     *
     * @param string $key
     * @param string $member
     * 
     * @return int
     */
    public function zScore($key, $member)
    {
        return parent::zScore($key, $member);
    }

    /**
     * 取排序集合指定成员的排名
     *
     * @param string $key
     * @param string $member
     * @return int
     */
    public function zRevRank($key, $member)
    {
        return parent::zRevRank($key, $member);
    }

    /**
     * 集合：add
     * 
     * @param  string    $key
     * @param  mixed     $member
     * 
     * @return bool     是否新成员
     */
    public function setAdd($key, $member)
    {
        return $this->sAdd($key, $member);
    }

    /**
     * 集合：remove
     * 
     * @param string    $key
     * @param mixed     $member
     * 
     * @return bool     是否存在
     */
    public function setRem($key, $member)
    {
        return $this->sRem($key, $member);
    }

    /**
     * 集合：random member
     * @param string    $key
     * 
     * @return string
     */
    public function setRandMember($key)
    {
        $member = $this->sRandMember($key);
        return ($member ? $member : '');
    }

    /**
     * 集合：members
     * 
     * @param string    $key
     * 
     * @return array(string)
     */
    public function setMembers($key)
    {
        return $this->sMembers($key);
    }

    /**
     * 集合：copy
     * 
     * @param string    $keyDst
     * @param string    $keySrc
     * 
     * @return int  成员数
     */
    public function setCopy($keyDst, $keySrc)
    {
        return (int)$this->sUnionStore($keyDst, $keySrc);
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
}