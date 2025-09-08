# Livewire-layout-setup-fonrtend-and-backend


Livewire Dynamic Layout Setup for Laravel
A simple, clean, and powerful layout management system for Laravel & Livewire projects. This setup allows you to dynamically switch between multiple layouts (e.g., frontend and backend) based on the current route, using a single, centralized layout file.

The Core Idea 💡
In many Laravel projects, we need separate layouts for the public-facing frontend and the private admin panel (backend). The traditional approach often involves creating multiple base Livewire components or repeatedly defining layouts using #[Layout('...')] attributes.

This project introduces a more elegant solution: a single root layout component that conditionally renders the correct layout shell.

This approach keeps your individual Livewire components clean and free from layout-defining logic.

How It Works
The magic happens in a single Blade component file, which acts as the main layout entry point for all Livewire components. This file checks the current route's name and decides whether to load the frontend or backend layout.

Here is the core logic, typically placed in resources/views/components/layouts/app.blade.php:

@if (request()->routeIs('backend.*'))
    {{-- If the route name starts with 'backend.', load the backend layout --}}
    <x-layouts.app.backend :title="$title ?? null">
        <main>
            {{ $slot }}
        </main>
    </x-layouts.app.backend>
@else
    {{-- Otherwise, load the default frontend layout --}}
    <x-layouts.app.frontend :title="$title ?? null">
        <main>
            {{ $slot }}
        </main>
    </x-layouts.app.frontend>
@endif


or


@php
    // Determine the layout type based on the route name
    $layout = request()->routeIs('backend.*') ? 'backend' : 'frontend';
@endphp

{{-- Dynamically render the correct layout component --}}
<x-dynamic-component :component="'layouts.app.' . $layout" :title="$title ?? null">
    <main>
        {{ $slot }}
    </main>
</x-dynamic-component>





Required File Structure
For the above code to work, your layout files must be organized correctly inside the resources/views/components/ directory.

/resources
└── /views
    └── /components
        └── /layouts
            ├── app.blade.php       // <-- The main layout file with the conditional logic
            └── /app
                ├── backend.blade.php   // <-- Your admin panel layout shell
                └── frontend.blade.php  // <-- Your public website layout shell

How to Use This System
Integrating this system into your Laravel + Livewire project is straightforward.

Step 1: Create the Layout Shells
Create the frontend.blade.php and backend.blade.php files inside resources/views/components/layouts/app/. These files will contain your HTML boilerplate, head tags, scripts, and styles for each respective section.

Example: frontend.blade.php

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'My Website' }}</title>
    {{-- Frontend CSS/JS --}}
</head>
<body>
    {{-- Frontend Navigation --}}
    {{ $slot }}
    {{-- Frontend Footer --}}
</body>
</html>

Step 2: Create the Main Conditional Layout
Create the main app.blade.php file inside resources/views/components/layouts/ and paste the conditional logic from the "How It Works" section above.

Step 3: Configure Your Livewire Components
To make all your Livewire components use this dynamic layout system, the best approach is to create a base component that points to the main layout file.

Create a Base Component app/Livewire/AppComponent.php

<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')] // <-- All components will use this layout
abstract class AppComponent extends Component
{
    // You can add shared properties or methods here
}

Now, simply extend this AppComponent in all your page components. They will automatically use the dynamic layout system.

Example: app/Livewire/HomePage.php

<?php

namespace App\Livewire;

class HomePage extends AppComponent
{
    public function render()
    {
        return view('livewire.home-page');
    }
}

Step 4: Define Your Routes
Finally, make sure your route names follow a consistent pattern. For all backend routes, prefix the name with backend..

Example: routes/web.php

use App\Livewire\HomePage;
use App\Livewire\Admin\Dashboard;

// Frontend Routes
Route::get('/', HomePage::class)->name('home');

// Backend Routes (with name prefix)
Route::middleware(['auth'])->prefix('admin')->name('backend.')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    // ... other backend routes
});

Benefits of This Approach
DRY (Don't Repeat Yourself): The layout logic is defined in only one place.

Clean Components: Your Livewire components are not cluttered with layout definitions.

Centralized Control: Easily manage or change layouts from a single file.

Scalable: Simple to add more layout conditions (e.g., for a member.* section).

Feel free to contribute to this idea or suggest improvements!
