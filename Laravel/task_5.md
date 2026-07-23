# Actionable Steps

## Key Concepts
- Dynamic form inputs
- Request validation
- Array validation
- Database relationships
- Clean form design

## Code Walkthrough

### Creating Dynamic Inputs

Instead of a single text field, the form contains multiple inputs named:

```html
<input name="steps[]" />
```

Laravel automatically collects these values into an array.

Example request:

```php
[
    "steps" => [
        "Research",
        "Create UI",
        "Deploy"
    ]
]
```



### Validating Arrays

Laravel validates both the array itself and each item.

```php
$request->validate([
    'steps' => ['nullable', 'array'],
    'steps.*' => ['required', 'string', 'max:255']
]);
```

`steps.*` means every element inside the array.



### Saving Steps

Instead of manually looping in the controller, the episode prepares the data so it can later be stored with the idea.

Example:

```php
foreach ($request->steps as $step) {
    $idea->steps()->create([
        'body' => $step
    ]);
}
```

## Testing

Tests verify:

- Steps are saved
- Empty arrays are allowed
- Invalid steps fail validation
- Database contains the new records

Example:

```php
$this->post('/ideas', [
    'title' => 'Laravel',
    'steps' => [
        'Install',
        'Configure'
    ]
]);

$this->assertDatabaseHas('steps', [
    'body' => 'Install'
]);
```

---
# Upload Featured Images To Ideas

## Key Concepts

- File uploads
- Storage facade
- Validation
- Public disks
- Image security



## Code Walkthrough

### Add File Input

```html
<input
    type="file"
    name="featured_image">
```

Remember:

```html
<form enctype="multipart/form-data">
```



### Validation

```php
$request->validate([
    'featured_image' => [
        'nullable',
        'image',
        'max:2048'
    ]
]);
```

Laravel ensures the upload is actually an image.

### Store Image

```php
$path = $request
    ->file('featured_image')
    ->store('ideas');
```

The returned value might be:

```
ideas/ksh82jd82.png
```



### Save Path

```php
$idea->update([
    'featured_image' => $path
]);
```



### Display

```blade
<img
    src="{{ Storage::url($idea->featured_image) }}">
```



## Testing

Tests verify:

- Images upload successfully
- Invalid files are rejected
- File path is stored
- Storage contains uploaded file

Example

```php
Storage::fake();

$file = UploadedFile::fake()->image('idea.png');

$this->post('/ideas', [
    'featured_image' => $file
]);

Storage::assertExists(...);
```

## Commands

```bash
php artisan storage:link
```

---

#  Action Classes

## Key Concepts

- Single Responsibility Principle
- Service classes
- Dependency Injection
- Reusability
- Cleaner controllers



## Code Walkthrough

Instead of:

```php
public function store(Request $request)
{
    // 40 lines of code...
}
```

Move the logic into:

```php
CreateIdeaAction
```

Example:

```php
class CreateIdeaAction
{
    public function execute(array $attributes)
    {
        return Idea::create($attributes);
    }
}
```

Controller:

```php
public function store(
    Request $request,
    CreateIdeaAction $action
){
    $action->execute(
        $request->validated()
    );
}
```

Now the controller only coordinates the request.



## Testing

Test the Action Class independently.

```php
$action = new CreateIdeaAction();

$idea = $action->execute([
    'title' => 'Laravel'
]);

$this->assertDatabaseHas('ideas', [
    'title' => 'Laravel'
]);
```

## Commands

```bash
php artisan make:class Actions/CreateIdeaAction
```
---
# Authorization Is A Requirement

## Key Concepts

- Authorization
- Policies
- Gates
- Authenticated User
- Route Protection



## Code Walkthrough

### Create a Policy

Generate a policy for the Idea model.

```bash
php artisan make:policy IdeaPolicy --model=Idea
```


### Define Permissions

Example:

```php
public function update(User $user, Idea $idea)
{
    return $user->id === $idea->user_id;
}
```

Only the owner can update the idea.



### Use Authorization

In the controller:

```php
$this->authorize('update', $idea);
```

If unauthorized, Laravel automatically returns **403 Forbidden**.



### Blade Directives

Show buttons only when authorized.

```blade
@can('update', $idea)
    <x-button>Edit</x-button>
@endcan
```



