<?php
namespace Cache;
/*
 * This file is part of the SDtools package.
 * Copyright (C) 2012 Steve Luo <info@sd.idv.tw>
 * WTFPL (Do What The Fuck You Want To Public License)
 */

/**
 * Description of XcacheCache
 *
 * @author Steve Luo <info@sd.idv.tw>
 */
class XcacheCache extends AbstractCache
{
    static public function getIds()
    {
        static::_checkAuth();
        $keys = array();

        for ($i = 0, $count = xcache_count(XC_TYPE_VAR); $i < $count; $i++) {
            $entries = xcache_list(XC_TYPE_VAR, $i);

            if (is_array($entries['cache_list'])) {
                foreach ($entries['cache_list'] as $entry) {
                    $keys[] = $entry['name'];
                }
            }
        }

        return $keys;
    }

    /**
     * 
     */
    static protected function _doFetch($id)
    {
        return $this->_doContains($id) ? unserialize(xcache_get($id)) : false;
    }

    /**
     * 
     */
    static protected function _doContains($id)
    {
        return xcache_isset($id);
    }

    /**
     * 
     */
    static protected function _doSave($id, $data, $lifeTime = 0)
    {
        return xcache_set($id, serialize($data), (int) $lifeTime);
    }

    /**
     * 
     */
    static protected function _doDelete($id)
    {
        return xcache_unset($id);
    }


    /**
     * Checks that xcache.admin.enable_auth is Off
     *
     * @throws \BadMethodCallException When xcache.admin.enable_auth is On
     * @return void
     */
    static protected function _checkAuth()
    {
        if (ini_get('xcache.admin.enable_auth')) {
            throw new \BadMethodCallException('To use all features of XcacheCache, you must set "xcache.admin.enable_auth" to "Off" in your php.ini.');
        }
    }
}

?>
