# Install & Explore Laravel Breeze

## Overview

In this section, the project transitions from a basic CRUD API into an authenticated REST API using **Laravel Breeze** and **Laravel Sanctum**.

The goal is to scaffold authentication while adapting Laravel Breeze, which is designed primarily for web applications, into a token-based API authentication system.



# API Resources Improvements

Before starting authentication, all controller responses are updated to consistently use **API Resources**.

Instead of returning Eloquent models directly:

```php
return $post;
```

The application returns:

```php
return new PostResource($post);
```

This provides a standardized JSON response and keeps the API response structure consistent.



## Returning Resources in Every CRUD Operation

Every endpoint that returns a post should return a resource.

### Create

```php
return new PostResource($post);
```

### Show

```php
return new PostResource($post);
```

### Update

```php
return new PostResource($post);
```

### Delete

No response body is returned.

---

# Conditional Relationships

Sometimes the API should include the author information.

Sometimes it shouldn't.

Instead of always returning:

```php
'author' => new UserResource($this->author)
```

Laravel Resources provide `whenLoaded()`.

```php
'author' => UserResource::make(
    $this->whenLoaded('author')
)
```

Now the author will only appear if the relationship has already been eager loaded.



## Without Loading the Relationship

```php
Post::paginate();
```

Response:

```json
{
    "id": 1,
    "title": "...",
    "body": "..."
}
```

No author information is returned.



## With Eager Loading

```php
Post::with('author')->get();
```

Since the relationship is loaded, the resource automatically includes:

```json
{
    "id": 1,
    "title": "...",
    "author": {
        ...
    }
}
```

No additional logic is required.



# Loading Relationships

Instead of:

```php
Post::all();
```

Load the relationship:

```php
Post::with('author')->get();
```

Important correction:

`all()` cannot be called after `with()`.

Correct:

```php
Post::with('author')->get();
```



# Pagination

Instead of retrieving every record:

```php
Post::with('author')->get();
```

Use pagination:

```php
Post::with('author')
    ->paginate();
```

Laravel automatically returns:

```json
{
    "data": [...],
    "links": {...},
    "meta": {...}
}
```



## Pagination Metadata

The response includes useful information such as:

- Current page
- Last page
- Items per page
- Total records
- First item
- Last item

Example:

```php
paginate(2);
```

Only two records are returned per request.

The client can use the metadata to build pagination controls.


# Resource Wrapping

Returning a Resource directly:

```php
return new PostResource($post);
```

produces:

```json
{
    "data": {
        ...
    }
}
```

Everything is wrapped inside `data`.


## Returning Custom JSON

Using:

```php
return response()->json(
    new PostResource($post)
);
```

does not produce Laravel's default wrapping.

Laravel only wraps resources when they are returned directly.


# Disabling Data Wrapping

Wrapping can be disabled for one resource.

Inside the Resource:

```php
public static $wrap = null;
```

Now:

```json
{
    "id": 1,
    "title": "..."
}
```

instead of

```json
{
    "data": {
        ...
    }
}
```



## Disable Wrapping Globally

Inside AppServiceProvider:

```php
JsonResource::withoutWrapping();
```

Now every resource returned by the application will no longer be wrapped.

Collections still contain:

- data
- links
- meta

because pagination requires additional metadata.



# Version Control Before Authentication

Before adding authentication, the project state is saved.

Check modified files:

```bash
git status
```

Stage changes:

```bash
git add .
```

Create a commit:

```bash
git commit -m "Create post CRUD with resources"
```

The tutorial recommends making commits frequently, but only after meaningful progress rather than after every small change.



# Installing Laravel Breeze

Install Breeze as a development dependency.

```bash
composer require laravel/breeze --dev
```

Install Breeze.

```bash
php artisan breeze:install
```

For this project choose:

- API Only
- Pest testing framework



# What Laravel Breeze Generates

Laravel Breeze scaffolds authentication for different stacks including:

- Blade + Alpine
- Livewire
- React + Inertia
- Vue + Inertia
- API Only

For this REST API project, only **API Only** is used.



# Database Migration

During installation Breeze asks whether pending migrations should run.

```bash
php artisan migrate
```

Since Sanctum migrations were already installed earlier, there may be nothing new to migrate.



# Project Changes After Installation

Laravel Breeze modifies many files.

Examples include:

## Composer

Adds Laravel Breeze dependency.



## Controllers

Creates authentication controllers for:

- Registration
- Login
- Password Reset
- Email Verification


## Middleware

Adds middleware such as:

- EnsureEmailIsVerified



## Requests

Creates request classes like:

```text
LoginRequest
```

These contain validation and authentication logic.



## AppServiceProvider

Adds URL generation logic for password reset functionality.


## Bootstrap

Adds authentication middleware.

Example:

