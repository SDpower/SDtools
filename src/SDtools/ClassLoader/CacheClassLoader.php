<?php

/*
 * This file is part of the SDtools package.
 * Copyright (C) 2012 Steve Luo <info@sd.idv.tw>
 * WTFPL (Do What The Fuck You Want To Public License)
 */
namespace SDtools\ClassLoader;
use \Exception;
require 'SplClassLoader.php';
/**
 * CacheClassLoader is autoloaderer suported namespace and prefix
 *
 * @author Steve Luo <info@sd.idv.tw>
 */
class CacheClassLoader extends SplClassLoader {
    static private $_CacheNamespace = 'ClassLoader';
    
    static private $_Cache = null;
    
    /**
     * Set Cacher 
     * @param \Cache\AbstractCache $cache 
     */
    static public function SetCache($cache)
    {
        static::$_Cache = $cache;
    }
    
    /**
     * Get Cacher 
     * @param \Cache\AbstractCache $cache 
     */
    static public function GetCache()
    {
        return static::$_Cache;
    }
    
    /**
     * Clear All of Cache datas.
     * @return type 
     */
    static public function ClearCache() {
        if (!is_null(static::$_Cache)){
            $cache = static::$_Cache;
            $cache::deleteAll();
        }
        return;
    }

        /**
     * Setting CacheNamespace string
     * @param string $ns 
     */
    static public function SetCacheNamespace($ns)
    {
        static::$_CacheNamespace = $ns;
    }
    
    
    /**
     * call by spl_autoload
     * @param string $class 
     */
    static public function AutoLoad($class)
    {
        if (!is_null(static::$_Cache)){
            $cache = static::$_Cache;
            if( ($file = $cache::fetch(static::$_CacheNamespace . '::' . $class) ) !== false ) {
                require $file;
                return true;
            }
            
            if (($file = static::findClass($class)) !== FALSE){
                $cache::save(static::$_CacheNamespace . '::' . $class, $file);
                require $file;
                return true;
            }            
        }else{
            if (($file = static::findClass($class)) !== FALSE){
                require $file;
                return true;
            }
        }
        return false;
    }

}

?>
