<?php
namespace Cache;
/*
 * This file is part of the SDtools package.
 * Copyright (C) 2012 Steve Luo <info@sd.idv.tw>
 * WTFPL (Do What The Fuck You Want To Public License)
 */

/**
 * Description of RedisCache
 * This RedisCache is require PHP extension Redis(https://github.com/nicolasff/phpredis).
 * @author Steve Luo <info@sd.idv.tw>
 */
class RedisCache extends AbstractCache {
    static private $_prefix = "RedisCache";

    static private $_redis = NULL;
    
    /**
     * Sets the memcache instance to use.
     *
     * @param Redis $rediscache
     */
    static public function setRedis(\Redis $rediscache)
    {
        static::$_redis = $rediscache;
        static::SetPREFIX(static::$_prefix);
    }
    
    static public function SetPREFIX($param) {
        static::$_prefix = $param;
        static::$_redis->setOption(\Redis::OPT_PREFIX, $param.':');
    }


    static public function getRedis()
    {
        return static::$_redis;
    }
    
    static protected function _initCheck()
    {
        if (is_null(static::$_redis))
            throw new \BadMethodCallException("Use RedisCache you must be set Redis first,call RedisCache::setRedis .");
        return;
    }
    
    static public function getIds()
    {
        static::_initCheck();
        if (($all = static::$_redis->keys(static::$_prefix."*")) !== FALSE){
            foreach ($all as $key => $value) {
                $all[$key] = str_replace(static::$_prefix, "", $value);
            }
            return $all;
        }
        return FALSE;
    }

    static protected function _doFetch($id)
    {
        static::_initCheck();
        return static::$_redis->get($id);
    }

    static protected function _doContains($id)
    {
        static::_initCheck();
        return (bool) static::$_redis->exists($id);
    }

    static protected function _doSave($id, $data, $lifeTime = 0)
    {
        static::_initCheck();
        
        return static::$_redis->setex($id, (int) $lifeTime ,$data );
    }

    static protected function _doDelete($id)
    {
        static::_initCheck();
        return static::$_redis->delete($id);
    }
}

?>
