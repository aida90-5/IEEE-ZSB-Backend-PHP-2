# VAR_DUMP
```php
<?php
var_dump('Hello There');
```
- If some one access this file from the browser :
       - "Hello There" will appear at the top of page as : **string(11) "Hello There"** in all pages not just Router(Page we wrote in)

# Name Spacing
## WHAT?
- A Way to organize code and avoid name conflicts by grouping related Files under a unique name.
---
## WHY?
- To Avoid Name Conflicts
- Better Code Organization
- Easier Collaboration(for Teamwork)
- Supports Autoloading

## HOW?
1- Define A Namespace
```php
<?php
namespace Core;
```
 2- to use It Without Path Like:
 ```php
 <?php
$db = new core\Database($config['database']);
 ```

 - we use 'use'
 ```php 
 <?php
use core\Database;
$db = new Database($config['database']);

 ```

 ```plaintext
core/
 └── Database.php

controllers/
 └── notes/
      └── show.php
```
# Multiple Request Methods
  - In web development, a **request method** defines the action the client wants the server to perform.

  **DELETE**
   - Removes a resource from the server.
   - Example: Deleting a post or a comment.
   ```php
   ;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $note = $db->query('SELECT * FROM notes WHERE id = id',[
        'id' => $_GET['id']
    ])->findorfail();
    authorize($note['user_id']== $currentUserId);
    $db->query('DELETE FROM notes WHERE id = id', [
        'id' => $_GET['id']
    ]);

    header('Location: /notes/');
    exit();
}else {
//}
```
# Build A Better Router

1. Define routes
```php 
   $router->get('/','controllers/index.php');
```
2. Capture the current request
```php
$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
```
3. Dispatch the request
```php
$router->route($uri, $method);
```

# One Request , One Controller

```plain text
controllers/
└── notes/
├── create.php
├── store.php
├── destroy.php
└── show.php
```
- Store (POST Request)
```php
<?php

use core\Validator;
use core\Database;

$config = require basePath('config.php');
$db = new Database($config['database']);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!Validator::string($_POST['body'], 1, 1000)) {
        $errors['body'] = 'A body of no more than 1000 characters is required.';
    }

    if (!empty($errors)) {
        return view("notes/create.view.php", [
            'heading' => 'Create Notes',
            'errors' => $errors
        ]);
    }

    $db->query(
        'INSERT INTO notes (body, user_id) VALUES (:body, :user_id)',
        [
            'body' => $_POST['body'],
            'user_id' => 1
        ]
    );

    header('Location: /notes');
    exit();
}
```
# Service Container
```plaintext
core/
 ├── Container.php
 ├── App.php
 └── Database.php

bootstrap.php
config.php
```
- Why Use a Container?
1. Cleaner, more maintainable code
2. Easy to swap implementations
3. Supports scalable architecture

**Example**
```php
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
```
# Updating Using PATCH
- HTML forms only support GET and POST,we simulate a PATCH request using a hidden input
```html
<input type="hidden" name="_method" value="PATCH">
```
## Update code
```php
<?php

use Core\Database;
use core\Validator;
use core\App;

$db = App::resolve(Database::class);

$currentUserId = 1;

// Fetch the note
$note = $db->query('SELECT * FROM notes WHERE id = :id', [
    'id' => $_POST['id']
])->findOrFail();

// Authorization check
authorize($note['user_id'] == $currentUserId);

$errors = [];

// Validation
if (!Validator::string($_POST['body'], 1, 1000)) {
    $errors['body'] = 'A body of no more than 1,000 characters is required.';
}

// Handle validation errors
if (count($errors)) {
    return view('notes/edit.view.php', [
        'heading' => 'Edit Note',
        'errors' => $errors,
        'note' => $note,
    ]);
}

// Update the note
$db->query('UPDATE notes SET body = :body WHERE id = :id', [
    'id' => $_POST['id'],
    'body' => $_POST['body']
]);

// Redirect after update
header('Location: /notes');
die();
```

# PHP Sessions 101
- Store and Display user data across different pages
1. **Start the session**
```php
<?php
session_start();
```
2. **Store the Data**
```php
<?php
$_SESSION['name'] = 'Aida';
```
3. **Display Data**
```php
<header class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">
            Hello, <?= $_SESSION['name'] ?? 'Guest' ?>
        </h1>
    </div>
</header>
```
# Register New User
- Registration Form
```html
<form action="/register" method="POST">
    <input type="email" name="email" required />
    <input type="password" name="password" required />

    <button type="submit">Register</button>
</form>
```
- Controller Logic
```php
<?php

use core\Validator;
use core\App;
use core\Database;

$email = $_POST['email'];
$password = $_POST['password'];

$errors = [];

//  Validation
if (!Validator::email($email)) {
    $errors['email'] = "Please provide a valid email address";
}

if (!Validator::string($password, 7, 255)) {
    $errors['password'] = "Please provide a password at least 7 characters long";
}

// If validation fails
if (!empty($errors)) {
    return view('registeration/create.view.php', [
        'errors' => $errors
    ]);
}

//  Database
$db = App::resolve(Database::class);

// Check if user exists
$user = $db->query(
    'SELECT * FROM users WHERE email = :email',
    ['email' => $email]
)->find();

//  If user already exists
if ($user) {
    $errors['email'] = "Email already exists";

    return view('registeration/create.view.php', [
        'errors' => $errors
    ]);
}

// Insert new user
$db->query(
    'INSERT INTO users (email, password) VALUES (:email, :password)',
    [
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT)
    ]
);

//  Create session
$_SESSION['user'] = [
    'email' => $email
];

//  Redirect
header('location: /');
exit();
```


