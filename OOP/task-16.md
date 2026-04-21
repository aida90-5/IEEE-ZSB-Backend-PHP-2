# Traits 
## How do "Traits" solve this problem, and when should you use them?

- In PHP, traits are to reuse code that allows you to share methods between multiple classes without using inheritance.


- **When to use**
1. share common functionality across unrelated classes.
2. PHP does not support multiple inheritance 
3. Don't Want To Duplicate code.

---
# NameSpaces
## What is a Namespace in PHP? Explain how itprevents "naming collisions" when you have two classes with the same name.

- **Namespaces** are used to group related code together under a name to avoid naming conflicts when your code grows, or when you use code from multiple sources.

**Why To Use?**
- To avoid name conflicts, especially in larger projects
- To organize code into logical groups
- To separate your code from code in libraries
- To allow the same name to be used for more than one class, without conflict

## How?:
- in PHP, we can't use 2 classes with same name, namespaces create Unique prefix, we can have the same classes' name but with different paths  

```php

<?php
namespace Project1;
class myclass{
//Project1/myclass
}
namespace Project2;
class myclass{
//Project2/myclass
}

?>
```

---
# Autoloading
## What is Autoloading, and how does it save time?
- It's When  automatically loads class files when they are needed, without requiring you to manually use include or require for each file.

## How does it save time?
- No More Include Bloat
- Performance Optimization
- Maintenance
- Scalability
---
# Magic Methods
## 1. What are __get() ,  __set()?
- Overloading methods, They allow you to handle data dynamically when you try to interact with properties that aren't "visible" or haven't been defined in a class.

1. __get():
- Triggered when You try to access a property that is private or protected or doesn't exist
2. __set():
- Triggered When You try to assign a value to a property that is private or protected or doesn't exist

---
# static Methods And Properties
## what does it mean?
### 1. What is static Method?
- functions that don't need to know anything about a specific object. They are often used for utility functions
### 2. What is static Properties?
- one copy of this variable. If you change a static property, it changes for everyone

## Do you need to create an object using the new keyword to access a static method?
- No
---