# Strategy Pattern
> The Strategy Pattern is a behavioral design pattern that allows us to define multiple interchangeable behaviors and choose between them without changing the code that uses them.

## What is the Strategy Pattern?

- >The **Strategy Pattern** allows us to encapsulate different ways of performing
the same operation into separate classes.
- **Send Notifications**
```java 
void NotifyOrderShippedByEmail(Order order);
void NotifyOrderShippedBySms(Order order);
```
- Instead of having one class containing many conditions like:

```c#
if ($method === 'email') {
    // send email
} elseif ($method === 'sms') {
    // send SMS
}// send push notification

```txt
            Strategy Interface
                  |
        -----------------------
        |          |          |
      Email       SMS       Push
     Strategy   Strategy   Strategy
```


- **Applying the Strategy Pattern**
```c#
interface IOrderNotifier
{
   void NotifyOrderShipped(Order order); 
}

class EmailService : IOrderNotifier
{
    void NotifyOrderShipped(Order order)
    {
      var subject = $"Order {order.Id} Shipped";
      var message = $"Your order has been shipped";
      Send(subject, order.CustomerEmail, message)
    }
   void Send (String subject,String to, String message )
    {
      //
    }
}

class SmsService : IOrderNotifier
{
    void NotifyOrderShipped(Order order)
    {
      var message = $"Your order has been shipped";
      Send(order.CustomerPhone, message)
    }
   void Send (String to, String message )
    {
      //
    }
}
```
```c#
class OrderService
{
    private readonly IOrderNotifier _notifier;

    public OrderService(IOrderNotifier notifier)
    {
        _notifier = notifier;
    }

    public void ShipOrder(Order order)
    {
        // Ship order...

        _notifier.NotifyOrderShipped(order);
    }
}
var emailService = new EmailService();
var orderService = new OrderService(emailService);

orderService.ShipOrder(order);
var smsService = new SmsService();
var orderService = new OrderService(smsService);

orderService.ShipOrder(order);
```

```txt
                OrderService
                     |
                     v
             IOrderNotifier
               /          \
              /            \
     EmailService       SmsService
```
---
# Factory Method Pattern
- The main problem is **object creation**.
- We often need to create different types of objects that share some common
behavior.

```text
Object
├── Type A
├── Type B
└── Type C
```
```c++
if (type == A)
{
    object = new TypeA();
}
else if (type == B)
{
    object = new TypeB();
}
else if (type == C)
{
    object = new TypeC();
}
```
## The problem is that the code using the object now needs to know:
> Which concrete class to instantiate
> How the object is created
> All possible object types
# Design Pattern Concept
```txt
Client
  |
  v
Factory Method
  |
  ------> Concrete Product A
  |
  ------> Concrete Product B
  |
  ------> Concrete Product C
```
## Problem Solution
```c++
//First, define a common interface for the objects.

class Product
{
public:
    virtual void operation() = 0;
    virtual ~Product() = default;
};

//Then create concrete products:

class ProductA : public Product
{
public:
    void operation() override
    {
        // Product A behavior
    }
};
class ProductB : public Product
{
public:
    void operation() override
    {
        // Product B behavior
    }
};
```
# Factory Method
> Instead of directly creating the products, define a factory method:
```c#
class Creator
{
public:
    virtual Product* createProduct() = 0;

    virtual ~Creator() = default;
};
//Now different creators can decide what product to create.
class CreatorA : public Creator
{
public:
    Product* createProduct() override
    {
        return new ProductA();
    }
};
class CreatorB : public Creator
{
public:
    Product* createProduct() override
    {
        return new ProductB();
    }
};
```
```txt

Creator
   |
   ----- createProduct()
             |
       ----------------
       |              |
       v              v
   ProductA        ProductB
```
> The creator defines how the object is requested, while the concrete creator decides which concrete object is created.
```c#
//The client can work with the abstract creator:
Creator* creator = new CreatorA();
Product* product = creator->createProduct();
product->operation();


