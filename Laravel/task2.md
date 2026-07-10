* **The Core Architecture of Laravel Auth**
* **The Session:** HTTP is stateless. Laravel uses an encrypted cookie session payload to remember who a user is across multiple requests.

* **The Guard:** The mechanism that determines how users are authenticated for each request (the web guard uses sessions; an api guard might use tokens).

* **The Provider:** The database bridge that instructs Laravel where to fetch the user record (typically via the Eloquent User model matching the users database table).

**Registering a User**
Registration is the gateway. When a visitor fills out your sign-up form, the backend executes four sequential phases:
```txt
Validate Inputs
       |
       v
Create User in DB
       |
       v
Log User In
       |
       v
Redirect to Dashboard
```
* **Form Validation**
Never trust user inputs.
Laravel's `request()->validate()` method ensures that data matches strict criteria.
For passwords, Laravel utilizes a fluent Password rules helper class.

```PHP
$attributes = request()->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->letters()->numbers()],
]);
```
* Adding the `confirmed` validation rule means Laravel automatically seeks a matching frontend input named exactly `password_confirmation`

* **Database Insertion and Password Hashing**
```PHP
$user = User::create($attributes);
```
* **Logging the User In**
With the record created, we pass the user instance directly to Laravel's authentication manager to establish the encrypted session.

```PHP
Auth::login($user);
return redirect('/dashboard');
```

* **The Custom Login Flow**
When an existing user attempts to log back into the system, the architecture switches from creation to validation using `Auth::attempt()`

* **The Controller Implementation**
```PHP
public function store()
{
    // 1. Validate form fields
    $attributes = request()->validate([
        'email' => ['required', 'email'],
        'password' => ['required']
    ]);

    // 2. Attempt to match credentials against the DB and sign in
    if (! Auth::attempt($attributes)) {
        throw ValidationException::withMessages([
            'email' => 'Sorry, those credentials do not match our records.'
        ]);
    }

    // 3. Prevent Session Fixation Attacks
    request()->session()->regenerate();

    // 4. Redirect to intended destination
    return redirect()->intended('/dashboard');
}
```
* **Logging Out (Destroying the Session)**
Logging out requires cleaner handling than simply wiping data. 
It must invalidate the session entirely and regenerate the token identifier.

```PHP
public function destroy()
{
    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
}
```
* **Protecting Routes and Views**
```PHP
use App\Http\Controllers\DashboardController;

// Only logged-in users can see this
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');

// Only unauthenticated guests can see this (e.g., login/register forms)
Route::get('/login', [SessionController::class, 'create'])->middleware('guest');
```


```html
@auth
    <p>Welcome back, {{ auth()->user()->name }}!</p>
    <form action="/logout" method="POST">
        @csrf
        <button type="submit">Log Out</button>
    </form>
@endauth

@guest
    <a href="/login">Log In</a>
    <a href="/register">Register</a>
@endguest
```
---
# Authentication Middleware in Laravel
* **Architectural Flow of Request Filtering**
- When a user requests a secure page, the request travels through a pipeline before hitting your controller
```txt
Incoming Request
        __________________________________
       |                                  |
       v                                  v
Auth Middleware (yes Authenticated)    (NO Guest)
       |                                  |
       v                                  v
   Controller                      Redirect to Login
       |
       v
      View
                       
```
* **Protecting Routes with Middleware**
* We can attach the auth middleware directly to  routes within  web route file `routes/web.php`
```php
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Only authenticated users can access the dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');
```
* **Grouping Multiple Protected Routes**
* If we have several routes that require the user to be logged in, group them to avoid repeating the `middleware('auth')`
```php
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit']);
    Route::patch('/profile', [ProfileController::class, 'update']);
    
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);
});
```
* **Protecting Guest-Only Routes**
```php
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionController;

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/login', [SessionController::class, 'create']);
    Route::post('/login', [SessionController::class, 'store']);
});
```
* **Ensure Login Route is Named**
* If you encounter routing errors, double-check that your login route explicitly defines the name `login`
```php
Route::get('/login', [SessionController::class, 'create'])
    ->middleware('guest')
    ->name('login'); // <--- Crucial for the 'auth' middleware redirect
```
* **Customizing the Redirect Behavior**
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->redirectGuestsTo('/sign-in'); // Changes default from '/login'
    
    // Or specify a dynamic callback closure:
    // $middleware->redirectGuestsTo(fn () => route('login'));
})
```

```php
public function store()
{
    $attributes = request()->validate([
        'email' => ['required', 'email'],
        'password' => ['required']
    ]);

    if (! Auth::attempt($attributes)) {
        throw ValidationException::withMessages([
            'email' => 'Your credentials do not match our records.'
        ]);
    }

    request()->session()->regenerate();

    // Redirect to the URL they originally tried to visit, or fallback to /dashboard
    return redirect()->intended('/dashboard');
}
```
---
# Eloquent Relationship
* **One-to-One**
* A one-to-one relationship means a record in Table A belongs to exactly one record in Table B.
**Example**
* user has one profile
- `users` table: `id`, `name`, `email`
- `profiles` table: `id`, `user_id (Fk)`, `bio`, `avatar`

**The Model Implementation**
```php
// app/Models/User.php
public function profile()
{
    return $this->hasOne(Profile::class);
}
```
* **One-to-Many**
* This is the most common relationship type. A single record in Table A owns multiple records in Table B.
**Example**
* Post has many Comments, but each comment belongs to only one post.
- `posts` table: `id`, `title`, `body`
- `comments` table: `id`, `post_id (Fk)`, `body`
**The Model Implementation**
```php
// app/Models/Post.php
public function comments()
{
    return $this->hasMany(Comment::class);
}
```

```php
// app/Models/Comment.php
public function post()
{
    return $this->belongsTo(Post::class);
}
```
**Many-to-Many**
* A many-to-many relationship is more complex. A record in Table A can relate to multiple records in Table B, and vice versa.
- **Example**
* Post can have many Tags, and a Tag can belong to many Posts.
- `posts` table: `id`, `title`
- `tags` table: `id`, `name`
- `post_tag` Pivot Table: `post_id`, `tag_id`
**The Model Implementation**
```php
// app/Models/Post.php
public function tags()
{
    return $this->belongsToMany(Tag::class);
}
```
```php
// app/Models/Tag.php
public function posts()
{
    return $this->belongsToMany(Post::class);
}
```
**Interacting with Relationships**
```php
// Fetching a post and looping through its comments
$post = Post::find(1);

