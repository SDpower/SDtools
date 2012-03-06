<?php
namespace Cache;
/*
 * This file is part of the SDtools package.
 * Copyright (C) 2012 Steve Luo <info@sd.idv.tw>
 * WTFPL (Do What The Fuck You Want To Public License)
 */

/**
 * Description of AbstractCache
 *
 * @author Steve Luo <info@sd.idv.tw>
 */
abstract class AbstractCache
{

    /**
     *
     */
    static public function fetch($id)
    {
        return static::_doFetch($id);
    }

    /**
     * {@inheritdoc}
     */
    static public function contains($id)
    {
        return static::_doContains($id);
    }

    /**
     *
     */
    static public function save($id, $data, $lifeTime = 0)
    {
        return static::_doSave($id, $data, $lifeTime);
    }

    /**
     * {@inheritdoc}
     */
    static public function delete($id)
    {

        if (strpos($id, '*') !== false) {
            return static::deleteByRegex('/' . str_replace('*', '.*', $id) . '/');
        }

        return static::_doDelete($id);
    }

    /**
     * Delete all cache entries.
     *
     * @return array $deleted  Array of the deleted cache ids
     */
    static public function deleteAll()
    {
        $ids = static::getIds();

        foreach ($ids as $id) {
            static::delete($id);
        }

        return $ids;
    }

    /**
     * Delete cache use by PHP regular expressions
     *
     * @param string $regex
     * @return array $deleted  Array of the deleted cache ids
     */
    static public function deleteByRegex($regex)
    {
        $deleted = array();

        $ids = static::getIds();

        foreach ($ids as $id) {
            if (preg_match($regex, $id)) {
                static::delete($id);
                $deleted[] = $id;
            }
        }

        return $deleted;
    }

    /**
     * Delete cache where the id has the passed prefix
     *
     * @param string $prefix
     * @return array $deleted  Array of the deleted cache ids
     */
    static public function deleteByPrefix($prefix)
    {
        $deleted = array();

        $ids = static::getIds();

        foreach ($ids as $id) {
            if (strpos($id, $prefix) === 0) {
                static::delete($id);
                $deleted[] = $id;
            }
        }

        return $deleted;
    }

    /**
     * Delete cache where the id has the passed suffix
     *
     * @param string $suffix
     * @return array $deleted  Array of the deleted cache ids
     */
    static public function deleteBySuffix($suffix)
    {
        $deleted = array();

        $ids = static::getIds();

        foreach ($ids as $id) {
            if (substr($id, -1 * strlen($suffix)) === $suffix) {
                static::delete($id);
                $deleted[] = $id;
            }
        }

        return $deleted;
    }
    
    abstract static protected function _doFetch($id);

    abstract static protected function _doContains($id);

    abstract static protected function _doSave($id, $data, $lifeTime = false);

    abstract static protected function _doDelete($id);

    abstract static public function getIds();
}

?>
