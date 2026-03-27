# Backend Development Notes (Laracasts Style - Professional)

## 1. Database Tables and Indexes

* Databases store data in **tables** made of rows and columns.
* Each table represents an entity (`notes`, `users`).
* A **primary key** uniquely identifies each record.
* we connect 12 tables together using foreign key refer to the primary.
* **Indexes** improve query speed when searching/filtering data.
* Overusing indexes can slow down insert/update operations.

### PHP Example 

```php
public function __construct($config,$username='root',$password='aida'){
$dsn = 'mysql:'. http_build_query($config,'',';');
       $this ->connection = new PDO($dsn,'root','aida',[
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
        
    }
```

---

## 2. Rendering Notes and Note Pages

* Rendering means displaying data from the database in the UI.
* Data is passed from PHP to HTML views.
* Usually:

  * Notes page → list of notes
  * Note page → single note details

### PHP Example

```php
$note = $db->query('SELECT * FROM notes WHERE user_id = :user and id = :id',
    ['user' =>1,
        'id' => $_GET['id']
    ])->findorfail();
```

---

## 3. Introduction to Authorization

* Authorization controls what a user **can or cannot do**.
* It is different from authentication (login).
* Must always be handled on the **server side**.

### PHP Example

```php
class Responses{
    const NOT_Found =404;
    const Forbidden =403;
}
<?php
require_once dirname(__DIR__) . '/functions.php';
$heading = isset($heading) ? $heading : 'Contact Us';
require __DIR__ . '/partial/head.php';
?>
<?php require __DIR__ . '/partial/nav.php'; ?>


<main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1>You Are Not Authorized to view this page</h1>
        <p>
            <a href="/" class="text-blue underline">Go Back Home!</a>
        </p>
    </div>
</main>
<?php require __DIR__ . '/partial/footer.php'; ?>

```

---

## 4. Programming is Rewriting

* Code is not written once — it is improved continuously.
* Refactoring helps:

  * Simplify logic
  * Improve readability
  * Reduce duplication
* Clean code = fewer bugs.

---

## 5. Forms and Request Methods

* Forms collect user input and send it to the server.
* HTTP Methods:

  * **GET** → retrieve data (URL visible)
  * **POST** → send data (more secure)

### Example

```html
<form method="POST" action="/notes">
    <input type="text" name="title">
    <button type="submit">Add</button>
</form>
```

```php
$title = $_POST['title'];
```

---

## 6. Always Escape Untrusted Input

* Never trust user input.
* Prevent **XSS attacks** by escaping output.

### PHP Example

```php
 <p>
          <?= htmlspecialchars()$note['body'] ?>
 </p>

```

---

## 7. Form Validation

* Validation ensures correct and safe input.
* Must be done on the server side.

### PHP Example

```php
public static function string($value,$min=1,$max=INF)
    {
    $value =trim($value);
    return strlen($value)>=$min&&strlen($value)<=$max;
    }
    public static function email($value)
    {
        filter_var($value, FILTER_VALIDATE_EMAIL);
    }
```

---

## 8. Extracting a Validator Class

* Avoid repeating validation logic.
* Create a reusable **Validator class**.

### PHP Example

```php
class validator{
    public static function string($value,$min=1,$max=INF)
    {
    $value =trim($value);
    return strlen($value)>=$min&&strlen($value)<=$max;
    }
    public static function email($value)
    {
        filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
```

---