Creator* creator = new CreatorB();
Product* product = creator->createProduct();
product->operation();

//The client does not need to directly write:
//new ProductA();
//new ProductB();
```

```txt
Without the pattern:
Client
  |
  ----- creates object
  |
  ----- uses object

With Factory Method:
Client
  |
  v
Creator
  |
  v
Factory Method
  |
  v
Product
```
- **Imagine we have different types of notifications**
- The problem is that the code responsible for using the notification
also knows how every notification is created
```txt
          Application
               |
       -------------------
       |        |        |
     Email     SMS      Push
     new()     new()    new()
```
## Structure
```txt
                    Creator
                       |
                createProduct()
                       |
              -------------------
              |                 |
              v                 v
         ConcreteCreatorA  ConcreteCreatorB
              |                 |
              v                 v
           ProductA          ProductB
              \                 /
               \               /
                ---- Product ----
```
- **Applying the Factory Method Pattern**
## Using the Factory Method
- Now the client does not need to know how the notification is created
```c#
//email
NotificationCreator creator =
    new EmailNotificationCreator();

creator.SendNotification("Your order has been shipped.");
//sms
NotificationCreator creator =
    new SmsNotificationCreator();

creator.SendNotification("Your order has been shipped.");
//push
NotificationCreator creator =
    new PushNotificationCreator();

creator.SendNotification("Your order has been shipped.");
//client only works with:
NotificationCreator
INotification
```
## Structure
```txt
                    Creator
                       |
             CreateNotification()
                       |
          ---------------------------
          |            |            |
          v            v            v
   EmailCreator   SmsCreator   PushCreator
          |            |            |
          v            v            v
       Email          SMS          Push
    Notification   Notification  Notification
```

## Without Factory Method

- >The client creates the object:
```c#
var notification = new EmailNotification();
```
## With Factory Method

- >The creator creates the object:
```c#
var notification = CreateNotification();
```
- The client only knows the abstraction:`INotification`
## Advantages
1. Loose Coupling
2. Easier Extension
3. Encapsulates Object Creation

---
# Concurrency
```txt
 --- <---- process 1
|CPU|     |Switching
 --- <----process 2 
```
## What is Concurrency?

- **Concurrency** means dealing with multiple tasks during the same period
  of time.
- Multiple tasks can make progress without necessarily executing at the
  exact same moment.
- The operating system/runtime can switch between tasks and manage their
  execution.

```text
Task A: ████      ████
Task B:     ████      ████
Task C:         ████      ████

       Time  ───────────────>
```
- **overlapping progress**, not necessarily executing
everything simultaneously.
# Why Do We Need Concurrency?
>Real applications often have multiple things to handle.
```txt
Application
    |
    +--> Handle User Request
    |
    +--> Read From Database
    |
    +--> Write To File
    |
    +--> Perform Background Work
```
- Executing everything strictly one after another can cause unnecessary
waiting.
- Concurrency allows the application to make progress on other work while
one task is waiting.

* **Without concurrency, tasks are generally handled one after another.**

```txt
Task A ─────────>
Task B             ─────────>
Task C                         ─────────>

Time ───────────────────────────────>
```
* **With concurrency, tasks can overlap**
# Single Core vs Multiple Cores
## Single Core
- Only one thread can execute at an instant.
The system can switch between threads.
```txt
Time ─────────────────────>

Thread A  ████      ████
Thread B      ████      ████
```
- This can provide concurrency without true parallel execution.
## Multiple Cores
- Different threads can execute at the same time.
```txt
Core 1 ─── Thread A ─────────>
Core 2 ─── Thread B ─────────>
Core 3 ─── Thread C ─────────>
```

- This can provide actual parallelism.
# Context Switching
- When multiple threads share a CPU, the system can switch execution from
one thread to another.
* **This is called context switching**
```txt
Thread A
   ↓
[Running]
   ↓
