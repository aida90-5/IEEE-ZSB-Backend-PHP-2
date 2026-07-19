# Browser Testing Registration Forms

## Key Concepts
- Browser testing using **Pest**.
- Simulating real user interactions (visit pages, fill forms, click buttons).
- Testing the complete registration workflow.
- Verifying redirects and authentication after registration.
- Ensuring validation errors are displayed correctly.

## Code Walkthrough
1. Create a browser test for the registration page.
2. Visit the `/register` route.
3. Fill in the registration form with valid data.
4. Submit the form.
5. Assert the user is redirected and authenticated.
6. Add tests for invalid input and duplicate email addresses.

## Testing
-  Registration page loads successfully.
-  User can register with valid information.
-  User is logged in after registration.
-  Validation errors appear for invalid input.
-  Duplicate emails are rejected.

## Common falls
- Forgetting to refresh the database between tests.
- Hardcoding test data that causes conflicts.
- Only testing successful cases.
- Mixing browser tests with unit test logic.

```bash
php artisan test
php artisan make:test RegistrationTest
php artisan migrate:fresh --seed
```
```php
uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests can register with valid data', function () {
    $this->post('/register', [
        'name' => 'name ',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])
    ->assertRedirect('/home');

    $this->assertAuthenticated();
    
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'name' => 'name  ',
    ]);
});

test('registration rejects duplicate emails', function () {
    \App\Models\User::factory()->create(['email' => 'test@example.com']);

    $this->post('/register', [
        'name' => 'name  ',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])
    ->assertSessionHasErrors(['email']);
});

test('registration requires a name, email, and password', function () {
    $this->post('/register', [
        'name' => '',
        'email' => '',
        'password' => '',
    ])
    ->assertSessionHasErrors(['name', 'email', 'password']);
});

test('registration requires password confirmation to match', function () {
    $this->post('/register', [
        'name' => ' name ',
        'email' => 'newuser@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'different123',
    ])
    ->assertSessionHasErrors(['password']);
});
```
---

# Flash Messaging and Interactivity with Alpine.js

## Key Concepts
- Laravel session flash messages.
- Displaying temporary success/error messages.
- Basic Alpine.js reactivity.
- Showing and hiding UI elements.
- Auto-dismissing notifications.

## Code Walkthrough
1. Store a flash message using the session.
2. Display the message in a Blade component.
3. Use Alpine.js to control visibility.
4. Automatically hide the notification after a delay.
5. Reuse the flash component across pages.

## Testing
-  Flash message appears after successful action.
-  Flash message disappears after page refresh.
-  Alpine.js correctly toggles visibility.
-  Notification is shown only when a session message exists.

## Common falls
- Forgetting to check if a session message exists.
- Embedding large JavaScript logic in Blade.
- Using flash messages for permanent notifications.

```bash
php artisan test
```
```php
<!-- Inside resources/views/components/flash.blade.php -->
@if (session()->has('success'))
    <div 
        x-data="{ show: true }" 
        x-init="setTimeout(() => show = false, 3000)" 
        x-show="show"
        x-cloak
        class="fixed bottom-4 right-4 bg-green-500 text-white p-4 roundedshadow"
    >
        {{ session('success') }}
    </div>
@endif
  
```

---

# Idea Cards

## Key Concepts
- Blade components.
- Reusable UI.
- Passing data to components.
- Displaying Eloquent models.
- Separating presentation from business logic.

## Code Walkthrough
1. Create an Idea Card component.
2. Pass an `Idea` model to the component.
3. Display title, author, and metadata.
4. Loop through ideas using Blade.
5. Replace duplicated HTML with reusable components.

## Testing
-  Idea card renders correctly.
-  Dynamic data is displayed.
-  Multiple ideas render without errors.
-  Empty collections are handled gracefully.

## Common falls
- Duplicating HTML.
- Performing business logic inside views.
- Passing unnecessary data to components.


