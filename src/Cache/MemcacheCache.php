<?php
namespace Cache;
/*
 * This file is part of the SDtools package.
 * Copyright (C) 2012 Steve Luo <info@sd.idv.tw>
 * WTFPL (Do What The Fuck You Want To Public License)
 */

/**
 * Description of MemcacheCache
 *
 * @author Steve Luo <info@sd.idv.tw>
 */
class MemcacheCache extends AbstractCache {
    static private $_memcache = NULL;
    
    /**
     * Sets the memcache instance to use.
     *
     * @param Memcache $memcache
     */
    static public function setMemcache(Memcache $memcache)
    {
        static::$_memcache = $memcache;
    }
    
    static public function getMemcache()
    {
        return static::$_memcache;
    }
    
    static protected function _initCheck()
    {
        if (is_null(static::$_memcache))
            throw new \BadMethodCallException("Use MemcacheCache you must be set Memcache first,call MemcacheCache::setMemcache .");
        return;
    }
    
    public function getIds()
    {
        static::_initCheck();
        $keys = array();
        $all = static::$_memcache->getExtendedStats('slabs');

        foreach ($all as $server => $user) {
            if (is_array($slabs)) {
                foreach (array_keys($user) as $Id) {
                    $dump = $this->_memcache->getExtendedStats('cachedump', (int) $Id);

                    if ($dump) {
                        foreach ($dump as $data) {
                            if ($data) {
                                $keys = array_merge($keys, array_keys($data));
                            }
                        }
                    }
                }
            }
        }
        return $keys;
    }

    protected function _doFetch($id)
    {
        static::_initCheck();
        return static::$_memcache->get($id);
    }

    protected function _doContains($id)
    {
        static::_initCheck();
        return (bool) static::$_memcache->get($id);
    }

    protected function _doSave($id, $data, $lifeTime = 0)
    {
        static::_initCheck();
        return static::$_memcache->set($id, $data, 0, (int) $lifeTime);
    }

    protected function _doDelete($id)
    {
        static::_initCheck();
        return static::$_memcache->delete($id);
    }
}

?>