[Pause]
   ↓
Thread B
   ↓
[Running]
   ↓
[Pause]
   ↓
Thread A
```
- The system saves the state of one thread and restores the state of another.
---
# Concurrency Demo
- A **Race Condition** happens when multiple threads access and modify the
  same shared data at the same time.
- The final result depends on the **timing and order** in which the threads
  execute.
- This can cause unexpected or incorrect results.

```text
Thread 1 ──────┐
               │
               v
           Shared Data
               ^
               │
Thread 2 ──────┘
```
## Suppose we have a shared variable:
`int counter = 0;`
- Multiple threads try to increment it:
`counter++;`
- The statement looks like one operation, but it involves multiple steps:
```txt
Read
  ↓
Modify
  ↓
Write
```
# Race Condition
- Two threads can interleave their operations:
```txt
Thread 1              Thread 2

Read counter
                     Read counter

Increment
                     Increment

Write counter
                     Write counter
```
- Both threads may read the same value.
- One update can overwrite the other update.
- Therefore, the final result can be smaller than expected.
```c#
int counter = 0;

void Increment()
{
    counter++;
}
```
- Imagine two threads execute Increment() many times.
```txt
Thread 1 → counter++
Thread 2 → counter++
```
- We expect every increment to be counted.
- Because the operation is not protected, updates can be lost.
## Why Does This Happen?
```txt
counter++

      ↓

Read counter
      ↓
Calculate counter + 1
      ↓
Write the new value
```
- Another thread can execute between these steps.
- This creates a race between the threads.
## How Can We Solve It?
- The shared operation needs synchronization so that multiple threads
```txt
Race Condition
      ↓
Synchronization
      ↓
Protect Shared Data
Main Idea
Multiple Threads
       |
       v
Shared Data
       |
       v
Unsynchronized Access
       |
       v
Race Condition
       |
       v
Incorrect / Unexpected Result
```
# Race Condition vs Atomicity
```txt
Atomicity
   ↓
Protect an operation

Race Condition
   ↓
Problem caused by unsafe concurrent access
```
---
# Atomicity
## What is Atomicity?
- An **atomic operation** is an operation that cannot be observed in a
  partially completed state.
- It either happens completely or does not happen.
- Other threads should not be able to observe an intermediate state.

```text
Atomic Operation

Start
  |
  v
[ Operation ]
  |
  v
Complete
```
```c++
int sum+ = x;
```
1. Read x
2. Read Sum
3. Arithmetic
4. Store
```c++
x++;
```
1. Read x
2. Increment
3. Store 
---
# Deadlock
## What is a Deadlock?
- A deadlock can happen when multiple threads need more than one shared
  resource.
- Each thread acquires one resource and then waits for another resource
  that is currently held by another thread.

```text
Thread 1                    Thread 2

Locks Resource A            Locks Resource B
      |                           |
      v                           v
Waits for B                Waits for A
      |                           |
      +------------+--------------+
                   |
                DEADLOCK
```
- Thread can acquire a resource using a lock.
- If another thread already owns that resource, the second thread must wait.
- A deadlock happens when threads create a circular dependency.
```txt
Thread 1
   |
   | holds
   v
Resource A
   |
   | waits for
   v
Resource B
   ^
   | holds
   |
Thread 2
   |
   | waits for
   v
Resource A
```
# Resources
- A resource is something that a thread needs in order to continue.
```c#
object resourceA = new object();
object resourceB = new object();

//A thread can acquire a lock before accessing a protected resource.

lock (resourceA)
{
    // Access resource A
}

//Thread 1 acquires resourceA and then tries to acquire resourceB.

lock (resourceA)
{
    Thread.Sleep(100);

    lock (resourceB)
    {
        // Work
    }
}

//Thread 2 acquires resourceB and then tries to acquire resourceA.

lock (resourceB)
{
    Thread.Sleep(100);

    lock (resourceA)
    {
        // Work
    }
}
```

# What Happens?
```txt
Thread 1                    Thread 2