```bash
php artisan make:component IdeaCard
```

---

# Idea Filtering

## Key Concepts
- Filtering Eloquent queries.
- Query Builder.
- Request query parameters.
- Dynamic filtering.
- Preserving filter state.

## Code Walkthrough
1. Read filter values from the request.
2. Apply conditional query constraints.
3. Return filtered ideas.
4. Update the UI to reflect active filters.

## Testing
- Filter by category.
-  Filter by status.
-  Invalid filters don't break the application.
-  Filters return expected records.

## Common falls
- Applying filters after retrieving all records.
- Forgetting to sanitize query parameters.
- Duplicating filtering logic.

```bash
php artisan test
```
```php
// Inside app/Models/Idea.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Idea extends Model
{
    /**
     * Scope a query to filter ideas by dynamic request variables.
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when($filters['category'] ?? null, function ($query, $category) {
            $query->where('category_id', $category);
        })->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        });
    }
}
```
```html
<!-- Inside resources/views/ideas/index.blade.php -->
<div class="space-y-4">
    @forelse ($ideas as $idea)
        <!-- Reusable Blade Component passing the explicit model instance -->
        <x-idea-card :idea="$idea" />
    @empty
        <div class="text-center py-12 text-gray-500">
            No ideas found matching those active filters.
        </div>
    @endforelse
</div>
```
---

# Show A Single Idea

## Key Concepts
- Route Model Binding.
- Dynamic routes.
- Displaying individual resources.
- Eloquent relationships.

## Code Walkthrough
1. Define a route with model binding.
2. Fetch the requested Idea.
3. Display details in a dedicated view.
4. Show related information (author, links, etc.).

## Testing
- Existing idea loads successfully.
-  Non-existing idea returns 404.
-  Correct information is displayed.
-  Related data is visible.

## Common falls
- Manually querying models unnecessarily.
- Forgetting to handle missing records.
- N+1 query problems.

## Commands
```bash
php artisan route:list
```
```php
// Inside routes/web.php
use App\Models\Idea;
use Illuminate\Support\Facades\Route;

Route::get('/ideas/{idea}', function (Idea $idea) {
    // Eager load relationships immediately before passing the model down
    $idea->load(['user', 'category', 'links']);

    return view('ideas.show', [
        'idea' => $idea,
    ]);
});
```

---

# Create A Functional Modal With Alpine.js

## Key Concepts
- Alpine.js state management.
- Modal components.
- Event handling.
- Conditional rendering.

## Code Walkthrough
1. Create modal markup.
2. Initialize Alpine state.
3. Open modal on button click.
4. Close modal using buttons and outside clicks.
5. Improve accessibility.

## Testing
-  Modal opens.
-  Modal closes.
-  Clicking outside closes the modal.
-  Form remains functional inside the modal.

## Common falls
- Forgetting keyboard accessibility.
- Leaving hidden elements interactive.
- Overcomplicating Alpine logic.

```bash
php artisan test
```
```html
<!-- Inside resources/views/components/modal.blade.php -->
<div 
    x-data="{ isOpen: false }" 
    @open-modal.window="isOpen = true"
    @keydown.escape.window="isOpen = false"
    x-cloak
    class="relative z-50"
>
    <!-- Simple component button trigger -->
    <button @click="isOpen = true" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
        Open Form
    </button>

    <!-- Modal Layout Background Overlay -->
    <div 
        x-show="isOpen" 
        x-transition.opacity 
        class="fixed inset-0 bg-gray-500/75 transition-opacity" 
        @click="isOpen = false"
    ></div>

    <!-- Container Wrap -->
    <div x-show="isOpen" class="fixed inset-0 z-10 overflow-y-auto flex items-center justify-center p-4">
        <div 
            class="relative transform overflow-hidden rounded-lg bg-white p-6 text-left shadow-xl transition-all sm:w-full sm:max-w-lg"
            @click.stop
        >
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-lg font-medium text-gray-900">Submit New Concept</h3>
                <button @click="isOpen = false" class="text-gray-400 hover:text-gray-500 text-2xl">&times;</button>
            </div>

            <!-- Dynamic Form Wrapper Slot Content -->
            {{ $slot }}
        </div>
    </div>
</div>
```
---