```php
EnsureFrontendRequestsAreStateful::class
```



## Routes

Creates:

```text
routes/auth.php
```

Authentication routes are placed here.



## Removes Frontend Assets

Since this project is API-only, Laravel removes frontend files.

Deleted assets include:

- CSS
- JavaScript
- Blade views
- Vite configuration

No frontend assets are needed.


# Understanding Generated Code

One of the important lessons emphasized is understanding generated code.

Generated code may come from:

- AI
- Laravel packages
- Third-party packages

Developers should learn to:

- Read generated code
- Modify it
- Remove unnecessary parts
- Understand why it exists

instead of blindly using it.


# Authentication Routes

Laravel Breeze places authentication routes inside:

```text
routes/auth.php
```

These routes are included inside:

```php
web.php
```

However, this project is an API.

Authentication endpoints should belong to:

```text
api.php
```

instead of:

```text
web.php
```

The authentication routes are moved from:

```php
web.php
```

to

```php
api.php
```

---

# Session Authentication vs Token Authentication

Laravel Sanctum supports two authentication styles.

## Session-Based Authentication

Used for:

- Blade
- SPA
- Browser applications

Uses:

- Sessions
- Cookies



## Token-Based Authentication

Used for:

- REST APIs
- Mobile Applications
- External Clients

Uses:

- API Tokens

No sessions are involved.

This project adopts **Token Authentication**.



# Removing Stateful Middleware

Bootstrap contains:

```php
EnsureFrontendRequestsAreStateful::class
```

Since REST APIs are stateless, this middleware is removed.

```php
->withMiddleware(function ($middleware) {

    // Remove EnsureFrontendRequestsAreStateful

});
```

Requests should remain completely independent.



# Why Sessions Are Removed

REST APIs are stateless.

Each request must contain everything needed for authentication.

The server should not remember previous requests through sessions.

Authentication will instead rely entirely on Bearer Tokens.
---
# Register, Login & Protected Routes

## Overview

In this section, Laravel Breeze authentication is transformed into a REST API authentication system using **Laravel Sanctum**.

Instead of authenticating users with sessions and cookies, the application authenticates users using **API Tokens**. Users can register, log in, receive a personal access token, access protected endpoints, and log out by revoking their current token.




# Using the HasApiTokens Trait

Laravel Sanctum requires the `User` model to use the `HasApiTokens` trait.

```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
}
```

Without this trait, users cannot create personal access tokens.


# Register Endpoint

Laravel Breeze already generates a registration controller.

The controller validates the incoming request before creating a new user.

```php
$request->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => [
        'required',
        'string',
        'email',
        'max:255',
        'unique:users'
    ],
    'password' => [
        'required',
        'confirmed',
        Rules\Password::defaults(),
    ],
]);
```

After validation, the user is created.

```php
$user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
]);
```

Since registration only creates a user, the endpoint simply returns:

```php
return response()->noContent();
```

which produces HTTP **204 No Content**.


# Password Validation

The password uses Laravel's default password rules.

```php
Rules\Password::defaults()
```

The default rule enforces a minimum password length.

Additional requirements can also be added manually.

Example:

```php
Rules\Password::min(8)
    ->letters()
    ->numbers()
    ->symbols();
```

This allows stronger password policies when needed.


# Password Confirmation

The `confirmed` rule requires another request field.

Example request:

```json
{
    "password": "password123",
    "password_confirmation": "password123"
}
```

If the confirmation field is missing or different, Laravel returns a validation error.


# Register Request in Postman

The registration endpoint is:

```
POST /api/register
```

Headers:

```http
Accept: application/json
Content-Type: application/json
```

Example body:

```json
{
    "name": "John",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

Successful registration returns:

```
204 No Content
```


# Login Controller

Laravel Breeze creates an `AuthenticatedSessionController`.

Since this project is API-based, it is renamed to:

```
LoginController
```

This better reflects its purpose.


# Login Request

Instead of validating inside the controller, Laravel Breeze uses a custom request class.

```php
LoginRequest
```

The request validates:

```php
[
    'email',
    'password'
]
```

It also performs authentication.

```php
$request->authenticate();
```


# How Authentication Works

Inside `LoginRequest`, Laravel calls:

```php
Auth::attempt([
    'email' => $this->email,
    'password' => $this->password,
]);
```

If authentication fails,

Laravel throws a validation exception.

Example response:

```json
{
    "message": "The provided credentials do not match our records."
}
```

If authentication succeeds,

the authenticated user becomes available through:

```php
$request->user()
```


# Creating API Tokens

After successful authentication,

the authenticated user generates a personal access token.

```php
$token = $request->user()
    ->createToken('main')
    ->plainTextToken;