# Introduction to Middleware
```plaintext
core/
 └── middleware/
      ├── middleware.php
      ├── auth.php
      └── guest.php
```
## Middleware Manager
```php
<?php

namespace core\middleware;

class middleware
{
   const Map = [
       'guest' => guest::class,
       'auth'  => auth::class,
   ];

   public static function resolve($key)
   {
       $middleware = middleware::Map[$key] ?? null;

       if (!$middleware) {
           throw new \Exception("No matching middleware found for key {$key}.");
       }

       (new $middleware)->handle();
   }
}
```
## Auth Middleware
```php
<?php

namespace core\middleware;

class auth
{
    public function handle()
    {
        if (!($_SESSION['user'] ?? false)) {
            header('location:/');
            exit();
        }
    }
}
```
## Guest Middleware
```php
<?php

namespace core\middleware;

class guest
{
    public function handle()
    {
        if ($_SESSION['user'] ?? false) {
            header('location:/');
            exit();
        }
    }
}
```
### Using Middleware in Routes
```php
$router->get('/notes', 'controllers/notes/index.php')->only('auth');

$router->get('/register', 'controllers/registeration/create.php')->only('guest');
```
# Manage Passwords
- Registration Logic
```php
<?php

use core\validator;
use core\App;
use core\Database;

$email = $_POST['email'];
$password = $_POST['password'];

$errors = [];

//  Validate Email
if (!validator::email($email)) {
    $errors['email'] = "Please provide a valid email address";
}

//  Validate Password
if (!validator::string($password, 7, 255)) {
    $errors['password'] = "Please provide a password at least 7 characters long";
}

//  If validation fails
if (!empty($errors)) {
    return view('registeration/create.view.php', [
        'errors' => $errors
    ]);
}

//  Resolve Database
$db = App::resolve(Database::class);

// Check if user exists
$user = $db->query(
    'SELECT * FROM users WHERE email = :email',
    ['email' => $email]
)->find();

//  If user already exists
if ($user) {
    header('location:/');
    exit();
}

//  Insert new user with hashed password
$db->query(
    'INSERT INTO users (email, password) VALUES (:email, :password)',
    [
        'email' => $email,
        'password' => password_hash($password, PASSWORD_BCRYPT)
    ]
);

//  Store user in session
$_SESSION['user'] = [
    'email' => $email
];

//  Redirect
header('location:/');
exit();
```
- Password Hashing
```php
password_hash($password, PASSWORD_BCRYPT);
```
# Login Form
```html
<main>
  <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
    <form method="POST" action="/login">
      <div>
        <label>Email</label>
        <input type="email" name="email" value="<?= $_POST['email'] ?? '' ?>">
        <p><?= $errors['email'] ?? '' ?></p>
      </div>
      <div>
        <label>Password</label>
        <input type="password" name="password">
        <p><?= $errors['password'] ?? '' ?></p>
      </div>
      <button type="submit">Login</button>
    </form>
  </div>
</main>
```
# Login Logic
```php
<?php

use core\App;
use core\Database;
use core\validator;

session_start();

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$errors = [];

// Validate input
if (!validator::email($email)) {
    $errors['email'] = "Please enter a valid email";
}
if (!validator::string($password, 7, 255)) {
    $errors['password'] = "Password must be at least 7 characters";
}
if (!empty($errors)) {
    return view('auth/login.view.php', ['errors' => $errors]);
}

// Fetch user
$db = App::resolve(Database::class);
$user = $db->query('SELECT * FROM users WHERE email = :email', ['email' => $email])->find();

if (!$user || !password_verify($password, $user['password'])) {
    $errors['login'] = "Invalid credentials";
    return view('auth/login.view.php', ['errors' => $errors]);
}

// Set session
$_SESSION['user'] = [
    'email' => $user['email']
];

// Redirect
header('location:/');
exit();
```
# Logout Logic
```php
<?php
session_start();
session_unset();
session_destroy();

// Redirect to home
header('location:/');
exit();
```
