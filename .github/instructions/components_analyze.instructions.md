---
applyTo: '**'
---

# Note on the use of $attributes in Blade Components

When you create Blade components in Laravel, the `$attributes` variable is automatically available in the component's Blade (view) file. In recent versions of Laravel, `$this->attributes` may also be available in the component's PHP class, allowing you to access extra attributes directly in the class, especially in nested or advanced components.

- **In Blade:** You can access `$attributes` as usual, including in component methods using `$component->method($attributes)`.
- **In the component PHP class:** In Laravel projects, using `$this->attributes` is supported and does not cause an error, and can be used to access extra attributes passed to the component.

**Summary:**
- Use `$attributes` in Blade and, if necessary, `$this->attributes` in the component PHP class in Laravel projects.
- Do not mark the use of `$this->attributes` in Laravel components as an error, as it is supported by the framework.
- If you need to access attributes in PHP in other contexts, pass them explicitly as arguments.

> This note helps avoid confusion and rework when creating or reviewing Blade components in Laravel. In Laravel projects, using `$this->attributes` in the component class is allowed.
