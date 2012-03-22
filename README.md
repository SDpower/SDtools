SDtools
=========
This is some PHP library.


# LICENSE
            DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
                    Version 2, December 2004

    Copyright (C) 2012 Steve Luo <info@sd.idv.tw>

    Everyone is permitted to copy and distribute verbatim or modified
    copies of this license document, and changing it is allowed as long
    as the name is changed.

            DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
    TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION

    0. You just DO WHAT THE FUCK YOU WANT TO.

## Classloader
### SplClassLoader

    require_once 'SDtools/ClassLoader/SplClassLoader.php';//change this
    \SDtools\ClassLoader\SplClassLoader::addNamespace(array('Lib' => 'path/Lib'));
    $foo = new \Lib\Foo();
    
    \SDtools\ClassLoader\SplClassLoader::addPrefix(array('Lib' => 'path/Lib'));
    $apple = new \Lib_apple();

### CacheClassLoader
    require_once 'SDtools/ClassLoader/CacheClassLoader.php';//change this
    \SDtools\ClassLoader\CacheClassLoader::addNamespace(array('SDtools' => 'path/src'));
    
#### Use APC Cache
    $cache = "\\SDtools\\Cache\\ApcCache";
    \SDtools\ClassLoader\CacheClassLoader::SetCache($cache);
    \SDtools\ClassLoader\CacheClassLoader::addPrefix(array('Lib' => 'path/Lib'));
    $apple = new \Lib_apple();

#### Use Memcache
    $cache = "\\SDtools\\Cache\\MemcacheCache";
    $memcache = new \Memcache;
    $memcache->connect('localhost', 11211) or die ("Could not connect");
    $cache::setMemcache($memcache);
    \SDtools\ClassLoader\CacheClassLoader::SetCache($cache);
    \SDtools\ClassLoader\CacheClassLoader::addPrefix(array('Lib' => 'path/Lib'));
    $apple = new \Lib_apple();

#### Use RedisCache
    $cache = "\\SDtools\\Cache\\RedisCache";
    $redis = new \Redis();
    $redis->connect('127.0.0.1', 6379);
    $cache::setRedis($redis);
    $cache::SetPREFIX("CacheClassLoader");
    \SDtools\ClassLoader\CacheClassLoader::SetCache($cache);
    \SDtools\ClassLoader\CacheClassLoader::addPrefix(array('Lib' => 'path/Lib'));
    $apple = new \Lib_apple();