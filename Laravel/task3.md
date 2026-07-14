# Why Bundle Front-end Assets
- In modern web development, there is a massive gap between the code that is good for developers to write and the code that is good for browsers to load. Bundling is the process that bridges this gap.

# Features:
* **Performance & Load Times**
               - Reducing HTTP Requests
               - Minification
               - Dead Code Elimination
* **Modern Language Support**
               - Sass/SCSS or PostCSS
               - JavaScript
               - TypeScript or framework-specific files like Vue (.vue) and React (.jsx)
* **Cache Busting**
               - Bundlers automatically append a unique hash to the file names in production
               - When you deploy an update, the hash changes, forcing the user's browser to instantly download the fresh code

# Vite in Laravel 
- Laravel integrates out of the box with Vite, a next-generation frontend build tool. Vite replaces older, slower bundlers (like Webpack/Laravel Mix) by using native browser ES modules to deliver incredibly fast Hot Module Replacement (`HMR`) during development

# Installtion
```bash
npm install
```

- Creates a node_modules/ folder containing Vite, the Laravel Vite plugin, and any styling utilities needed by the app

```bash
npm run dev
```
- You will see a message stating that Vite is running locally (usually at http://localhost:5173/)

```html
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
```
# Installing daisyUI
- Install the Package via NPM
```bash
npm i -D daisyui@latest
```
# Configure Your CSS Entry Point
```css
@import "tailwindcss";

/* 1. Tell Tailwind where your Blade files are */
@source "../**/*.blade.php";
@source "../**/*.js";

/* 2. Load daisyUI as a plugin */
@plugin "daisyui";
```
# Run Your Build Process
```bash
# In your frontend asset terminal:
npm run dev
```

# Where are JavaScript Files Built?
- Where We Write Code
```txt
your-project/
└── resources/
    └── js/
        ├── app.js      <-- Your main entry point
        └── bootstrap.js <-- Configures helper libraries 
```
# Where Compiled Files Go
- In Production `npm run build`
```txt
your-project/
└── public/
    └── build/
        ├── manifest.json   <-- Maps clean names to hashed names
        └── assets/
            └── app-Ch8_z9Lx.js  <-- Your compiled, minified production JS
```
- In Development `npm run dev`
- Instead, Vite serves your JavaScript directly from computer memory (RAM) via a local server (e.g., http://localhost:5173/resources/js/app.js).

# How Laravel Finds the Right Path
```html
@vite(['resources/js/app.js'])
```
- In Dev Mode: Generates <script type="module" src="http://localhost:5173/resources/js/app.js"></script>

- In Production Mode: Reads public/build/manifest.json and automatically generates <script type="module" src="/build/assets/app-Ch8_z9Lx.js"></script>
---
# Notifactions
- The primary objective of Laravel's notification engine is to separate the event that occurred from the way the user is alerted.
```txt
[ Incoming Request ] ---> [ Middleware Group ] ---> [ Route / Controller ]
|
(Fails check? Redirect/Abort)
```
```bash
php artisan tinker
> App\Models\User :: first();
```
# Make Notification
```bash
php artisan make:notifications-table

php artisan migrate
```
# Create Email Notification
```bash
php artisan make:notifications [Notifi_Name]
```
```php
public function via(object $notifiable):array
{
    return['mail'];
}
public function tomail(object $notifiable):MailMessage
{
    return (new MailMessage)
     ->greeting('hello')
     ->line('you published')
     ->action('Notifaction Action',url('/'))
     ->line('Thanks')
}
```

# Writing Notification in controller
```php
Auth::user()->notify(new Ideapublished($idea));
```
* **Update in Ideapublished.php**
```php
public function tomail(object $notifiable):MailMessage
{
    return (new MailMessage)
     ->greeting('hello')
     ->line('you published')
     ->line($this->idea->description)
     ->action('Notifaction Action',url('Read it','/'))
     ->line('Thanks')
}
```
---
# When to Queue it up 
* **why used Queued Jobs**

- In web development, a bad user experience is almost always tied to waiting.
- When a user interacts with your application, they expect an immediate response. 
- Queued jobs exist to make your application feel lightning-fast by separating immediate user feedback from heavy background processing.
- **Without Queues (Synchronous)** The user clicks "Submit". Your server saves them to the database, connects to an external email provider, sends a welcome email, contacts an analytics API, and finally loads the next page. The user is stuck watching a spinner for 3 to 5 seconds.

- **With Queues (Asynchronous)** The user clicks "Submit". Your server saves them to the database, throws the "Send Email" and "Log Analytics" tasks onto the queue database table (which takes less than 5 milliseconds), and immediately redirects the user to the dashboard. The user perceives the action as instant.

* **Queuing With ShouldQueue**

- What is ShouldQueue?
In PHP, an `interface` is a contract. When a class implements an interface, it promises to fulfill certain rules

- Without `ShouldQueue`: Laravel executes the class immediately on the current request thread (`synchronously`).

- With `ShouldQueue`: Laravel intercepts the execution, serializes the class data, pushes it to your database or Redis queue, and immediately returns control to your application (`asynchronously`).

```php
<?php

namespace App\Mail;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // 1. Import the contract
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobPosted extends Mailable implements ShouldQueue // 2. Apply the contract here
{
    use Queueable, SerializesModels; // These traits handle the queuing mechanics

    public function __construct(public Job $job) {}

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Job Posting is Live!')
                    ->view('emails.job-posted');
    }
}
```
```bash
// This code looks identical whether you queue it or send it live!
Mail::to($user)->send(new JobPosted($job));
```
**How ShouldQueue Works with Custom Jobs**
```bash
php artisan make:job ProcessImage
```
```php
<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue; // Imported automatically
use Illuminate\Foundation\Queue\Queueable;

class ProcessImage implements ShouldQueue // Implemented automatically
{
    use Queueable;

    public function __construct() {}

    public function handle(): void
    {
        //  code here
    }
}
```
**What is happening under the hood?**
```php
Mail::to($user)->later(now()->addMinutes(10), new JobPosted($job));
```
**Running Workers with `queue:work`**
```bash
php artisan queue:work
```
**Create and Dispatch Jobs**
```bash 
php artisan make:job
```
---
# Testing Code
* **Unit VS Feature Tests**
## Unit Tests (The Laser Pointer)
- **What they test:** A highly isolated "unit" of code (typically a single PHP method, a math helper, a text parser, or a custom class).

- **Isolation:** Ideally, unit tests do not boot the Laravel application kernel, query your database, make network requests, or read from cache.

- **Speed:** Because they are pure PHP execution with no framework baggage, they run in milliseconds. You can run hundreds of unit tests in less than a second.
## Create Unit Test
```bash
# Creates a test inside: tests/Unit/SalaryCalculatorTest.php
php artisan make:test SalaryCalculatorTest --unit
```
## Code Example
```php
// tests/Unit/TaxCalculatorTest.php

use App\Services\TaxCalculator;

test('it correctly calculates a 15 percent tax rate', function () {
    $calculator = new TaxCalculator();

    $result = $calculator->calculate(100, 15); // Amount: $100, Tax: 15%

    expect($result)->toBe(115.00);
});
```
## Feature Tests (The Safety Net)
- **What they test:** Larger end-to-end features or user actions (e.g., "A user can submit a job posting", or "An unauthenticated guest is redirected to /login").

- **Integration:** They boot the entire Laravel application container, resolve middlewares, hit database structures, dispatch events, and trigger notifications.

- **Speed:** Slightly slower than unit tests because they must bootstrap the framework, but they offer high confidence because they test your application exactly how a browser or client would.

## Create Feature Test
```bash
# Creates a test inside: tests/Feature/SubmitJobTest.php
php artisan make:test SubmitJobTest
```

## Code Example
```php
// tests/Feature/RegistrationTest.php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

// This trait cleans the database after every test run!
uses(RefreshDatabase::class);

test('new users can register an account', function () {
    // 1. Send a POST request to the application
    $response = $this->post('/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ]);

    // 2. Assert they are redirected to the homepage
    $response->assertRedirect('/');

    // 3. Assert the database contains the record
    $this->assertDatabaseHas('users', [
        'email' => 'john@example.com',
    ]);
});
```
## Run Both Test
```bash
# Run the entire test suite (Unit and Feature)
php artisan test
```

## Run Unit only
```bash
# Run only the Unit tests
php artisan test --group=unit
```
# Install Browser Test
```bash
composer require pestphp/pest-plugin-browser --dev
npm install playwright@latest --save-dev
npx playwright install
```
# Update your `.gitignore`
```txt
tests/Browser/Screenshots
```
# Create your First Pest Browser Test
```bash
php artisan make:test RegisterBrowserTest
```
# Fixing Browser Bootstrap
**In `pest.php`**
```php
pest()->extend(tests\testcase::class)->use(
Illuminate\foundation\Testing\Refreshdatabase::class)
    ->in('Browser')
```
# test it
**in Bash**
```bash
pest tests/Browser/Exampletest.php
```
- Failed
* **Edit in Web.php**
```php
Route::get('/',function(){
    return 'Welcome';
})
```
**in Bash**
```bash
pest tests/Browser/Exampletest.php
```
- Success

# How It Works
```txt
[Test Script] 
         |
         v
[Browser Driver]
         |
         v
[Real Browser (Chrome)] 
         |
         v
[Your Live Website]
         │
         v
[Assertion Passes/Fails] 
`````