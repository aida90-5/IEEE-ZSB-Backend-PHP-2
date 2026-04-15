# The Core Concept
## Abstraction and Organization :
- The primary goal of this refactor is to abstract database queries into specific classes.

- This prevents raw SQL queries from being scattered throughout your application files (like home.php or posts.php), which reduces the risk of errors and makes the code uniform.

## Class Structure :

- **Database Class (Base Class):**
      - Contains the core logic for connecting to and interacting with the database.

- **Posts Class (Subclass):**
       - Extends the Database class to handle tasks specific to the posts table.

- **User Class (Subclass):** 
      - Would also extend Database to handle user-specific logic without repeating connection code.

# Key OOP Principles Used:
**extends keyword:**
- Used for inheritance. 
- By having class Posts extends Database, the Posts class gains all methods from Database (like connection and reading logic).

**$this keyword:**
- Used to refer to methods or properties within the same class or inherited from a parent class ($this->connect()).

# Access Modifiers:
**private:** 
- Used for functions like connect() so they can only be accessed internally by the class itself. 

**public:**
- Used for functions like db_read() or get_home_posts() so they can be called from outside the class.

# Implementing Specialized Methods
---
| Method |  Purpose | SQL Logic |
| --- | --- | --- |
| get_home_posts() | Fetches a limited number of posts for the landing page. |  `SELECT * FROM posts LIMIT 2` |
| get_all_posts() | Retrieves every post for a main feed. | `SELECT * FROM posts ORDER BY id DESC` |
| get_one_post($id) | Fetches a single specific post by its ID. | `SELECT * FROM posts WHERE id = $id LIMIT 1` |

# How to Use the Refactored Classes
- you must "instantiate" them first.

```php
// 1. Create an instance (object) of the class
$post_class = new Posts();

// 2. Call the specific method you need
$result = $post_class->get_home_posts();
```
---

# Security and Maintenance Benefits
- **Fragmentation:**
     - Because the code is broken into small, specific functions, it is easier to read and debug.

- **Uniformity:**
    - Since all queries for a table are in one place, you can ensure they are always formatted correctly.

- **Quick Fixes:**
    - If a security loophole is discovered in how you fetch posts, you only need to fix it in one method within the Posts class, and it will be updated everywhere on your site. 

# Minimizing Information Leakage
- **The Problem:** If an attacker knows the email is correct but the password is wrong, they have already bypassed 50% of the security and can focus solely on a brute-force attack on the password.

- **The Solution:** Always use a generic error message like: "Wrong email or password."

# Centralizing Session Management
- **Action:** Move session_start() to your main entry file .

- **Benefit:** This ensures sessions are available across all your includes and pages without having to call it repeatedly in every file.

# Refactoring Login to OOP
## The User Class Logic

- **Validation:**
    - Use filter_var($email,  ILTER_VALIDATE_EMAIL) to check if the email format is valid. 

- **Sanitization:** 
    - Use techniques like addslashes() or prepared statements (discussed in future videos) to prevent SQL injection.

- **Authentication:**
    - Query the database using the inherited db_read() method.

- **Error Handling:**
    - Return a string (the error message) or an empty string if successful.

## Refactoring Changes
---
| Feature | Procedural Approach (Old) |OOP Approach (New) |
|---|---|---|
| Error Messages | Specific ("Wrong Password") | Generic ("Wrong email or password") |
| Sessions | Called on every page | Centralized in `index.php` |
| Data Handling | Raw SQL in UI files | Methods inside a User class | 
| Global Variable | Using $_POST directly in logic |  Passing data as arguments to methods |


# The Principle of Least Privilege (PoLP)
- Giving users **only the minimum level** of access required to perform their job functions.

- **Avoid Over-Privileging:**
    - Do not give admin access to a content writer; they should be an "Editor."

- **Containment:**
    - If an account is compromised, the damage is limited to what that specific rank can access.

- **Accountability:**
    -  Restricting access makes it easier to track who performed specific actions on the system.

# Database Preparation
- **Action:** Add a rank (or level) column to your users table.

- **Example Ranks:** admin, editor, user.

# Testing & Troubleshooting
## The access() Function
- **How the Logic Works:**
- If the needed rank is admin: Only users with the rank admin are allowed.

- If the needed rank is editor: Both admin and editor are allowed (since an admin is higher than an editor).