lock(resourceA)             lock(resourceB)
      |                           |
      v                           v
   holds A                    holds B
      |                           |
      v                           v
waits for B                 waits for A
      |                           |
      +------------+--------------+
                   |
                DEADLOCK
```
>Thread 1 owns resourceA and waits for resourceB.
>Thread 2 owns resourceB and waits for resourceA.
>Both threads remain blocked.

# Circular Wait

```txt
Thread 1
   |
   | holds
   v
Resource A
   |
   | needed by
   v
Thread 2
   |
   | holds
   v
Resource B
   |
   | needed by
   v
Thread 1
```
```c#
class Program
{
    static object resourceA = new object();
    static object resourceB = new object();

    static void Main()
    {
        Thread thread1 = new Thread(Thread1Work);
        Thread thread2 = new Thread(Thread2Work);

        thread1.Start();
        thread2.Start();

        thread1.Join();
        thread2.Join();
    }

    static void Thread1Work()
    {
        lock (resourceA)
        {
            Console.WriteLine("Thread 1 locked Resource A");

            Thread.Sleep(100);

            lock (resourceB)
            {
                Console.WriteLine("Thread 1 locked Resource B");
            }
        }
    }

    static void Thread2Work()
    {
        lock (resourceB)
        {
            Console.WriteLine("Thread 2 locked Resource B");

            Thread.Sleep(100);

            lock (resourceA)
            {
                Console.WriteLine("Thread 2 locked Resource A");
            }
        }
    }
}
```
```txt
Thread 1 -> A -> waits for B
Thread 2 -> B -> waits for A
```

# How to Avoid Deadlocks

1. Always Acquire Locks in the Same Order
```c#
static void Work()
{
    lock (resourceA)
    {
        lock (resourceB)
        {
            // Work
        }
    }
}
```

2. Keep Lock Scope Small

```c#
lock (resourceA)
{
    // Only the required critical section
}
```

3. Avoid Unnecessary Multiple Locks
```txt
One lock
   ↓
Lower dependency complexity

Multiple locks
   ↓
More possible dependencies
   ↓
Higher deadlock risk
```
# Deadlock vs Race Condition

## Race Condition
```txt
Multiple threads
      ↓
Shared data
      ↓
Unsafe concurrent access
      ↓
Unexpected result
```
> The program can continue running, but the result may be incorrect.

## Deadlock
```txt
Multiple threads
      ↓
Acquire resources
      ↓
Wait for each other
      ↓
Circular dependency
      ↓
No progress
```
>The threads can remain blocked indefinitely.
---
# Deadlock Demo

## Definition of Deadlock

- A **Deadlock** occurs when two or more threads are waiting for each
  other's resources.
- This creates a cycle where none of the threads can proceed.

```text
Thread A
   |
   | holds
   v
Resource 1
   |
   | waits for
   v
Resource 2
   ^
   | holds
   |
Thread B
   |
   | waits for
   v
Resource 1
```
> Since each thread is waiting for a resource held by another thread,both threads remain blocked.
## Thread A
- Locks Resource 1.
- Then tries to acquire Resource 2.
```txt
Thread A
   |
   ---> Lock Resource 1
   |
   ---> Wait for Resource 2
```
## Thread B
- Locks Resource 2.
- Then tries to acquire Resource 1.
```txt
Thread B
   |
   ---> Lock Resource 2
   |
   ---> Wait for Resource 1
```
# Result
```txt
Thread A → holds Resource 1 → waits for Resource 2
Thread B → holds Resource 2 → waits for Resource 1

                    ↓

                 DEADLOCK
```                 
# Demo Walkthrough
> The demo uses a simple multithreading example.
> Two threads attempt to acquire locks in opposite orders.
```c#
object resource1 = new object();
object resource2 = new object();
//Thread A
lock (resource1)
{
    // Work with Resource 1

    lock (resource2)
    {
        // Work
    }
}
//Thread B
lock (resource2)
{
    // Work with Resource 2

    lock (resource1)
    {
        // Work
    }
}
```
```txt
Thread A                    Thread B

