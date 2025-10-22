# MaryUI v1 Community - AI Coding Assistant Instructions

## Project Overview
MaryUI v1 Community is a Laravel package providing Livewire UI components built on daisyUI and Tailwind CSS. This is a community-maintained fork of the original MaryUI v1, preserving the classic architecture for users who depend on it.

**Tech Stack**: Laravel 10+/11+/12+, Livewire 3, Blade Components, daisyUI 4, Tailwind CSS 3, Alpine.js

## Architecture Patterns

### Component Structure
All UI components follow a consistent inline rendering pattern:
- **Location**: `src/View/Components/`
- **Pattern**: Each component extends `Illuminate\View\Component` and renders Blade templates inline using heredoc syntax
- **No separate view files**: Components use `return <<<'HTML' ... HTML;` in their `render()` method
- **UUID generation**: Most components generate a unique ID via `md5(serialize($this))` for wire:key and element IDs

**Example Component Pattern:**
```php
class Button extends Component {
    public string $uuid;
    
    public function __construct(public ?string $label = null) {
        $this->uuid = "mary" . md5(serialize($this));
    }
    
    public function render(): View|Closure|string {
        return <<<'HTML'
            <button wire:key="{{ $uuid }}" {{ $attributes->class(['btn']) }}>
                {{ $label ?? $slot }}
            </button>
        HTML;
    }
}
```

### Service Provider Pattern
`MaryServiceProvider` handles:
1. **Component Registration**: All components are registered twice - once with configurable prefix (`config('mary.prefix')`) and once with `mary-*` prefix for internal use
2. **Blade Directive**: Custom `@scope/@endscope` directive for scoped slots (replaces dots with `___` for slot names like `user.city` → `user___city`)
3. **Route Registration**: Internal routes for Spotlight search, file uploads, and sidebar state
4. **BladeUI Icons Override**: Renames `<x-icon>` to `<x-svg>` to prevent collision with MaryUI's `<x-icon>`

### Configuration
`config/mary.php` supports:
- `prefix`: Component prefix (default: empty string → `<x-button>`, or `mary-` → `<x-mary-button>`)
- `route_prefix`: Prefix for internal routes (Spotlight, Editor uploads)
- `components.spotlight.class`: Custom Spotlight search handler class

### Traits Pattern
Livewire components can use traits for common functionality:
- **`Toast`**: `$this->success()`, `$this->error()`, `$this->warning()`, `$this->info()` - Display toast notifications via Livewire's `$this->js()` method
- **`Dialog`**: `$this->dialog()`, `$this->confirm()`, `$this->warning()`, `$this->error()` - Show confirmation dialogs
- **`WithMediaSync`**: Handle media library synchronization

**Usage in Livewire components:**
```php
use Mary\Traits\Toast;

class MyComponent extends Component {
    use Toast;
    
    public function save() {
        // ... save logic
        $this->success('Record saved!', redirectTo: '/dashboard');
    }
}
```

## Development Workflows

### Local Development Setup
1. **Local Package Development**: Use Composer path repository in consumer app:
   ```json
   "repositories": {
       "mary-v1-community": {
           "type": "path",
           "url": "/path/to/mary-v1-community",
           "options": { "symlink": true }
       }
   }
   ```
2. **Require package**: `composer require williamalmeida/mary-v1-community`
3. **Asset building**: `yarn dev` or `npm run dev`

### Installation Command
`php artisan mary:install` handles:
- Livewire/Volt installation (prompts user)
- daisyUI + Tailwind setup
- Stub file publishing (app.blade.php, tailwind.config.js, etc.)
- Component renaming (detects Jetstream/Breeze conflicts)
- View cache clearing

### Testing
- **Framework**: PHPUnit 10+/11+
- **Test location**: `tests/` directory (though not currently populated in workspace)
- **Run tests**: `vendor/bin/phpunit`

## Critical Conventions

### Component Attribute Handling
Components extensively use `$attributes` for flexible class and wire directive binding:
```php
{{ $attributes->whereDoesntStartWith('class') }}  // Non-class attributes
{{ $attributes->class(['btn', 'btn-primary' => $primary]) }}  // Conditional classes
{{ $attributes->whereStartsWith('wire:model')->first() }}  // Get wire:model value
```

### Error Handling Pattern
Form components follow consistent error display:
- `errorField`: Override which model field to check for errors
- `omitError`: Skip error display
- `firstErrorOnly`: Only show first error message
- `errorClass`: Customize error styling (default: `text-red-500 label-text-alt p-1`)

### Scoped Slots Usage
The custom `@scope/@endscope` directive enables slot parameters:
```blade
{{-- In table component --}}
@scope('actions', $user)
    <x-button wire:click="edit({{ $user->id }})">Edit</x-button>
@endscope
```

### Internal Route Configuration
Routes use `config('mary.route_prefix')` for customization:
- Spotlight: `/mary/spotlight`
- Editor upload: `/mary/upload` (requires auth middleware)
- Sidebar toggle: `/mary/toogle-sidebar` (note: typo is preserved for compatibility)

## Component-Specific Patterns

### Table Component
- Supports sorting, pagination, expandable rows, selectable rows
- Row/cell decoration via closures: `rowDecoration` and `cellDecoration` arrays
- Custom formatting: `'format' => ['date', 'Y-m-d']` or `'format' => ['currency', '20', '$']`
- Link generation: `link` prop with `disableLink` override per header

### Form Components (Input, Select, Textarea, etc.)
- **Inline labels**: Use `inline` prop for floating label effect
- **Prepend/append slots**: Wrap input with decorative elements
- **Icon support**: `icon` (left), `iconRight` (right)
- **Hints**: `hint` prop with customizable `hintClass`

### Button Component
- **Spinner integration**: `spinner` prop auto-shows loading state on wire:click target
- **Tooltips**: `tooltip`, `tooltipLeft`, `tooltipRight`, `tooltipBottom`
- **Responsive labels**: `responsive` prop hides label on mobile
- **Links**: Use `link` prop for anchor tag, `external` for target="_blank", `noWireNavigate` to disable wire:navigate

## Important Notes

### $attributes in Component Classes
Per project instructions (`components_analyze.instructions.md`), **`$this->attributes` is supported in Laravel component PHP classes**. Don't flag this as an error when reviewing components.

### View Cache Management
Always run `php artisan view:clear` after:
- Changing `config('mary.prefix')`
- Modifying component class names
- Updating Blade directives

### Livewire 3 Integration
Components assume Livewire 3 patterns:
- `wire:navigate` for SPA-like navigation
- `wire:loading` for loading states
- `$this->js()` for JavaScript dispatch
- Volt support via optional installation

## Common Tasks

### Adding a New Component
1. Create class in `src/View/Components/`
2. Use inline Blade rendering with heredoc syntax
3. Generate UUID in constructor if needed
4. Register in `MaryServiceProvider::registerComponents()` with both standard and `mary-*` prefix
5. Follow existing patterns for attributes, errors, icons, slots

### Modifying Existing Components
1. Check for internal usage (many components use `<x-mary-*>` prefix internally)
2. Preserve `$attributes` handling patterns
3. Update serialization exclusions if adding closures
4. Test with `wire:model` and Livewire directives

### Working with Stubs
Stubs in `stubs/` are published during `mary:install`:
- `welcome.blade.php`, `index.blade.php`: Demo pages
- `app.blade.php`: Layout template
- `tailwind.config.js`, `postcss.config.js`: Build configuration
- `bootcamp/`: Database seeders and models for demo data