foreach ($post->comments as $comment) {
    echo $comment->body;
}
```
**Filtering with Relationship Queries**
```php
// Fetch only the comments approved today for this specific post
$approvedComments = $post->comments()->where('approved', true)->get();
```
---
# Authorization Using Gates
**Conceptual Framework of Gates**
* Think of a Gate exactly like a physical security checkpoint. It accepts the current authenticated user and the resource they want to modify, executing a quick true/false check.

```txt
[ User Action: Edit Job ]
                           │
                           ▼
                  [ Laravel Gate ]
                   /            \
        ( Returns True )     ( Returns False )
                 /                \
                ▼                  ▼
       Allow to Proceed      403 Forbidden Error
```

* **Defining a Gate**
* Gates are typically defined inside your application provider pipeline. In modern versions of Laravel, you define them inside the `boot()` method of `app/Providers/AppServiceProvider.php` file using the `Gate::define()` method
```php
namespace App\Providers;

use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define a gate to check if a user can edit a specific job listing
        Gate::define('edit-job', function (User $user, Job $job) {
            return $job->employer->user->is($user);
        });
    }
}
```
**Enforcing Gates in the Controller**
* Inline Gate Check (`Gate::allows` / `Gate::denies`)
```php
use Illuminate\Support\Facades\Gate;

public function edit(Job $job)
{
    if (Gate::denies('edit-job', $job)) {
        abort(403);
    }

    return view('jobs.edit', ['job' => $job]);
}
```
* The Streamlined `authorize()` Method
- Modern controller workflows use the cleaner wrapper method directly.
- If the gate fails, it immediately terminates execution and throws an automatic `403 Forbidden` response page

```php
public function edit(Job $job)
{
    // If this fails, Laravel throws a 403 HTTP exception automatically
    Gate::authorize('edit-job', $job);

    return view('jobs.edit', ['job' => $job]);
}
```
**Checking via the User Instance**
* You can also run authorizations directly through the authenticated user model

```php
public function edit(Job $job)
{
    if (request()->user()->cannot('edit-job', $job)) {
        abort(403);
    }

    return view('jobs.edit', ['job' => $job]);
}
```
**Conditionally Hiding UI Elements in Blade Views**
```html
@extends('layouts.app')

@content
    <h1>{{ $job->title }}</h1>
    <p>{{ $job->description }}</p>

    @can('edit-job', $job)
        <div class="mt-6">
            <a href="/jobs/{{ $job->id }}/edit" class="btn-primary">Edit Job</a>
        </div>
    @endcan
@endcontent
```
**Applying Gates via Route Middleware**
```php
use App\Http\Controllers\AdminController;

// The user must pass the 'see-admin-dashboard' gate to load this route
Route::get('/admin', [AdminController::class, 'index'])
    ->middleware('can:see-admin-dashboard');
```
---

# Authorization Using Policies
* **Gates vs. Policies**
```txt
Gates Approach:       AppServiceProvider──> ( Contains ALL application rules )
Policies Approach:   Job Policy──> ( Holds rules ONLY for Job model )
                      Post Policy──> ( Holds rules ONLY for Post model )
```

* **Creating a Policy**
```bash
php artisan make:policy 
```
- This creates a clean file under the `app/Policies/`
* **Defining Authorization Rules**
```php
namespace App\Policies;

use App\Models\Job;
use App\Models\User;

class JobPolicy
{
    /**
     * Determine whether the user can update the job listing.
     */
    public function update(User $user, Job $job): bool
    {
        // Only the employer who posted the job can update it
        return $job->employer->user->is($user);
    }
}
```
* **Enforcing Policies in Controllers**
```php
public function edit(Job $job)
{
    // Laravel looks up JobPolicy and executes the update() method
    Gate::authorize('update', $job);

    return view('jobs.edit', ['job' => $job]);
}
```
* **Utilizing the User Model Instance**
```php
public function edit(Job $job)
{
    if (request()->user()->cannot('update', $job)) {
        abort(403);
    }

    return view('jobs.edit', ['job' => $job]);
}
```
* **Using Policies in Blade Views**
```html
@can('update', $job)
    <div class="mt-6">
        <a href="/jobs/{{ $job->id }}/edit" class="btn">Edit JobListing</a>
    </div>
@endcan
```
* **Applying Policies via Route Middleware**
```php
use App\Http\Controllers\JobController;

// Laravel looks up the 'job' route variable, inspects its policy, and runs 'update'
Route::patch('/jobs/{job}', [JobController::class, 'update'])
    ->middleware('can:update,job');
```
---