Lock Resource 1             Lock Resource 2
      ↓                           ↓
Wait for Resource 2          Wait for Resource 1
      ↓                           ↓
      +-----------+---------------+
                  ↓
               DEADLOCK
```
```txt
Threads
   ↓
Blocked
   ↓
No Progress
   ↓
Program Appears Stuck
```
# Mitigation Strategies
1. Lock Ordering
- >Always acquire multiple locks in a consistent order.
- >Avoid acquiring them in opposite orders.
- >Consistent lock ordering prevents the circular dependency.
2. Timeouts
- >Use a timeout when trying to acquire a lock.
- >This prevents a thread from waiting indefinitely.
- >A timeout allows the application to detect that the resource is not available and take another action.
3. Deadlock Detection
- >Monitor thread states and resource allocation.
- >Look for cycles in the resource dependencies.
- >Detecting this cycle can indicate a deadlock.
4. Avoid Nested Locks
- >Minimize situations where multiple locks are held simultaneously.
- >Nested locks increase the complexity of resource dependencies.
- >Reducing the number of simultaneously held locks can reduce the risk of deadlocks.
---
# What is REST?
- REST stands for Representational State Transfer.
- It is an architectural style for building APIs using HTTP.
- Each URL represents a resource.
- HTTP methods define the action performed on that resource.
```txt
Resource
   |
   --> users
   --> posts
   --> orders
   --> products
```
# Statelessness

- Every request is independent from previous requests.
- The server does not keep session state for the client.
- The request must contain the information required by the server.
- A token can be sent with every request to identify the user.
```txt
Request 1  ──────> Server
Request 2  ──────> Server
Request 3  ──────> Server
```

# Client-Server Separation

- The backend and frontend are independent.
- The API can be used by different clients.
```txt
                 REST API
                    |
        ---------------------------
        |            |            |
      Web App     Mobile App    TV App
```
# Uniform Interface
- API endpoints should be consistent and predictable.
- Resources should be represented using plural nouns.
```txt
/api/users
/api/posts
/api/orders
/api/products
```
* **Avoid using verbs inside resource URLs**
```txt
/api/get-users
/api/create-post
```
# Resource Naming

- Resource names should use plural nouns.
- Resource names should be lowercase.
- For multiple words, use `_ -`
```txt
/api/country_products
/api/country-products
```
# REST Resources and Endpoints

## Collection
```txt
/api/users
/api/posts
```
* **Used when working with a collection**

## Specific Resource
```txt
/api/users/1
/api/posts/22
```
* **Used to work with one resource**

## Nested Resources
```txt
/api/users/1/posts
```
* **Represents posts belonging to user 1**

# HTTP Methods
```txt
Method  --------------------> Purpose
GET  --------------------> Read data
POST  --------------------> Create data
PUT  --------------------> Update data
PATCH  --------------------> Partially update data
DELETE  --------------------> Delete data
```
## GET
```txt
GET /api/posts
GET /api/posts/22
```
## POST
```txt
POST /api/posts
```
## PUT
```txt
PUT /api/posts/22
```

## PATCH
```txt
PATCH /api/posts/22
```

## DELETE
```txt
DELETE /api/posts/22
```
# PUT vs PATCH
## PUT
- Typically replaces the complete resource.
- Missing properties may be replaced.

## PATCH
- Updates only the provided properties.
```txt
PATCH:
name = "John"

Only the name changes.
```


# JSON Request and Response

- JSON is the typical format used to exchange API data.
```bash
{
    "data": {
        "id": 1,
        "name": "Zura",
        "email": "zura@example.com"
    }
}

//Error Response