# Construct The Idea Form

## Key Concepts
- Blade forms.
- Form components.
- CSRF protection.
- Form validation.
- Old input handling.

## Code Walkthrough
1. Build the form.
2. Add title and description fields.
3. Include the CSRF token.
4. Display validation errors.
5. Preserve user input after validation failures.

## Testing
-  Form renders correctly.
-  Required fields exist.
-  Validation errors display.
-  Old input is preserved.

## Common falls
- Missing `@csrf`.
- Forgetting old input values.
- Poor validation feedback.

```bash
php artisan make:request StoreIdeaRequest
```
```html
<!-- Inside resources/views/components/idea-form.blade.php -->
<form action="/ideas" method="POST" class="space-y-6 bg-white p-4 rounded-lg">
    @csrf

    <div>
        <label for="title" class="block text-sm font-medium text-gray-700">Idea Title</label>
        <input 
            type="text" 
            name="title" 
            id="title" 
            value="{{ old('title') }}" 
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="A short descriptive heading"
        >
        @error('title')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Detailed Description</label>
        <textarea 
            name="description" 
            id="description" 
            rows="4" 
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="Explain your concept cleanly..."
        >{{ old('description') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex justify-end pt-2">
        <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded shadow hover:bg-emerald-700">
            Save Progress
        </button>
    </div>
</form>
```
---

# Test The Create Idea Form

## Key Concepts
- Feature testing.
- Database assertions.
- Validation testing.
- Authenticated user actions.

## Code Walkthrough
1. Write a feature test.
2. Submit valid form data.
3. Assert the database contains the new idea.
4. Verify redirects.
5. Test validation failures.

## Testing
-  Authenticated users can create ideas.
-  Guests cannot create ideas.
-  Validation works.
-  Database contains the new idea.

## Common falls
- Forgetting authentication.
- Weak validation coverage.
- Reusing mutable test data.

```bash
php artisan make:test CreateIdeaTest
php artisan test
```
```php
// Inside tests/Feature/CreateIdeaTest.php
uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('authenticated users can create ideas', function () {
    $user = \App\Models\User::factory()->create();

    $this->actingAs($user)
        ->post('/ideas', [
            'title' => 'My New Idea',
            'description' => 'Detailed description of the concept.',
        ])
        ->assertRedirect('/');

    $this->assertDatabaseHas('ideas', [
        'title' => 'My New Idea',
    ]);
});

test('guests cannot create ideas', function () {
    $this->post('/ideas', [
        'title' => 'An Idea',
        'description' => 'Some description.',
    ])
    ->assertRedirect('/login');
});
```
---

# Allow For One Or Many Links

## Key Concepts
- One-to-many relationships.
- Dynamic form fields.
- Managing multiple related records.
- Saving associated models.
- Data normalization.

## Code Walkthrough
1. Update the form to accept multiple links.
2. Validate each submitted link.
3. Save related links using Eloquent relationships.
4. Display links with each idea.
5. Handle optional or empty links gracefully.

## Testing
- Create an idea with one link.
-  Create an idea with multiple links.
-  Ideas without links are supported.
-  Invalid URLs fail validation.
-  Links are correctly associated with the idea.

## Common falls
- Saving links before the parent model exists.
- Not validating URLs.
- Creating duplicate links.
- Forgetting to cascade deletes.


```bash
php artisan make:model Link -m
php artisan migrate
php artisan test
```
```php
// Inside app/Http/Requests/StoreIdeaRequest.php
public function rules(): array
{
    return [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'links' => 'nullable|array',
        'links.*.url' => 'required|url', // Validates each nested link input
    ];
}
```