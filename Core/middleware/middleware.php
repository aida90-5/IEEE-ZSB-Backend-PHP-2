<?php

namespace core\middleware;

class middleware
{
   const Map=[
       'guest'=>guest::class,
       'auth'=>auth::class,
   ];
   public  static  function resolve($key)
   {
       $middleware = middleware::Map[$key];
       if(!$middleware) {
           throw new \Exception('No matching middleware found for Key' . {$key} . '.');
       }
       (new $middleware)->handle();
   }

}