{
    "message": "Validation failed",
    "errors": {
        "email": [
            "The email field is required."
        ]
    }
}
```



# API Versioning

- **Why Version APIs?**

- APIs evolve.
- Large changes can break existing clients.
- New versions allow old clients to keep working.
```txt
API v1
   |
   ---> Existing clients

API v2
   |
   ---> New clients
```
Example
```txt
/api/v1/posts
/api/v2/posts
```


# Pagination, Filtering and Sorting

## Pagination
```txt
/api/posts?page=2&per_page=10
```
- Page 2.
- 10 items per page.

## Sorting
```txt
/api/posts?sort=created_at&direction=desc
```
- Sort by created_at.
- Latest posts first.

## Filtering
```txt
/api/posts?author_id=3&status=published
```
- Filter by author.
- Filter by status.

# Git / Version Control

Initialize

`git init`

Track Files

`git add .`

Commit

`git commit -m "Initial commit"`

# Install Laravel API Support


`php artisan install:api`



`routes/api.php is created.`

- Authenticated User Route
```php
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
```
- Unauthorized API requests return 401.

# First API Route

- routes/api.php
```php
use Illuminate\Support\Facades\Route;

Route::get('/hello', function () {
    return [
        'message' => 'Hello Laravel API'
    ];
});
```
- Request:

`GET /api/hello`

- Response:
```php
{
    "message": "Hello Laravel API"
}
```
# Artisan


List Commands

`php artisan list`

List a Namespace

`php artisan make:list`

Command Help

`php artisan make:seeder --help`

Routes

`php artisan route:list`

Application Information

`php artisan about`

# Tinker

- Tinker is Laravel's interactive command-line shell.
- It can execute Laravel code directly.
- A major use is working with database data.
```bash
Start Tinker

php artisan tinker
```

- Random String

`Str::random();`

- Get Users

`User::all();`

- Create a User

`$user = new User;`
```php
$user->name = 'Zura';
$user->email = 'zura@example.com';
$user->password = bcrypt('password');
$user->save();
```

- Find a User

`$user = User::find(1);`

- Update
```php
$user->name = 'John';`
$user->save();`
```
- Delete

`$user->delete();`

# Postman

- Postman is introduced as an API client.
- It is used to send HTTP requests and inspect responses.

## GET
```bash
GET https://jsonplaceholder.typicode.com/posts
```
* **Returns JSON posts.**

## POST
```bash
POST https://jsonplaceholder.typicode.com/posts

Body:

{
    "title": "Test Post",
    "body": "Test Body"
}

Header:

Content-Type: application/json
```
* **The example returns 201**

## PUT

`PUT /posts/1`


## DELETE

`DELETE /posts/1`

# Content-Type vs Accept

## Content-Type

- Tells the server what the client is sending.
```bash
Content-Type: application/json

"I am sending JSON."
```

## Accept
- Tells the server what the client wants to receive.
```bash
Content-Type: application/json
Accept: application/json
Adding Accept: application/json makes Laravel return JSON-formattedunauthenticated API responses.

```

# Routes and Controllers

## Create Controller

`php artisan make:controller PostController`

```php

class PostController extends Controller
{
    public function index()
    {
        return 'index';
    }

    public function store()
    {
        return 'store';
    }
}
```

```php
use App\Http\Controllers\PostController;

Route::get('/posts', [PostController::class, 'index'])
    ->name('posts.index');

Route::post('/posts', [PostController::class, 'store'])
    ->name('posts.store');
```

```bash
GET  /api/posts
POST /api/posts
```
# Route Parameters

- Controller:
```php
public function show(string $id)
{
    return "show {$id}";
}
```
- Route:
```php
Route::get('/posts/{id}', [PostController::class, 'show'])
    ->name('posts.show');
```


# API Resource Controller

```txt