- If the needed rank is user: admin, editor, and user are all allowed.

# Protecting UI Elements
- Once the function is defined, you can wrap sensitive parts of your code in an if statement.
```php
if (access('admin')) {
    // Show the posts and management tools
} else {
    echo "Sorry, you don't have access to this page.";
}
```
---
# Understanding the Attack
- **The Goal:** To bypass authentication or retrieve data without a valid password.

- **The Exploit:** Using characters like single quotes ('), dashes (--), or logic like OR 1=1.

# How it works:
**if your query looks like this:**

```SQL
SELECT * FROM users WHERE email = '$email' AND password = '$password'
```
- An attacker can input admin@test.com' OR 1=1 -- into the email field. The resulting query becomes:

```SQL
SELECT * FROM users WHERE email = 'admin@test.com' OR 1=1 --' AND password = '...'
```
-  The OR 1=1 is always true.

- **Result:** The attacker is logged in as the admin. 

# Multi-Layered Defense Strategy
- **Layer 1:** Server-Side Validation
- **Action:**
    - Use PHP's filter_var($email, FILTER_VALIDATE_EMAIL) on the server. If it's not a valid email, the script stops before it ever touches the database.
- **Layer 2:** Sanitization (Escaping)
- **Function:** addslashes($string)

- **How it works:** It adds a backslash (\) before quotes. A ' becomes \'.

- **Result:** The database treats the quote as a literal piece of text, not as the end of the SQL string


- **Layer 3:** Type Casting
If you expect a number (like a User ID), force the variable to be an integer.

- **Action:** $id = (int)$_POST['id'];

- **Benefit:** Even if an attacker enters 10 OR 1=1, the code will convert it to the number 10 (or 0 if it's purely text), neutralizing the attack. [12:43]

- **Layer 4:** Structural Protection
- Always wrap your variables in single quotes inside the SQL string.

- **Incorrect:**`WHERE id = $id` 
- (Easier to hack)

- **Correct:** `WHERE id = '$id'`
- (Forces the hacker to "break out" of the quotes first, which addslashes prevents)

| Function | Purpose |	Example |
| --- | --- | --- |
| filter_var()	| Validates data format |	`filter_var($email, FILTER_VALIDATE_EMAIL)` |
| addslashes() | Escapes special characters |	`addslashes($user_input)` |
| (int) |	Forces a variable to be an integer |	`$age = (int)$_POST['age']` |
| is_numeric() | Checks if data is a number |	`if (is_numeric($id)) `|

---
# Understanding the GET Attack Vector
- Vulnerable Query:

```SQL
SELECT * FROM posts WHERE id = $id LIMIT 1
```
- **The Exploit:**
     - An attacker changes the URL to post.php?id=4 ORDER BY 1. 
     - If the page still loads, the site is vulnerable.

# Advanced Hacking Techniques
- **Enumerating Columns:**
    - Using ORDER BY X to find the number of columns in a table. 
    - If ORDER BY 4 works but 5 fails, the table has 4 columns.

- **Union Select:**
    - Combining the original query with a second query to display sensitive data.

- **Example:**
    - `id=4 AND 0 UNION SELECT 1, user(), version(), 4`
    - This can display the database user and version on the page. 

- **Reading System Files:**
    - Using the `LOAD_FILE()` function to read server files like `php.ini` 

- **Saving Files:**
    - Potentially saving malicious scripts to the server using INTO OUTFILE

# Protection Strategies for GET Variables
- **Method 1:**
     -  Type Casting (Recommended for IDs)
     - Explicitly convert the incoming data into an integer.
     - This strips away any trailing SQL commands.

- **Code:** `$id = (int)$_GET['id'];`

- **Result:** `4 ORDER BY 1` becomes simply `4` 

- **Method 2:**
     - Numerical Validation
     - Ensure the data is a number before using it in a query.

- **Code:** `if (is_numeric($_GET['id'])) { ... }`

- **Method 3:**
     - Structural Protection (Quotes & Escaping)
     - Always wrap variables in single quotes and use addslashes() or prepared statements.

- **Code:** `"WHERE id = '" . addslashes($id) . "'"`

- **Result:**
    - Even if an attacker adds quotes to "break out" of the string, the backslashes added by addslashes() will neutralize them.

 | Defense Level | Method | Best For... |
 | --- | --- | --- |
 | Basic | Quotations around variables | All queries |
 | Medium | addslashes() |Text inputs |
 | High | Type Casting (int) | IDs and numeric lookups |
 | Pro |Prepared Statements | Industry standard for all data |

 ---

 # What are Prepared Statements?
- Prepared statements separate the SQL logic from the data. 
- Instead of building a query string with variables directly in it, you use placeholders.

- **The Problem:**
- Mixing data and code allows attackers to "inject" code into your variables.

- **The Solution:**
- The query is sent to the database first as a template. 
- The data is sent separately later. 
- The database treats the data strictly as a value, never as executable code.

# Setting Up PDO (PHP Data Objects)
- PDO is a database access layer that provides a uniform method of access to multiple databases. 
- It is preferred over mysqli for its flexibility and ease with prepared statements.

```php
class Database
{
    private function connect()
    {
        $string = "mysql:host=localhost;dbname=my_db";
        
        try {
            $con = new PDO($string, "root", "");
            return $con;
        } catch (PDOException $e) {
            // Error handling: Show details on localhost, hide on live server
            if ($_SERVER['HTTP_HOST'] == 'localhost') {
                die($e->getMessage());
            } else {
                die("Could not connect to database.");
            }
        }
    }

    public function read($query, $data = [])
    {
        $con = $this->connect();
        $stm = $con->prepare($query);

        // Execute returns true on success
        $check = $stm->execute($data);

        if ($check) {
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (is_array($result) && count($result) > 0) {
                return $result;
            }
        }
        return false;
    }

    public function write($query, $data = [])
    {
        $con = $this->connect();
        $stm = $con->prepare($query);

        $check = $stm->execute($data);

        if ($check) {
            return true;
        }
        return false;
    }
}

```

# Implementing the Database Class
- The beauty of a central database class is that you only have to fix the security logic in one place to protect the entire site.
```php
$db = new Database();
$id = (int)$_GET['id'];

// The placeholder is :id
$query = "SELECT * FROM posts WHERE id = :id LIMIT 1";

// The data is passed as an associative array
$data = ['id' => $id];

$result = $db->read($query, $data);
```
# User Login
- Multiple placeholders are handled by adding more keys to the array.
```php
$query = "SELECT * FROM users WHERE email = :email AND password = :password LIMIT 1";

$data = [
    'email'    => $_POST['email'],
    'password' => $_POST['password']
];

$result = $db->read($query, $data);
```
| Feature | Previous Method (Vulnerable) | New Method (Secure) |
| --- | --- | --- |
| Connection | `mysqli_connect()` | new PDO() |
| Variables | `'$email' (in string)` | :email (token) |
| Escaping | `addslashes()` |Automatic via execute($data) |
| Error Handling | `die(mysqli_error())` |try-catch blocks |

---
# Cross-Site Scripting (XSS) Protection

- XSS occurs when an attacker injects JavaScript (or occasionally PHP/HTML) into your website.
- When other users view that page, the script runs in their browser, allowing the hacker to steal session cookies, hijack accounts, or redirect users. 

1. **The Vulnerability**

- In the video, the instructor signs up with a password that is actually a script:
`<script>alert(document.cookie)</script>`

- Because the profile page echoes the password directly, the browser executes the script instead of showing the text.
- This exposes the PHP Session ID, which can lead to session hijacking. 

2. **The Solution: htmlspecialchars()**

- To fix this, you must "neutralize" the data before it is displayed.
- This function converts special characters into HTML entities , so the browser displays them as text instead of running them as code. 

3. **Recommended Security Implementation**

- The video suggests creating a central "clean" function so you don't have to write the long PHP function name everywhere.

4. **Why Use a Wrapper Function?**

- **Efficiency:**
- esc($data) is much faster to type than htmlspecialchars(...). 

- **Global Updates:**
- If you decide to change your sanitization method later, you only change the code inside the esc() function once, and the entire website is updated. 

# Key Takeaways

- **Never Trust User Data:**
- Even if data is already in your database, treat it as "dirty" when echoing it to a page. 

- **Prepared Statements are not enough:**
- Prepared statements protect your Database (SQL Injection), but htmlspecialchars protects your Users (XSS). 

- **Clean everything:**
- Every piece of data from the URL ($_GET), Forms ($_POST), or Database must be escaped before being displayed. 




