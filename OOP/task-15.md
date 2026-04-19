# Methods Training
```php
<? php
class AppleDevice{
    public &ram; //variable
    //methods
    public function changeSpec($r){
    $this->ram =$r;
    }
}
$iphone6plus = new AppleDevice(); //Property
//$iphone6plus->ram ='2 GB';
$iphone6plus->changeSpec("2GB");

```
---
# Encapsulation
- Hides the internal state of an object and only allows controlled access through public methods (getters and setters)

```php
<? php
class AppleDevice{
    public $ram; //variable
    private $lock;
    //methods
    public function changeSpec($r){
    $this->ram =$r;
    }
    public function changeLock($lo)
    {
      $this->lock= sha1($lo);

    }
}
$iphone6plus = new AppleDevice(); //Property
//$iphone6plus->ram ='2 GB';
$iphone6plus->changeSpec("2GB");
//$iphone6plus->lock='1234';
$iphone6plus->changeLock('12345');


```

---
# Inheritance
- **Inheritance** is a fundamental concept in OOP that allows a class (child class) to inherit properties and methods from another class (parent class).
- This promotes code **reusability** and establishes **hierarchical relationships** between classes.

```php
<? php
class AppleDevice{
    public $ram; //variable
    private $lock;
    //methods
    public function changeSpec($r){
    $this->ram =$r;
    }
    public function changeLock($lo)
    {
      $this->lock= sha1($lo);

    }
}
class Sony extends AppleDevice{
    //properties
    public $camera = '25GB';
    
   

}

$iphone6plus = new AppleDevice(); //Property
//$iphone6plus->ram ='2 GB';
$iphone6plus->changeSpec("2GB");
//$iphone6plus->lock='1234';
$iphone6plus->changeLock('12345');
$sony= new Sony();
$sony->changeSpec("2GB");


```
---
# Inheritance Override

- To override a method in PHP, a child class must redefine the method in the parent class with the same name, parameters, and return type.
- This allows the child class to provide a specific implementation of the method, replacing the code in the parent class.
```php
<? php
class AppleDevice{
    public $ram; //variable
    private $lock;
    //methods
    public function changeSpec($r){
    $this->ram =$r;
    }
    public function changeLock($lo)
    {
      $this->lock= sha1($lo);
    }
    public function SayHello($n)
    {
        $this->name=$n;
        echo 'Welcome To ' . $n;
    } 
}
class Sony extends AppleDevice{
    //properties
    public $camera = '25GB';
    
   public function SayHello($n)
    {
        $this->name=$n;
        echo 'Welcome To ' . $n .'This Phone Has' .$this->ram . 'RAM';
    } 

}

$iphone6plus = new AppleDevice(); //Property
//$iphone6plus->ram ='2 GB';
$iphone6plus->changeSpec("2GB");
//$iphone6plus->lock='1234';
$iphone6plus->changeLock('12345');
$iphone6plus->SayHello('iphone');
$sony= new Sony();
$sony->changeSpec("2GB");
$sony -> SayHello('Sony');


```
---
# Inheritance Final Keyboard
- In PHP, the final keyword is used in inheritance to prevent a class from being extended or a method from being overridden.

- If you made a class 'final class ---' you can't make a child class inherites from it.

```php
<? php
class AppleDevice{
    public $ram; //variable
    private $lock;
    //methods
    public function changeSpec($r){
    $this->ram =$r;
    }
    public function changeLock($lo)
    {
      $this->lock= sha1($lo);
    }
    final public function SayHello($n)
    {
        $this->name=$n;
        echo 'Welcome To ' . $n;
    } 
}
class Sony extends AppleDevice{
    //properties
    public $camera = '25GB';  
  }

$iphone6plus = new AppleDevice(); //Property
//$iphone6plus->ram ='2 GB';
$iphone6plus->changeSpec("2GB");
//$iphone6plus->lock='1234';
$iphone6plus->changeLock('12345');
$iphone6plus->SayHello('iphone');
$sony= new Sony();
$sony->changeSpec("2GB");
$sony -> SayHello('Sony');


```
---
# Class Abstraction
-  class abstraction is a way to define a blueprint for other classes without allowing direct instantiation of that class.
- It’s done using the abstract keyword and is useful for enforcing a contract for child classes while still allowing shared code.

- can't be instantiated
- Made for others to inherit prep & methods from
- can have methods & properties 
- can have abstracted method and Non abstracted methods
- abstract methods contain no body code

## Example
```php
abstract class MakeDevice{
    public $ram; //variable
    public function SayHello($n)
    {
        $this->name=$n;
        echo 'Welcome To ' . $n;
    } 
}
class AppleDevice extends MakeDevice{
   
}
$iphone6plus = new AppleDevice();
$iphoneplus -> sayHello('iphone');
```
---
# Polymorphism

## What is it?
- Describes a pattern in OOP in which classes have different functionlly while sharing common interface.

- Working with different classes doesn't need to know which class it is using since they're aal used the same way.

- Have Methods Without body code.
---
# Visibilty Markers

## Public
- Can be called in any scope.
- Default when visibilty isn't declared.

## Protected
- can be accessed from inside class
- Any class that extends them.
- can't be acceseed from outside the class or subclass.

## Private
- Can't be accessed by a subclass of the class 
- You can access protected not private.

# What is the main benefit of Inheritance in OOP? 
- Code Reusability
- Extensibility
- Organization


```php
<? php
class AppleDevice{
    public $ram; //variable
    private $lock;
    //methods
    public function changeSpec($r){
    $this->ram =$r;
    }
    public function changeLock($lo)
    {
      $this->lock= sha1($lo);

    }
}
//inheritance
class Sony extends AppleDevice{
    //properties
    public $camera = '25GB';
    
   

}



```
---
# What happens if you put the final keyword before a class or a method? 

- it cannot be inherited

# Why would a developer want to use this?
- More Security
---

# What does it mean to "override" a method in a child class?
- a Child class provides its own specific implementation for a method that is already defined in its Parent class.


# How can you call the original parent method from inside the child's overridden method?
```php
<? php
class AppleDevice{
    public $ram; //variable
    private $lock;
    //methods
    public function changeSpec($r){
    $this->ram =$r;
    }
    public function changeLock($lo)
    {
      $this->lock= sha1($lo);
    }
    public function SayHello($n)
    {
        $this->name=$n;
        echo 'Welcome To ' . $n;
    } 
}
class Sony extends AppleDevice{
    //properties
    public $camera = '25GB';
    
   public function SayHello($n)
    {
        //override
        $this->name=$n;
        echo 'Welcome To ' . $n .'This Phone Has' .$this->ram . 'RAM';
    } 

}


```
---
# Abstract Class vs. Interface:
- **Abstract**:
- Used when classes share a common identity and actual code logic.
- **Interface**:
- Used to define a capability or behavior regardless of what the object actually is.
---
# Polymorphism:
- a pattern in OOP in which classes have different functionlly while sharing common interface.

```php
class AppleDevice {
    public $ram;
    public $name;

    public function changeSpec($r) {
        $this->ram = $r;
    }

    public function SayHello($n) {
        $this->name = $n;
        echo "Welcome to " . $this->name . "<br>";
    } 
}

class Sony extends AppleDevice {
    public function SayHello($n) {
        $this->name = $n;
        echo "Welcome to " . $this->name . " (Xperia Series). RAM: " . $this->ram . "<br>";
    } 
}

class Samsung extends AppleDevice {
    public function SayHello($n) {
        $this->name = $n;
        echo "Welcome to " . $this->name . " (Galaxy Series). RAM: " . $this->ram . "<br>";
    } 
}
```
---