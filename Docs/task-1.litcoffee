# PHP Core & Logic
- We write in terminal "php -h"
    We want "php -S"
            -S <addr>:<port> Run with built-in web server.
```php
 php -S localhost:8888
```
- Note : If You Change File Name AS Example From (index) to (index2) it would give us **ERROR 404 NOT FOUND**

Then we covert to PHP 

## PHP Tag 
- <?php starts the code with it .
- If a file is pure PHP   '?>' is the closing .
## PHP
```php
<?php 

?>
```
- **Inside these Tags We Write PHP Code not HTML**

## Echo
- It's the same as "Print" But we stick to "Echo"

## Concadination
In PHP We Use "." not "+"
```php
<?php 
   echo "Hello, "."World";
?>
```

## Variables
- Defined with a $.
- They are dynamically typed
- variable can hold a string and then be an int later.
## Single & Double Quotes
- Single -> Outputs The String we wrote itself
Example :
```php
<h1>
            <?php 
                $greeting = "Hello";
                 echo '$greeting world';
            ?>
        </h1>
```
### Output :
- $greeting world

- Double -> Outputs The String
Example :
```php
<h1>
  <?php 
 $greeting = "Hello";        echo "$greeting world";
 ?>
  </h1>
```
### Output :
- Hello world

## Conditionals and Booleans
- It's Html code To make its style we use CSS
- Uses if/else/else if .
## If Statment
```php
<h1>
  <?php 
      $name = "Dark Matter";
     $read = true;
 if("ask your question here")
 {
     // 
 }
         ?>
        </h1>
```
- Booleans are true or false.
- PHP considers empty strings, 0, and null as "false"

## Arrays
- array is indexed from 0
- Ordered lists using numeric indexes .

## Associative Arrays
- Key-value pairs: ['name' => 'John', 'age' => 30]. 
- Essential for representing database rows.
## Associative Arrays
- array is indexed from 0
```html
<!DOCTYPE html>
<html lang="en">
    <head>
  <meta charset="UTF-8">
 <title>Demo</title>
    </head>
 
    <body>
    <h1>Recommended Books
        </h1>
        <?php 
  $book =[  "Do Andriods Dream of electric sheep ",
  "The langoliars", 
  "Hail Msry"
      ]
        ?>
        <p>
            <?= $books[0]>
        </p>
        
    </body>
</html>

```
## Loop Nested Array
```php
        <ul>
<?php foreach($books as $book ):?>
 <Li>
 <a href="<?= $book ['purchaseURL]">  <?= $books ['name'] </a>
 ?> </Li>
<?php> endforeach </php> </ul>
```

## Functions and Filters 
- Functions encapsulate logic for reuse. 
- Filters (like filter_var) are specialized functions used to validate  input like emails or URLs.

## Lambda Functions (Anonymous Functions)
- They have no name and are often passed into other functions (like array_filter).


## Routing & Structure
- PHP Partials  HTML blocks ( head.php, nav.php,banner , footer) 
- To Prevent Repeating.

## Superglobals 
- Built-in variables accessible everywhere ($_GET for URL parameters, $_POST for form data, $_SERVER for headers).

## Make a PHP Router
-  A script that inspects $_SERVER ['REQUEST_URI'] and loads the corresponding controller, allowing for "pretty URLs" like /about instead of /about.php.

## Database & Security
Create a MySQL Database: Using SQL commands (or a GUI) to define tables, columns, and primary keys to store persistent data.

## PDO (PHP Data Objects)
- The standard, secure interface for connecting PHP to a database. It allows you to switch database types (MySQL to PostgreSQL) easily.

## Extract a PHP Database Class
- Moving PDO connection logic into a single class to manage the connection once and use it throughout the app.

## Environments & Configuration
- Storing sensitive data (DB passwords) in a separate config file or .env so it isn't hardcoded in the main logic.

## SQL Injection
- A vulnerability where user input is executed as SQL. 
- Prevention: Never use variables in strings; 
- always use Prepared Statements with placeholders (? or :id).

### In PHP Project
- I Divided Code files into :
 
 - Controllers 
                 
        include 1. about
                2. contact
                3. index
**example:**
```php
<?php
$heading = 'About Us';
require __DIR__ . '/views/about.view.php';
```
- Views
    - partial

          include 1.banner
                  2.footer
                  3.head
                  4.nav
- nav .php:
```php
<nav class="bg-gray-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="flex h-16 items-center justify-between">

            <div class="flex items-center">
                <div class="shrink-0">
                    <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" class="size-8" />
                </div>

                <div class="block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="<?= appUrl('/') ?>" class="<?= urlIs('/') ? 'bg-gray-900 text-white' : 'text-gray-300' ?> rounded-md hover:bg-gray-900 px-3 py-2 text-sm font-medium">
                            Home
                        </a>

                        <a href="<?= appUrl('/about') ?>" class="<?= urlIs('/about') ? 'bg-gray-900 text-white' : 'text-gray-300' ?> rounded-md px-3 py-2 text-sm font-medium hover:bg-white/5 hover:text-white">
                            About
                        </a>

                        <a href="<?= appUrl('/contact') ?>" class="<?= urlIs('/contact') ? 'bg-gray-900 text-white' : 'text-gray-300' ?> rounded-md px-3 py-2 text-sm font-medium hover:bg-white/5 hover:text-white">
                            Contact
                        </a>
                    </div>
                </div>
            </div>

            <div class="flex items-center">
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e" class="size-8 rounded-full" />
            </div>

        </div>

    </div>
</nav>

```
1. 404
2. about.view
3. contact.view
4. index.view
```php
<?php
require_once dirname(__DIR__) . '/functions.php';
$heading = isset($heading) ? $heading : 'Contact Us';
require __DIR__ . '/partial/head.php';
?>
<?php require __DIR__ . '/partial/nav.php'; ?>

<?php require __DIR__ . '/partial/banner.php'; ?>

    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <p>Contact Us!</p>
        </div>
    </main>
<?php require __DIR__ . '/partial/footer.php'; ?>

```
- Here I faced so many Errors so i used AI to solve these Problems :)

- There are external pages Like:
- config
- Database(to connect code to database)
- functions
- router

**flowchart:**
Browser → Router → Controller → View → Partials (header/nav/footer)