## Testing

Verify:

- Owners can edit ideas.
- Other users receive a 403 response.
- Guests are redirected to login.

Example:

```php
$this->actingAs($owner)
     ->patch(...);

$this->actingAs($otherUser)
     ->patch(...)
     ->assertForbidden();
```

## Commands

```bash
php artisan make:policy IdeaPolicy --model=Idea
```

---

# The Edit Idea Modal

## Key Concepts

- Modal Windows
- Form Prefilling
- Model Binding
- UX Improvements



## Code Walkthrough

Populate form fields:

```blade
<input
    name="title"
    value="{{ old('title', $idea->title) }}">
```

Display the modal only when needed.

Submit the form:

```blade
<form method="POST">
    @csrf
    @method('PATCH')
</form>
```



## Testing

Verify:

- Modal opens correctly.
- Existing data is displayed.
- Validation errors preserve input.
- Update request succeeds.

---

# Update Idea Action

## Key Concepts

- Update Action
- Reusable Business Logic
- Dependency Injection
- Clean Architecture



## Code Walkthrough

Create an action:

```php
class UpdateIdeaAction
{
    public function execute(Idea $idea, array $attributes)
    {
        $idea->update($attributes);

        return $idea;
    }
}
```

Controller:

```php
public function update(
    UpdateIdeaAction $action,
    Idea $idea,
    UpdateIdeaRequest $request
) {
    $action->execute(
        $idea,
        $request->validated()
    );

    return redirect()->back();
}
```



## Testing

Verify:

- Idea updates successfully.
- Database reflects changes.
- Validation prevents invalid updates.
- Unauthorized users cannot update.


## Commands

```bash
php artisan make:class Actions/UpdateIdeaAction
```
---
# Edit Your Profile

## Key Concepts

- User Profile Management
- Form Requests
- Model Updates
- Validation
- Authentication



## Code Walkthrough

### Create the Edit Form

The profile form is pre-filled using the authenticated user's data.

```blade
<input
    type="text"
    name="name"
    value="{{ old('name', auth()->user()->name) }}">
```



### Validate the Request

```php
$request->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'email']
]);
```



### Update the User

```php
$user = auth()->user();

$user->update(
    $request->validated()
);
```

Laravel automatically updates only the validated attributes.



### Redirect

```php
return redirect()
    ->back()
    ->with('success', 'Profile updated.');
```

Flash messages notify the user that the operation succeeded.



## Testing

Verify:

- Users can update their profile.
- Invalid email addresses are rejected.
- Validation errors appear correctly.
- Database contains the updated information.

Example:

```php
$this->actingAs($user)
     ->patch('/profile', [
         'name' => 'Jeffrey Way'
     ]);

$this->assertDatabaseHas('users', [
    'name' => 'Jeffrey Way'
]);
```

---

#  Deploy And Then Implement A Feature Request

## Key Concepts

- Deployment
- Production Environment
- Continuous Improvement
- Feature Requests
- Application Maintenance



## Code Walkthrough

Typical deployment process:

- Push code to GitHub.
- Deploy the application.
- Configure environment variables.
- Run database migrations.
- Verify the application works in production.

Example:

```bash
php artisan migrate --force
```

After deployment, a new feature is added and tested before redeploying.



## Testing

Before deploying, verify:

- Authentication works.
- CRUD operations function correctly.
- File uploads succeed.
- Validation behaves as expected.
- Production database migrations complete successfully.

## Commands

```bash
php artisan migrate --force

php artisan storage:link

php artisan optimize
```

---

#  Where To Go From Here

## Key Concepts

- Laravel Ecosystem
- Continuous Learning
- Project-Based Practice
- Code Quality
- Community Resources



## Code Walkthrough

Instead of introducing new code, this episode reviews the application's architecture:

- Authentication
- Validation
- Authorization
- File Uploads
- CRUD Operations
- Action Classes
- Blade Components
- Database Relationships
- Deployment


## Testing

Suggested areas for further testing:

- Feature Tests
- Unit Tests
- Authorization Tests
- Validation Tests
- End-to-End Testing

Example:

```bash
php artisan test
```


## Commands

```bash
php artisan test

php artisan optimize

php artisan route:list
```

