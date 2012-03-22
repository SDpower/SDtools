<?php
namespace SDtools\Cache;
/*
 * This file is part of the SDtools package.
 * Copyright (C) 2012 Steve Luo <info@sd.idv.tw>
 * WTFPL (Do What The Fuck You Want To Public License)
 */

/**
 * Description of ApcCache
 *
 * @author Steve Luo <info@sd.idv.tw>
 */
class ApcCache extends AbstractCache
{
    static public function getIds()
    {
        $apc = apc_cache_info('user');
        $keys = array();

        foreach ($apc['cache_list'] as $entry) {
            $keys[] = $entry['info'];
        }

        return $keys;
    }

    static protected function _doFetch($id)
    {
        return apc_fetch($id);
    }

    static protected function _doContains($id)
    {
        $ret = false;
        apc_fetch($id, $ret);
        return $ret;
    }

    static protected function _doSave($id, $data, $lifeTime = 0)
    {
        return (bool) apc_store($id, $data, (int) $lifeTime);
    }

    static protected function _doDelete($id)
    {
        return apc_delete($id);
    }
}

?>
