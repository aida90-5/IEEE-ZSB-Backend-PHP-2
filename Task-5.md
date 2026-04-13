# What Is Security?
- State of Being **Protected Or Safe**.
---
# Web Security
- A specific branch of security focused exclusively on protecting websites and online services
---
# Why web Security?
### Public Nature of Websites:
     - Unlike a private local file, Website are public
     - any one can access to Website without monitoring
### Protection of Personal Data :
     - Protect User & Personal Information
### The "Same Password" Risk:
     - Many users reuse credentials across multiple sites.
     - their accounts on other platforms are also at risk
---
# Nature of Security Work:
### Knowledge + Action
- Security requires both understanding 
- Knowledge without implementation is ineffective
### Importance of Updates
- Always use the latest stable version of software/PHP
- older versions are eaasier to be attacked.
# No absolute Security 
- The aim is not perfection, but to make it as difficult as possible for unauthorized users to gain access
# Avoid Obstruction
- it's so important to strike a balance between security & obstructing Real Users.
---
# What is a Hacker?
- Someone who uses a system or tool in a way that it was **not intended** to be used.

**Web context:**
- Breaking into computer systems or websites to gain unauthorized access or manipulate data .

---
# White Hat VS Black Hat Hackers
| :--- | :--- | :--- |
| **Primary Goal** | To improve security by finding flaws before they are exploited . | To exploit systems for personal gain, profit, or harm . |
| **Permission** | Works with the owner’s consent (Authorized) . | Operates without permission (Unauthorized/Illegal). |
| **Methods** | Uses **Penetration Testing** to test defenses . | Uses malware, **botnets**, and spam . |
| **Outcome** | Reports findings to owners so the system can be patched . | Keeps vulnerabilities secret for future use . |
---
# Types Of Black Hat Attackers

| Category | Behavior / Motivation | Risk Level |
| :--- | :--- | :--- |
| **Curious Users** | Manipulate URLs (e.g., `id=20` → `201`) to find hidden data . | **High** |
| **Script Kiddies** | Use pre-made scripts without understanding the underlying code . | **Moderate** |
| **Professionals** | Skilled hackers aiming for profit or creating **backdoors**. | **Very High** |
| **Specialized Groups** | **Thrill Seekers/Activists:** Target "Big Fish" for prestige or politics . | **Low** (for small sites) |
---


# What is Social Engineering?
Act of persuading or tricking someone into voluntarily giving up confidential information (like passwords or admin access).
---
# Why It's Effective?
- It is often much easier to trick a person than to use technical "brute force" to crack a computer system .

