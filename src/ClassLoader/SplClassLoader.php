<?php

/*
 * This file is part of the SDtools package.
 * Copyright (C) 2012 Steve Luo <info@sd.idv.tw>
 * WTFPL (Do What The Fuck You Want To Public License)
 */

/**
 * SplClassLoader is autoloaderer suported namespace and prefix
 *
 * @author Steve Luo <info@sd.idv.tw>
 */
namespace ClassLoader;
use \Exception;
class SplClassLoader {
    
    /**
     * namespace mapping
     * @var array
     */
    static protected $_namespaces;
    
    /**
     * prefix mapping
     * @var array 
     */
    static protected $_prefix;
    
    /**
     * use IncludePath
     * @var boolen
     */
    static protected $_useIncludePath = FALSE;
    
    /**
     * fileExtension
     * @var string 
     */
    static protected $fileExtension = '.php';
    
    /**
     * initialized flag
     * @var boolen
     */
    static protected $_initialized = FALSE;
    
    /**
     * namespace 
     * @var array
     */
    static protected $_ns= array();
    
    /**
     * uniq path
     * @var array
     */
    static protected $_uniq=array();
    
    const namespaceSeparator = "\\";

    /**
     * register to spl_autoload_register
     * @return boolen
     */
    static protected function register()
    {
        return spl_autoload_register( __NAMESPACE__ .'\SplClassLoader::autoload');
    }
    
    /**
    * unregister the spl autoloader
    * @return boolen
    */
    static public function unregister()
    {
        return spl_autoload_unregister(__NAMESPACE__ .'\SplClassLoader::autoload');
    }
    
    /**
     * add namespace
     * @throws \BadMethodCallException When wrong params
     * @param array $ns
     */
    static public function addNamespace($ns = array())
    {
        if( is_array($ns) ) {
            foreach( $ns as $n => $dirs ){
                static::$_namespaces[ $n ] = $dirs;
                static::register_ns($n, $dirs);
            }
            return;
        } 
        else {
            $args = func_get_args();
            if( count( $args ) == 2 ) {
                static::$_namespaces[ $args[0] ] = $args[1];
                static::register_ns($args[0], $args[1]);
                return;
            }
        }
        throw new \BadMethodCallException("Use SplClassLoader::addNamespace you must be given an array param");
    }
    
    /**
     * add prefix
     *
     * @param array $ps
     */
    static public function addPrefix($pre = array())
    {
        foreach ($pre as $prefix => $dirs) {
            static::$_prefix[$prefix] = $dirs;
        }
        return;
    }
    
    /**
     * auto register
     * @return type 
     */
    static protected function init(){
        if (static::$_initialized) return;
        static::$_initialized=TRUE;
        static::register();
    }
    
    /**
     * call by spl_autoload
     * @param string $class 
     */
    static public function AutoLoad($class) {
        if (($file = static::findClass($class)) !== FALSE){
            //echo "File: $file.\n";
            require $file;
        }
    }
    
    /**
     * return find Class
     * @param string $class
     * @return string|boolean 
     */
    static protected function findClass($class){
        $fqcn=trim($class, "\\");
        $ns_arr=explode("\\", $fqcn);
        $file=str_replace("\\", DIRECTORY_SEPARATOR, $fqcn).static::$fileExtension;
        $class=array_pop($ns_arr);
        $ns=implode("\\", $ns_arr);

        foreach(static::$_uniq as $path){
            if(is_file($path.$file))
                return $path.$file;
        }

        foreach(static::$_ns as $n => $v){
            if(strlen($n)<=strlen($ns) and substr($ns, 0, strlen($n))==$n){
                foreach($v as $path){
                    if(is_file($path.$file))
                        return $path.$file;
                }
            }
        }
        
        if (static::$_useIncludePath && $filename = stream_resolve_include_path($file))
            return $filename;
        if (is_array(static::$_prefix)){
            $subpath = str_replace('_', DIRECTORY_SEPARATOR, $file);
            foreach (static::$_prefix as $p => $dir) {
                if (strpos($fqcn, $p) !== 0)
                    continue;
                $file = $dir.DIRECTORY_SEPARATOR.$subpath;
                if (file_exists($file))
                    return $file;
            }
        }
        if (static::$_useIncludePath && $filename = stream_resolve_include_path($file))
            return $filename;
        
        return FALSE;
    }
    
    /**
     * Do register namespace autoload
     * @param string $ns
     * @param string $path
     */
    static protected function register_ns($ns, $path){
        static::init();
        $path=static::convPath($path);
        $ns=trim($ns, static::namespaceSeparator);
        if($ns==''){
            if(!in_array($path, static::$_uniq))
                static::$_uniq[]=$path;
            return;
        }
        if(isset(static::$_ns[$ns]) or !in_array($path, static::$_ns))
            static::$_ns[$ns][]=$path;

    }
    
    /**
     * get path 
     * @param string $path
     * @return string 
     */
    static protected function convPath($path){
        $r_path=realpath($path);
        return rtrim($r_path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    }
}

?>