```

`plainTextToken` is returned only once.

Laravel stores only the hashed version inside the database.

The original token cannot be retrieved again.


# Login Response

Instead of returning only the user,

the API returns both the authenticated user and the generated token.

Example:

```php
return [
    'user' => new UserResource($request->user()),
    'token' => $token,
];
```

Example response:

```json
{
    "user": {
        ...
    },
    "token": "1|kjsdf8sd..."
}
```


# Removing Session Logic

Laravel Breeze is originally designed for browser authentication.

The generated controller contains:

```php
$request->session()->regenerate();
```

Since REST APIs are stateless,

this line is removed.

No session should be created after login.


# Logout

Instead of destroying a session,

the API deletes the currently authenticated token.

```php
$request->user()
    ->currentAccessToken()
    ->delete();

return response()->noContent();
```

After logout,

the token becomes invalid immediately.


# Protecting API Routes

Protected routes use Sanctum middleware.

Instead of protecting every route individually,

they are grouped.

```php
Route::middleware('auth:sanctum')
    ->group(function () {

        // Protected routes

    });
```

Only authenticated users may access these endpoints.


# Bearer Token Authentication

Every protected request must send:

```http
Authorization: Bearer YOUR_TOKEN
```

Example:

```http
GET /api/v1/posts

Authorization: Bearer 1|as8f79sd...
```

If the token is valid,

Laravel authenticates the request automatically.


# Unauthorized Requests

If no token is provided,

Laravel returns:

```
401 Unauthorized
```

The same happens when:

- the token is invalid
- the token was deleted
- the token expired


# Using Postman

After login,

the returned token is copied into the Postman Authorization tab.

Authentication Type:

```
Bearer Token
```

Paste:

```
1|xxxxxxxxxxxxxxxx
```

Every request inside the collection inherits this authorization automatically.


# Returning Only User Posts

Originally,

the controller returned every post.

```php
Post::paginate();
```

Instead,

the authenticated user's relationship is used.

```php
$request->user()
    ->posts()
    ->paginate();
```

Now each user only sees their own posts.


# Creating Posts for the Authenticated User

Previously,

the author was hardcoded.

```php
'author_id' => 1
```

Instead,

use:

```php
'author_id' => $request->user()->id
```

Now every created post automatically belongs to the logged-in user.


# Access Control

Users should never access posts belonging to another user.

Before returning a post,

ownership is verified.

```php
if ($request->user()->id != $post->author_id) {

    abort(403, 'Access forbidden');

}
```

Laravel returns:

```
403 Forbidden
```

if the authenticated user does not own the resource.

# Simplifying Authorization

Laravel provides a helper.

Instead of:

```php
if (...) {
    abort(...);
}
```

Use:

```php
abort_if(
    Auth::id() != $post->author_id,
    403,
    'Access forbidden'
);
```

This produces cleaner code.



# Protecting Update Requests

The same ownership check is performed before updating.

```php
abort_if(
    Auth::id() != $post->author_id,
    403
);
```

Users cannot modify posts they do not own.



# Protecting Delete Requests

Deleting follows the same principle.

```php
abort_if(
    Auth::id() != $post->author_id,
    403
);
```

Only the owner may delete the post.

# Testing CRUD Operations

The API endpoints tested in Postman include:

## Register

```
POST /api/register
```



## Login

```
POST /api/login
```

Returns:

- User
- Token



## Get Posts

```
GET /api/v1/posts
```

Returns only authenticated user's posts.



## Create Post

```
POST /api/v1/posts
```

Creates a new post for the authenticated user.



## Get Single Post

```
GET /api/v1/posts/{id}
```

Returns the post only if it belongs to the authenticated user.



## Update Post

```
PUT /api/v1/posts/{id}
```

Updates the authenticated user's post.



## Delete Post

```
DELETE /api/v1/posts/{id}
```

Deletes the authenticated user's post.



## Logout

```
POST /api/logout
```

Deletes the current access token.

After logout,

any request using that token returns:

```
401 Unauthorized
```
---
# Rate Limiting

## Overview

Rate limiting protects an application from excessive or abusive requests by limiting how many requests a client can make within a specific period.

Laravel provides a built-in Rate Limiter that can be applied to authentication, API endpoints, or any custom route.




# Built-in Login Rate Limiting

The generated `LoginRequest` already includes rate limiting.

Before attempting authentication, Laravel checks whether the user has exceeded the maximum number of failed login attempts.

```php
$this->ensureIsNotRateLimited();
```

If the user has exceeded the limit, a validation exception is thrown before authentication is attempted.



# How Login Rate Limiting Works

The authentication flow is:

1. Check if the request is rate limited.
2. Attempt authentication.
3. If authentication fails:
   - Increment the failed attempts counter.
4. If authentication succeeds:
   - Clear all previous failed attempts.

```php
$this->ensureIsNotRateLimited();

