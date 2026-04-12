# Refactoring for Clarity
- Make Http Directory 
- Update Router 
```php
<?php

$router->get('/','index.php');
$router->get('/about','about.php');
$router->get('/contact','contact.php');

$router->get('/notes','notes/index.php')->only('auth');
$router->get('/note','notes/show.php');
$router->delete('/note','notes/destroy.php');

$router->get('/note/edit','notes/edit.php');
$router->patch('/note','notes/update.php');


$router->get('/notes/create','notes/create.php');
$router->post('/notes/create','notes/store.php');
$router->get('/register','registeration/create.php')->only('guest');
$router->post('/register','registeration/store.php');
$router->get('/login','sessions/create.php')->only('guest');
$router->post('/login','sessions/store.php')->only('guest');
$router->delete('/session','sessions/destroy.php')->only('guest');

```
- Make Loginform php file
```php
<?php

namespace Http\forms;

class Loginform
{
    protected $errors = [];
    public function validate($email,$password)
    {
        if (!validator::email($email))
        {
           $this->errors['email']="Please provide a valid email address";
        }
        if (!validator::string($password,7,255))
        {
          $this-> errors['password']="Please provide a password at least 7 characters long";
        }
       return empty($errors);
    }
    public function errors()
    {
        return $this->errors;
    }
}

```

# The Authenticator
```php
namespace Core;

class Authenticator
{
    public function attempt($email, $password)
    {
        // 1. Find the user
        $user = App::resolve(Database::class)->query('select * from users where email = :email', [
            'email' => $email
        ])->find();

        if ($user) {
            // 2. Verify the password
            if (password_verify($password, $user['password'])) {
                $this->login(['email' => $email]);

                return true;
            }
        }

        return false;
    }

    public function login($user)
    {
        $_SESSION['user'] = [
            'email' => $user['email']
        ];

        session_regenerate_id(true);
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();

        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
}
```
# Login Controller
- The controller is now much cleaner.
- It handles the form validation and uses the
**Authenticator** for the logic.
```php
$form = new LoginForm();

if ($form->validate($email, $password)) {
    if ((new Authenticator)->attempt($email, $password)) {
        redirect('/');
    }

    $form->error('email', 'No matching account found for that email address and password.');
}

return view('sessions/create.view.php', [
    'errors' => $form->errors()
]);
```
# Session Flashing
- Flash Data is data that:

1. Is stored in the session.
2. Is available for the very next request.
3. Is automatically deleted immediately after that.

```php
namespace Core;

class Session
{
    public static function put($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        return $_SESSION['_flash'][$key] ?? $_SESSION[$key] ?? $default;
    }

    // This puts data in a temporary "flash" bucket
    public static function flash($key, $value)
    {
        $_SESSION['_flash'][$key] = $value;
    }

    // This clears the flash bucket (called at the start of every request)
    public static function unflash()
    {
        unset($_SESSION['_flash']);
    }
}
```

# The destroy Method
- Instead of writing the cookie-clearing logic in the Authenticator, we moved it here to keep session-related code in one place.

```php
public static function destroy()
{
    $_SESSION = []; // Clear the array
    session_destroy(); // Destroy the session file on the server

    // Clear the session cookie from the browser
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
```
# The old() Helper
- This allows us to retrieve data that was flashed in the previous request

```php
function old($key, $default = '')
{
    return Core\Session::get('old')[$key] ?? $default;
}
```
# The Login View Implementation
- In sessions/create.view.php, we use the old() helper to ensure the user's email stays in the box even if they fail the login.

```php
<input 
    type="email" 
    name="email" 
    value="<?= old('email') ?>" 
    class="..."
>

<?php if (isset($errors['email'])) : ?>
    <p class="text-red-500 text-xs mt-2"><?= $errors['email'] ?></p>
<?php endif; ?>
```


```php
use Http\Forms\LoginForm;
use Core\Authenticator;

// 1. Validate the form (Throws ValidationException on failure)
$form = LoginForm::validate($attributes = [
    'email' => $_POST['email'],
    'password' => $_POST['password']
]);

// 2. Attempt to sign in
$signedIn = (new Authenticator)->attempt(
    $attributes['email'], 
    $attributes['password']
);

// 3. If credentials fail, manually add an error and throw
if (!$signedIn) {
    $form->error(
        'email', 'No matching Account for the email & password'
    )->throw();
}

// 4. Redirect on success
redirect('/');
```
# Composer Setup  
## 1. PHP Manual Installation
1. Download the PHP (Thread Safe) binary from the official website.
2. Extract the contents to `C:\php`.
3. Add `C:\php` to your Windows **System Environment Variables (Path)**.

### Configuring `php.ini`
1. Navigate to `C:\php`.
2. Copy `php.ini-development` and rename the copy to `php.ini`.
3. Open `php.ini` in a text editor and enable the following extensions by removing the leading semicolon (`;`):
   - `extension_dir = "ext"`
   - `extension=openssl`
   - `extension=mbstring`
   - `extension=curl`
4. Save and close the file

## 2. Installing Composer
Since we are using **Git Bash**, follow these steps:

1. Open your terminal and navigate to the PHP directory:
   ```bash
   cd /c/php
   ```
```bash
   php -r "copy('[https://getcomposer.org/installer](https://getcomposer.org/installer)', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```
# Global Access Configuration
```bash
#Create/Edit your bash profile:
nano ~/.bash_profile
# Add the following alias to the end of the file:
alias composer='php /c/php/composer.phar'
# Save and exit
source ~/.bash_profile
```
# Composer & Autoloading
```bash
"autoload": {
    "psr-4": {
        "Core\\": "Core/",
        "Http\\": "Http/"
    }
}
```
# Install Packages
```bash

composer require illuminate/collections
```

# PestPHP (Testing)
```bash

 composer require pestphp/pest --dev

 ```
 # Initialization & Testing Workflow
 ```bash
 ./vendor/bin/pest --init
 ```

 - Every time you change the autoload section in composer.json, run :
 ```bash
 composer dump-autoload
 ```
 - Run your tests
 ```bash
 ./vendor/bin/pest
 ```