index   → Get all records
store   → Create a record
show    → Get one record
update  → Update a record
destroy → Delete a record
```
# API Resource Routes

Use:

`Route::apiResource('posts', PostController::class);`

- This creates the standard CRUD routes.

# API Versioning in Laravel

Use a prefix group:
```php
Route::prefix('v1')->group(function () {

    Route::apiResource('posts', PostController::class);

});
```
Endpoints:
```bash
/api/v1/posts
/api/v1/posts/{post}
```
- The course places the controller in:

`app/Http/Controllers/API/V1/PostController.php`

Create it with:

`php artisan make:controller API/V1/PostController --api`

- structure:
```txt
API/
├── V1/
│   └── PostController.php
└── V2/
    └── PostController.php
```

# JSON Responses

- Laravel can convert arrays to JSON automatically:
```php
return [
    'message' => 'Hello'
];
```

Explicit JSON:
```php
return response()->json([
    'message' => 'Hello'
]);
```
Custom Headers
```php
return response()->json([
    'message' => 'Hello'
])->header('test-header', 'zura');
```
* Headers can provide additional information to the client.

Status Code
```php
return response()->json([
    'message' => 'Created'
], 201);
```
No content:
```php
return response()->noContent();
```
# Request Data

Receive the request:
```php
use Illuminate\Http\Request;

public function store(Request $request)
{
    //
}
```
All Input
```php
$data = $request->all();

return $data;
```
Selected Input
```php
$data = $request->only([
    'title',
    'body'
]);

return $data;
```

# Validation

Basic validation:
```php
$data = $request->validate([
    'title' => ['required'],
    'body' => ['required'],
]);
```
* **If validation fails, Laravel returns a validation error response.***

```php
{
    "message": "The body field is required.",
    "errors": {
        "body": [
            "The body field is required."
        ]
    }
}
```
# CRUD Status Codes
```php

public function index()
{
    return [
        [
            'id' => 1,
            'title' => 'Post 1'
        ]
    ];
}
```
Store
```php
public function store(Request $request)
{
    $data = $request->validate([
        'title' => ['required'],
        'body' => ['required'],
    ]);

    return response()->json($data, 201);
}
```

Show
```php
public function show(string $id)
{
    return response()->json([
        'id' => $id
    ]);
}
```

Update
```php
public function update(Request $request, string $id)
{
    $data = $request->validate([
        'title' => ['required'],
        'body' => ['required'],
    ]);

    return response()->json($data);
}
```

```php
public function destroy(string $id)
{
    return response()->noContent();
}
```

# Models and Migrations

Create a model and migration together:

`php artisan make:model Post -m`

* Migration

- A migration stores database schema changes.
- It has two main methods.
```php
public function up()
{
    // Apply change
}

public function down()
{
    // Revert change
}
```
- **up()** applies changes.
- **down()** reverses them.

# Posts Table
```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('body');
    $table->foreignId('author_id');
    $table->timestamps();
});
```
`id() creates the primary key`

`timestamps() creates created_at and updated_at`


`$table->foreignIdFor(User::class, 'author_id');`

or:
```php
$table->foreignId('author_id')
    ->constrained('users')
    ->cascadeOnDelete();
```

Run Migration

`php artisan migrate`

# Mass Assignment and $fillable

- Using:

`Post::create($data);`
```php
protected $fillable = [
    'title',
    'body',
    'author_id',
];
```

# Create a Post
```php
$data = $request->validate([
    'title' => ['required'],
    'body' => ['required'],
]);
```

# Foreign Key Constraint

## Without a database constraint:
- author_id = 3
- could be stored even if user 3 does not exist.

## With a constraint:
```txt
author_id = 3
        ↓
users.id = 3 ?
        ↓
No
        ↓
Foreign Key Constraint Violation
```
The course demonstrates rolling back and re-running the migration:

`php artisan migrate:rollback --step=1`
`php artisan migrate`

Then creating a post with a missing user produces a foreign key constrainterror.

# Route Model Binding

Manual approach:
```php
public function show(string $id)
{
    $post = Post::findOrFail($id);

    return $post;
}
```

Implicit Binding
```php
Route::get('/posts/{post}', [PostController::class, 'show']);

