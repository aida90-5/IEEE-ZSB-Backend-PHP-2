<?php

namespace Http\forms;

use core\validationException;

class Loginform
{
    public $attributes;
    protected $errors = [];
    public function __construct(public array $attributes)
    {
        if (!validator::email($this->attributes['email']))
        {
            $this->errors['email']="Please provide a valid email address";
        }
        if (!validator::string($this->attributes['password'],7,255))
        {
            $this-> errors['password']="Please provide a password at least 7 characters long";
        }
    }
    public static function validate($attributes)
    {
       $instance = new static($attributes);
       return $instance->failed() ? $instance->throw() : $instance;

    }
    public function throw()
    {
        validationException::throw($this->errors(),$this->attributes);
    }
    public function errors()
    {
        return count($this->errors);
    }
    public function failed()
    {
        return count($this->errors);
    }
    public function error($field,$message)
    {
        $this->errors[$field]=$message;
        return $this;
    }
}
