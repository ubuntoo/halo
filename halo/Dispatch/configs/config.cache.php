<?php
/**
 +-----------------
 * 缓存配置 
 +-----------------
 */
define('SWITCH_CACHE', true);      // 是否开启
define('CACHE_HOST', 'localhost'); // 缓存host
define('CACHE_PORT', 11211);       // 缓存端口  Memcached : 12321  redis : 6379
define('REDIS_OUTTIME', 0);        // 链接时长 (默认为 0 ，不限链接时间) (仅redis的配置)
define('REDIS_SERIALIZE', true);   // 是否序列化  (仅redis的配置)