--Controller--

public function show(Post $post)
{
    return $post;
}
```
Laravel automatically resolves the Post instance.

* **Update**
```php
public function update(Request $request, Post $post)
{
    $data = $request->validate([
        'title' => ['required'],
        'body' => ['required'],
    ]);

    $post->update($data);

    return $post;
}
```
* **Delete**
```php
public function destroy(Post $post)
{
    $post->delete();

    return response()->noContent();
}
```
# Form Request Classes

- For a small controller, validation can stay inside the controller.

- For scalable APIs, validation can be moved into a Form Request.

Create Request

`php artisan make:request StorePostRequest`

File:
```php
app/Http/Requests/StorePostRequest.php

Request Class

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required'],
            'body' => ['required'],
        ];
    }
}
```

- `authorize()` Determines whether the user is allowed to make the request.
- `rules()` Contains validation rules.

# Use the Form Request

Controller:
```php
public function store(StorePostRequest $request)
{
    $data = $request->validated();

    // ...
}
```


# Custom Validation Messages
```php
public function messages(): array
{
    return [
        'title.required' => 'Title is required.',
    ];
}
```

# Array Validation

Example request:
```php
{
    "title": "Post One",
    "body": "Post Body",
    "tags": [
        "PHP",
        "Laravel"
    ]
}
```
Validation:
```php
public function rules(): array
{
    return [
        'title' => ['required'],
        'body' => ['required'],
        'tags' => ['array'],
        'tags.*' => ['string', 'min:2'],
    ];
}
```
- tags must be an array.

- tags.* applies rules to every array element.

Invalid example:
```php
{
    "tags": [
        "L"
    ]
}
```
- The element fails because it has fewer than two characters.

# API Resources

- An API Resource is a special class used to control what the API returns.
- It is important for scalable and secure APIs.
- It prevents unwanted fields from being exposed.

Example model:
```txt
Post
 ├── id
 ├── title
 ├── body
 ├── author_id
 ├── created_at
 └── updated_at
```

# Create Post Resource

`php artisan make:resource PostResource`

- File:
```php
app/Http/Resources/PostResource.php

Return a Collection

Instead of:

return Post::all();

Use:

return PostResource::collection(
    Post::all()
);

Define Output

public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'title' => $this->title,
        'body' => $this->body,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
    ];
}
```
- `$this` refers to the current Post model.
# Format Dates

`'created_at' = > $this->created_at->format('Y-m-d H:i:s')`
`'updated_at' => $this->updated_at->format('Y-m-d H:i:s')`

- The resource controls the output format.

## Response structure:
```php
{
    "data": [
        {
            "id": 1,
            "title": "Post One",
            "body": "Post Body",
            "created_at": "2025-01-01 12:00:00",
            "updated_at": "2025-01-01 12:00:00"
        }
    ]
}
```
# Post and User Relationships

- Post Belongs To User

- In Post:
```php
public function author(): BelongsTo
{
    return $this->belongsTo(
        User::class,
        'author_id'
    );
}
```


- In User:
```php
public function posts(): HasMany
{
    return $this->hasMany(
        Post::class,
        'author_id'
    );
}
```
```txt
User
  |
  ---- Post
  |
  ---- Post
  |
  ---- Post
```
# Return Author Information

Inside PostResource:
```php
'author' => [
    'id' => $this->author->id,
    'name' => $this->author->name,
],
```
# User Resource

Create a reusable User Resource:

`php artisan make:resource UserResource`

UserResource
```php
public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'name' => $this->name,
        'email' => $this->email,
    ];
}
```
Use It in PostResource

`'author' => new UserResource($this->author),`

Response:
```php
{
    "data": [
        {
            "id": 1,
            "title": "Post One",
            "body": "Post Body",
            "author": {
                "id": 1,
                "name": "Zura",
                "email": "zura@example.com"
            }
        }
    ]
}
```
