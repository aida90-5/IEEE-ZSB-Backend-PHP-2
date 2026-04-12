<?php

namespace core;

class container
{
    protected $bindings=[];
   public function bind($key ,$resolver)
   {
    $this->bindings[$key]=$resolver;
   }
   public function resolve($key)
   {
       if(! array_key_exists($key,$this->bindings))
       {
           throw new \Exception("No matching binding for key ".$key);
       }

      $resolver=$this->bindings[$key];
      return call_user_func($resolver);
   }

}