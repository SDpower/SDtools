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

    require_once 'ClassLoader/SplClassLoader.php';//change this
    \ClassLoader\SplClassLoader::addNamespace(array('Lib' => 'path/Lib'));
    $foo = new \Lib\Foo();
    
    \ClassLoader\SplClassLoader::addPrefix(array('Lib' => 'path/Lib'));
    $apple = new \Lib_apple();