if (! Auth::attempt(...)) {

    RateLimiter::hit($this->throttleKey());

    throw ValidationException::withMessages([
        'email' => trans('auth.failed'),
    ]);
}

RateLimiter::clear($this->throttleKey());
```



# Throttle Key

Laravel identifies each login attempt using a unique throttle key.

The key combines:

- User email
- Client IP address

Example:

```php
$this->email.'|'.$request->ip();
```

This allows different users on the same network to have separate rate limits.


# RateLimiter::hit()

Whenever authentication fails:

```php
RateLimiter::hit(
    $this->throttleKey()
);
```

Laravel records another failed attempt.



# RateLimiter::clear()

When authentication succeeds:

```php
RateLimiter::clear(
    $this->throttleKey()
);
```

All previous failed attempts are removed.

The next login starts with a clean state.


# Login Lockout

If too many failed attempts occur,

Laravel throws a validation exception.

Example response:

```json
{
    "message": "Too many login attempts. Please try again in 46 seconds."
}
```

The remaining wait time is automatically calculated.

---

# Why Rate Limiting Matters

Without rate limiting,

an attacker could repeatedly try different passwords.

Rate limiting reduces the effectiveness of brute-force attacks by forcing users to wait after multiple failed attempts.



# Simple API Rate Limiting

Besides login protection,

Laravel allows rate limiting for any API endpoint.

Instead of manually checking requests,

a middleware can be used.

Example:

```php
Route::middleware('throttle:60,1')
    ->group(function () {

        ...

    });
```

This means:

- Maximum 60 requests
- Every 1 minute



# Creating a Custom Rate Limiter

Instead of writing numbers directly inside middleware,

Laravel allows named rate limiters.

Inside `AppServiceProvider`:

```php
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
```

Create a limiter:

```php
RateLimiter::for('api', function (Request $request) {

    return Limit::perMinute(60);

});
```

Now every route using the `api` limiter shares the same configuration.



# Grouping Requests

Instead of limiting every client together,

Laravel groups requests by a unique identifier.

Example:

```php
return Limit::perMinute(60)
    ->by(
        $request->user()?->id
            ?: $request->ip()
    );
```

If the user is authenticated,

their User ID is used.

Otherwise,

their IP address is used.

This prevents one user's requests from affecting another user.



# Applying the Custom Limiter

After defining the limiter,

apply it through middleware.

```php
Route::middleware([
    'auth:sanctum',
    'throttle:api'
])->group(function () {

    ...

});
```

Every endpoint inside the group now shares the configured rate limit.



# Testing Rate Limiting

To demonstrate the limiter,

the request limit is temporarily reduced.

```php
Limit::perMinute(2);
```

The first request:

```
200 OK
```

Second request:

```
200 OK
```

Third request:

```
429 Too Many Requests
```

Laravel automatically blocks additional requests until the time window resets.



# HTTP Status Code 429

When the limit is exceeded,

Laravel returns:

```
429 Too Many Requests
```

This tells the client to wait before sending more requests.



# Dynamic Rate Limits

The callback passed to `RateLimiter::for()` allows custom logic.

Example:

```php
RateLimiter::for('api', function (Request $request) {

    if ($request->user()?->isAdmin()) {

        return Limit::perMinute(1000);

    }

    return Limit::perMinute(60);

});
```

Different users can have different request limits depending on their role.



# Other Available Limits

Laravel provides several helper methods.

Examples:

```php
Limit::perSecond(5);
```

```php
Limit::perMinute(60);
```

```php
Limit::perHour(500);
```

```php
Limit::perDay(5000);
```

Choose the interval that best matches your application's needs.

---
# Real World API Project

## Overview

After completing CRUD operations, authentication, authorization, and rate limiting, the project is ready to move into building a real-world API.

Before starting the implementation, the instructor saves the current progress by creating another Git commit.

The remaining project will also introduce AI-assisted development using Cursor while ensuring that every generated piece of code is explained and understood rather than copied blindly.




# Saving Project Progress

Before implementing the final project, all completed work is committed.

```bash
git status
```

```bash
git add .
```

```bash
git commit -m "Authentication and Rate Limiting"
```

Creating meaningful commits makes it easier to return to stable versions of the project.



# Using AI as a Development Tool

The project switches from VS Code to **Cursor**.

The purpose is not to let AI build the application automatically, but to use it as a productivity tool.

AI is used to:

- Generate repetitive code.
- Speed up development.
- Assist with implementation.

Every generated section is reviewed and explained before being accepted.


# Understanding Generated Code

Even when AI generates code,

developers should:

- Read every line.
- Understand the implementation.
- Modify the generated code when necessary.
- Avoid copying code without understanding it.

AI should assist the developer rather than replace the learning process.


