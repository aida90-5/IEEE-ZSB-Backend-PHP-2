# Laravel
## Create New Project
- first make sure your PHP version is 8.4 or higher.
- Laravel installer
```bash
//in Terminal 
laravel 
```
* **New Project**
```bash
laravel new <name of project>
```
## Viewing the Project
```bash
ls
cd <name of project>
```
---
## Routes
- How to read Routes?
```php
Route::get('/', function(){
    return view('welcome!');
    //Register a route that responds to a get request to home page('/')
});
```
## Create a Layout File
- Create a file at resources/views/components/layout.blade.php.
- Use the $slot variable to mark where the unique page content should go.

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My App</title>
</head>
<body>
    <nav>
        <a href="/">Home</a> | <a href="/contact">Contact</a>
    </nav>

    {{ $slot }} 
</body>
</html>
```
- To use this layout in your home page (welcome.blade.php):
```html 
<x-layout>
    <h1>Welcome to the Home Page!</h1>
</x-layout>
```
## Make a Layout Using TailwindCSS
- To instantly style your site without a long build setup
```html
<script src="https://cdn.tailwindcss.com"></script>
```
- Then use utility classes directly on your HTML tags

```html
<nav class="bg-gray-800 p-4 text-white flex gap-4">
    <a href="/" class="hover:underline">Home</a>
    <a href="/jobs" class="hover:underline">Jobs</a>
</nav>
<main class="max-w-6xl mx-auto mt-6 p-6">
    {{ $slot }}
</main>
```
- Using $slot to inject page-specific content.
## Attributes VS Props
- The @props directive (to separate logical PHP variables).
- The $attributes->merge() method (to gracefully combine default component Tailwind styling with any custom classes or HTML attributes you pass from the outside).

## Style the Currently Active Navigation Link
- To highlight whichever page the user is currently looking at
```html
// Check if the current request is the root path '/' 
<a href="/" class="{{ request()->is('/') ? 'bg-gray-900 text-white' : 'text-gray-300' }} px-3 py-2 rounded-md">Home</a>

//Check if the current request path starts with 'jobs' 
<a href="/jobs" class="{{ request()->is('jobs') ? 'bg-gray-900 text-white' : 'text-gray-300' }} px-3 py-2 rounded-md">Jobs</a>
```
## View Data and Route Wildcards
- You can pass data from your route definition directly into your Blade view using `view()`
```php
// routes/web.php
Route::get('/jobs', function () {
    return view('jobs', [
        'heading' => 'List of Available Jobs',
        'jobs' => [
            ['id' => 1, 'title' => 'Director', 'salary' => '$50,000'],
            ['id' => 2, 'title' => 'Programmer', 'salary' => '$90,000']
        ]
    ]);
});
```
## To display a single item dynamically, Use Route Wildcard ({id})
```php
Route::get('/jobs/{id}', function ($id) {
    return view('job', ['id' => $id]);
});
```
## Autoloading, Namespaces, and Models
- Laravel uses PSR-4 Autoloading, meaning classes are automatically loaded based on their folder structure and Namespace.

```php
// app/Models/Job.php
namespace App\Models;

class Job {
    public static function all(): array {
        return [
            ['id' => 1, 'title' => 'Director', 'salary' => '$50,000'],
            ['id' => 2, 'title' => 'Programmer', 'salary' => '$90,000']
        ];
    }
}
--------
//now it's clear
use App\Models\Job;

Route::get('/jobs', function () {
    return view('jobs', ['jobs' => Job::all()]);
});
```

## Migrations
- Instead of manually clicking around a database UI like phpMyAdmin to make tables, you write them in code. Migrations are version control for your database schema

```bash
php artisan make:migration create_job_listings_table
```

- Inside the file (database/migrations/...)
```php
public function up(): void
{
    Schema::create('job_listings', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('salary');
        $table->timestamps(); // Adds created_at and updated_at automatically
    });
}
```
- Run the migration to build your actual database tables
```bash
php artisan migrate
```
## Eloquent
- Eloquent is Laravel's Object-Relational Mapper (ORM). It maps a database table directly to a PHP class.

- Instead of writing custom arrays or manual SQL strings, you let your Model inherit from Laravel's base model class

```bash
php artisan make:model Job
```
```php
// app/Models/Job.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model {
    // By default, Eloquent expects a table named 'jobs'. 
    // If your table is named differently (like 'job_listings'), you define it:
    protected $table = 'job_listings';
}
```
Database interactions are fluent and incredibly simple
```php
// Fetch all rows from the database
Job::all();

// Find a specific record by primary key
Job::find(1);
```
## HTTP Requests and REST
- Building a complete CRUD structure (Create, Read, Update, Delete) for your resource assets.

-  Harnessing predictable web standards. 
- Learning to wire standard web verbs (GET, POST, PATCH, DELETE) to explicit web route patterns to correctly capture intent when managing items.

## Model Factories
- When developing or testing locally, you need fake data. Model Factories allow you to automatically generate dummy data using a package called Faker.
```bash
php artisan make:factory JobFactory
```
```php
// database/factories/JobFactory.php
public function definition(): array
{
    return [
        'title' => fake()->jobTitle(),
        'salary' => '$' . number_format(fake()->numberBetween(40000, 150000)),
    ];
}
```
## Eloquent Relationship Types
- Jobs belong to employers, and employers have many jobs. You represent this in Eloquent by defining relationship methods on your models

### One to Many (hasMany)
- An Employer can post many jobs
```php
// app/Models/Employer.php
public function jobs() {
    return $this->hasMany(Job::class);
}
```
### Inverse Relationship (belongsTo)
- Each individual Job belongs to a singular Employer
```php
// app/Models/Job.php
public function employer() {
    return $this->belongsTo(Employer::class);
}
```
## Pivot Tables and BelongsToMany Relationships
- When you have a relationship where multiple items connect to multiple other items you need a Many-to-Many relationship.

- This requires a middle table called a Pivot Table 

```php
// app/Models/Job.php
public function tags() {
    return $this->belongsToMany(Tag::class);
}
```
## Eager Loading and the N+1 Problem
-  If you try to loop through 20 jobs and print each job's employer like this
```php
foreach(Job::all() as $job) {
    echo $job->employer->name; 
}
```
- Laravel will run 1 query to get the jobs, and then 20 separate queries to get the employer for each individual line. This is the catastrophic N+1 query problem

- To fix this:
- Use Eager Loading by chaining the with() method to load your relationships upfront in a single, efficient database operation
```php
// Only runs 2 queries total, no matter how many jobs there are!
$jobs = Job::with('employer')->get();
```
## Controllers
- Extracting business logic from inline route closures into dedicated controller classes.
```bash
php artisan make:controller IdeaController
```
## Request Validation
- Stopping system execution early to preserve app integrity
- Catching empty or malicious user entries before they touch your application layer

```php 
$validated = $request->validate([
    'description' => ['required', 'min:5', 'max:255']
]);
```
## Form Request Classes
- Moving validation rules entirely out of the controller file to respect the Single Responsibility Principle
- Consolidating security configurations into specialized Request instances

```bash

php artisan make:request StoreIdeaRequest
```
- Utilizing the native `authorize()` logic block to handle firewall rules and the `rules()` block to isolate parsing criteria

## Brief DaisyUI Detour
- Polishing the look and feel of your app layouts rapidly
- Coupling foundational utility strings with component presets. Installing Tailwind and DaisyUI to construct elegant navigations, content boxes, error banners, and input modules.
- : Isolating navigational zones into stand-alone Blade slots to increase overall document scannability.