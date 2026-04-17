# 1.Documentation:
## What is OOP?
- OOP is (Object Oriented Programming).
- A Style of coding that Allows Programmers to Group similar Tasks into Classes

**Languages cannot be "object oriented". Only Code Can Be**
---
# Why Should I Use OO Paradigm?
- More Security
- More Dynamic
- More Clean [Developers Nightmare]
- Will Help in Other Languages
---
- **Class**: Blueprint that you can create object from

- **Object**: Member in the main Application

- **Example :**
     # Web Application Registeration:
     - **Class** : Code To Add new member
     - **Object** : The Members
     - **Application** : Web Application Registration
---
- Variable outside Class = Variable
- Variable inside Class = Property
```php
<? php
class AppleDevice{
    public &ram; //variable
}
$iphone6plus = new AppleDevice(); //Property
```
---
`[->]` = Object Operator
```php
<? php
class AppleDevice{
    public &ram; //variable
}
$iphone6plus = new AppleDevice(); //Property
$iphone6plus->ram ='2 GB';
```
# Methods 
- Methods -> functions in class
```php
<? php
class AppleDevice{
    public &ram; //variable
public function doubleHomePressed()
{
 echo 'You have pressed the Home Button ';  
}
}
$iphone6plus = new AppleDevice(); //Property
$iphone6plus->ram ='2 GB';
$iphone6plus -> doubleHomePressed();
```
---
$this -> pseudo Variable [Refer to Object Properties].

```php
<? php
class AppleDevice{
    public &ram; //variable
    public $ownerName;
public function getSpecification()
{
 echo 'This iphone Ram Is :' . $this->ram .'<br>';  
}
public function setOwnername()
{
    if(strlen($this->ownerName) <3)
    {
        echo 'Owner Name Cannot be less than 3';
    }else
    {
        echo 'Your Name Has Been Set';
    }
}
}
$iphone6plus = new AppleDevice(); //Property
$iphone6plus->ram ='2 GB';
$iphone6plus -> doubleHomePressed();
```
---
# constant
- Difference between **Variables Vs Constants**:
1. Variables: can be edited
2. Constants : cannot be changed 
---
Variable Vs Constant:
```php
class Name{
public $ram='1GB'; //variable
}
const CONSTANTNAME ='Value';
```
`::` Scope Resolution Operator to refer to constant variable `(->)`
`self` -> used in constant (like $this in variables).
`self::CONSTANTNAME`
- object can refer to constant :
`echo $iphone6plus::CONSTANTNAME;`
---
# Self VS $this
- **self  :**
- refer to Current Class
- Access Static Members
- Not use ($) bc it doesn't represent value but class construction
- **$this :**
- refer to current object
- Access Non static Members
- Use ($) bc it represents Variable
---
# 2.Research Questions:
## Class vs. Object

- **Class**: Blueprint that you can create object from

- **Object**: a real instance created from that class OR Member in the main Application
```php
<? php
//class is the BluePrint
class AppleDevice{
    public &ram; //variable
}
//Object is the instance/Member 
$iphone6plus = new AppleDevice(); //Property
```

---

# Self VS $this
- **self  :**
- refer to Current Class
- Access Static Members
- Not use ($) bc it doesn't represent value but class construction
- **$this :**
- refer to current object
- Access Non static Members
- Use ($) bc it represents Variable
## when to use one over the other?
1. use `$this` : Instance Property , Regular Method.
2. use `self` : Class Constant , Static Method.

---
# Access Modifiers (Encapsulation):
1. **Public:**
- Accessible from anywhere
- This is the default if no modifier is specified.
2. **Private:**
- restricts access to properties and methods to within the class itself. 
- They cannot be accessed from outside the class or by derived classes.
3. **protected :**
- access modifier allows properties and methods to be accessed within the class itself and by classes derived from that class.
- They cannot be accessed from outside the class.
---
# Typed Properties:
```php
class User {
   public int $age;
}
```
## How they Prevent Buges
1. Eliminating "Type Juggling" Errors
2. Catching Errors at the Source
3. Better Tooling and "Intellisense"

## Common Types:
- int 
- string
- bool
- array
- ?string
---
# Constructor Methods:
## what is construtor used for?
- Setting initial values for properties.
- Connecting to a database.
- Checking if required files or settings exist.

## Why is it useful to pass arguments into a constructor?when creating a new object?
1. Ensuring Mandatory Data
2. Customization