---
# Common Social Engineering Tactics
| Method | Description | Prevention |
| :--- | :--- | :--- |
| **Physical Security** | Hackers look for password "sticky notes" on monitors or desks. | Never write passwords down; use a password manager. |
| **Dumpster Diving** | Searching through office trash for phone bills, purchase lists, or discarded notes with emails/passwords. | Shred all documents containing personal or corporate information. |
| **Keyloggers** | Software (sometimes sent via email) that invisibly records every keystroke you type . | Be cautious of email attachments and restrict physical access to your hardware . |
| **Social Media Mining** | Using public info (dog's name, hometown, etc.) to answer security questions for password resets . | Use **two-step verification (2FA)** and avoid using real personal info for security questions . |

---
# Phishing
Act of creating a fake version of a  website to steal login information.
---
# Mirror Website 
- Attackers use tools (like "Website Copier") to download the CSS, images, and HTML of a real site to make their fake version look identical .
---
# The Redirect 
- When you enter your data into the fake form, the script emails your details to the hacker and redirects you back to the real website. You may think the page just "glitched" and log in again, unaware your data was stolen .
---
# Spot Phising Attempt
- Suspicious Sender Address
- Look-alike URLs
- Too Good to Be True
---

## Functional Code Seperation 
**Never put your sensitive logic (functions and classes) inside your main index page**

- Best Choice:

     - Keep logic in separate files and store them in a dedicated folder.

- OR The Include Method:
     - Use PHP's `include` or `require` to call these functions in your index page only when needed .
---

## Private OR Public
| Folder | Purpose | Contents |
| :--- | :--- | :--- |
| **Public** | The "Landing Page" for the server  | `index.php`, CSS, JS, and Images. |
| **Private** | Files the user should never see directly  | `functions.php`, database configs, and classes. |
---
# Avoiding Directory Listings
If a folder doesn't have an index file, many servers will automatically list every file in that directory, exposing your assets to anyone.

#### Method A: The Empty Index File 
Add an empty `index.php` or `index.html` file to **every** folder in system. This forces the server to run the blank file instead of showing the directory list.

#### Method B: .htaccess Restriction 
Create a `.htaccess` file in your root or sensitive directories and add the following line:
----
# Use the **.php** Extension
- Risk :
     - If someone knows the path to config.json or passwords.txt, the browser will display the contents as plain text
- Advantage :
     - If you store that same data inside a .php file ,The server will attempt to execute it.

# Secure File Includes
- The Vulnerability **File Inclusion**
     - When you use a `$_GET` variable directly in an `include()` statement, you allow users to control which file the server executes.

**The Dangerous Pattern:**
```php
$page = isset($_GET['page']) ? $_GET['page'] : "home.php";
include($page); // Dangerous!
```

- The Risk:
     -  A hacker could change the URL to `?page=../../../../etc/passwd` to read sensitive system files or `?page=../../apache/logs/error. log` to find system vulnerabilities
---
# Fake Image Attack :
- Hackers can hide PHP code inside a valid image file

1. **Preparation** A hacker appends PHP code to the end of a valid `.jpg` file using a text editor

2. **Upload**  The hacker uploads this "image" as a profile picture.

3. **Execution** PHP will ignore the image data but execute the hidden PHP tags at the end
---

| Strategy | Strategy | Benefit |
| :--- | :--- | :--- |
| **Enforced Extension** | `include($_GET['page'] . '.php');` | Prevents the server from executing non-PHP |
| **Image Processing** | Use GD Library to crop or resize images. | Re-encoding the image data strips out any hidden PHP tags |
| **Whitelisting** | Create an array of approved filenames and only include matches. | The most secure method. It blocks any file or path that you have not explicitly defined in your code. |
---

- Safe Include Method
```php 
<?php
$page = isset($_GET['page']) ? $_GET['page'] : "home";
$folder = ""; // Current folder
$files = glob($folder . "*.php");


$requested_file = $page . ".php";

if (in_array($page . ".php", $files)) {
    include ($page . ".php");
} else {
    echo "could not find file";
}

?>
```

---
# Single Entry point
- In a traditional site, users access files directly
- In a **Single Page Loading** system, every request goes to `index.php`

- **Goal:** 
      -  To ensure that no script runs without first passing through your global security checks.
- **The Benefit:**
     -  You only have to write your "Gatekeeper" logic  in **one place** .

---
# Prevent Direct File Access:
- Internal files like database configurations or private functions should never be executed on their own.

---
# Constant Guard Method:
- To ensure a file only runs when included by your main `index.php`, you can use a defined constant:
```php
<?php
define('SECURELOAD', true);
include "includes/db.php";
?>
```

```php 
<?php
// Check if the constant exists
if(!defined('SECURELOAD')) {
    die("Direct access is forbidden!");
}

// Database credentials follow...
$db_pass = "123456";
?>
```
---
---
| Folder | Access Level | Purpose |
| :--- | :--- | :--- |
| **public_html/** | Public | Contains `index.php`, .`htaccess`, and `UI assets` |
| **private/pages/** | Hidden | Contains the actual content files  |
| **private/includes/** | Hidden | Contains sensitive logic  |
---
- Implementation:
```php 
<?php

define('SECURELOAD', true);

$folder = "pages/";
$files = glob($folder . "*.php");

$file = isset($_GET['url']) ? $_GET['url'] : "home";

if (in_array($file . ".php", $files)) {
    include $folder . $file . ".php";
} else {
    include $folder . "404.php";
}

?>
```
# What are Clean URLs?
- **Messy :**
`example.com/index.php?url=profile&id=2`
- **Clean :**
`example.com/profile/2`

---
# The .htaccess File
- To achieve this on an Apache server, you must create a file named `.htaccess` in your root directory. This file tells the server how to handle incoming requests before they reach your PHP code.

```PHP
RewriteEngine On

# Stop people from viewing the actual folder structure
Options -Indexes

# If the requested filename is not a real directory
RewriteCond %{REQUEST_FILENAME} !-d
# And if the requested filename is not a real file
RewriteCond %{REQUEST_FILENAME} !-f

# Send everything to index.php and pass the path as a 'url' variable
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```
---
# Modify `index.php`
- Once the `.htaccess` file is working, the "clean" part of the URL is sent to PHP as a variable.
- If a user types `example.com/about`, the server silently converts it to `index.php?url=about`.
---
# Handling Assets
- When using clean URLs, the browser might get confused about where your CSS and Images are located.

- **Problem**
       -  If you are at `/profile/edit`, the browser looks for `style.css` inside a fake 
       `/profile/` folder.
- **Fix**
- - Use Absolute Paths (starting with a /) for your links
```HTML
<link rel="stylesheet" href="/css/style.css">

<link rel="stylesheet" href="css/style.css">
```
---
# Why Refactor?
- Seperation Of Concerns.
- Reusability.

# The New File Structure 
- `index.php` -> Entry Point
- `functions.php` -> Where Logic Lives
- `Pages/` -> Where Content Lives

## functions.php :
```php
// connect to database
function connect()
{
    if(!$con = mysqli_connect("localhost","root","","security_db"))
    {
        die("could not connect to the database");
    }

    return $con;
}

function db_read($query)
{
    $con = connect();
    $result = mysqli_query($con,$query);

    if($result && mysqli_num_rows($result) > 0)
    {
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            
            $data[] = $row;
        }

        return $data;
    }

    return false;
}
```
---
# Benefits of this Refactor :
- Readability
- Security
- Scalability
