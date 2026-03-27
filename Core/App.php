<?php

namespace core;
class App{
    protected static $container;
 public static function setContainer($container)
{
   static::$container = $container;
}
public static function getContainer()
{
    return static::$container;
}
public static function bind($key)
{
        static::container() ->bind($key) ;
}
public static function resolve($key)
{
   return static::container()->resolve($key);
}
 }
