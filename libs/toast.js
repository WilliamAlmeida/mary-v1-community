/**
 * Uses the global `window.Livewire` registered by Livewire 3.
 * This avoids fragile relative imports when this file is consumed
 * directly from `vendor/williamalmeida/mary-v1-community/libs/`.
 */
export function registerToast() {
    if (typeof window === 'undefined' || !window.Livewire) {
        console.warn('[mary] registerToast(): window.Livewire is not available yet. Make sure Livewire is loaded before calling this function.');
        return;
    }

    window.Livewire.on('toast', (event) => {
        const { type, title, description, position, icon, css, timeout } = event;

        // Set default values
        const toast = {
            type: type || 'info',
            title: title || 'Notification',
            description: description || null,
            position: position || 'toast-top',
            icon: icon || null,
            css: css || 'alert-info',
            timeout: timeout || 3000
        };

        // Handle different toast types with appropriate defaults
        if (toast.type === 'success' && !icon) {
            toast.icon = '<svg class="inline w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>';
            toast.css = 'alert-success';
        } else if (toast.type === 'warning' && !icon) {
            toast.icon = '<svg class="inline w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>';
            toast.css = 'alert-warning';
        } else if (toast.type === 'error' && !icon) {
            toast.icon = '<svg class="inline w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>';
            toast.css = 'alert-error';
        } else {
            toast.icon = '<svg class="inline w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>';
        }

        window.toast({toast: toast});
    });
}
