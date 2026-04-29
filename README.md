<p align="center"><img width="200" src="https://github.com/robsontenorio/mary-ui.com/blob/main/public/mary.png?raw=true"></p>

<p align="center">
    <!-- Badges for mary-v1-community (update the URLs if/when published on Packagist) -->
    <a href="#">
        <img src="https://img.shields.io/badge/downloads-community--maintained-lightgrey" alt="Community Maintained">
    </a>
    <a href="#">
        <img src="https://img.shields.io/badge/stable-v1-blue" alt="Stable v1">
    </a>
    <a href="/license.md">
        <img src="https://img.shields.io/badge/license-MIT-green" alt="MIT License">
    </a>
</p>


## mary-v1-community

This repository is an independent, community-maintained continuation of the original MaryUI v1 project. It preserves and extends the classic v1 branch after official support was discontinued, ensuring ongoing updates, bug fixes, and compatibility for users who rely on the v1 codebase.

mary-v1-community provides a set of beautiful UI components for Livewire, powered by daisyUI and Tailwind CSS, based on the original MaryUI v1 foundation.


## Documentation

Refer to the [maryUI website](https://mary-ui.com) for the original documentation. Community updates and new documentation will be provided here as the project evolves.




## Contributing

Contributions are welcome! If you want to help maintain or improve mary-v1-community, please open issues or pull requests.

To use this package locally during development, clone this repository and set up a local path repository in your app's `composer.json`:

```json
"repositories": {
        "mary-v1-community": {
                "type": "path",
                "url": "/path/to/mary-v1-community",
                "options": {
                    "symlink": true
                }
        }
}
```

Then require the package:

```bash
composer require williamalmeida/mary-v1-community
```

Start the dev server:

```bash
yarn dev
```


## Importing JS helpers from `vendor` (no CDN required)

The original maryUI docs suggest loading some helpers (e.g. `currency.js`) via CDN from a `<script>` tag in your layout. In `mary-v1-community` you can also import the bundled helpers directly from the package inside your `resources/js/app.js`, so they get processed by Vite and bundled with your app — no extra CDN request needed.

The available helpers live under `vendor/williamalmeida/mary-v1-community/libs/`:

- `color-palette.js` — registers the `$store.maryColorPalette` Alpine store used by the `<x-color-palette>` component.
- `dialog.js` — registers the global Livewire `dialog` listener used by the `Dialog` trait.
- `toast.js` — registers the global Livewire `toast` listener used by the `Toast` trait.
- `currency.js` — provides the global `window.Currency` class used by the `<x-input money>` component.

### Example: `resources/js/app.js`

```js
import './bootstrap';

import Alpine from 'alpinejs';
import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';

// mary-v1-community helpers (bundled, no CDN)
import { registerColorPalette } from '../../vendor/williamalmeida/mary-v1-community/libs/color-palette';
import { registerDialog }       from '../../vendor/williamalmeida/mary-v1-community/libs/dialog';
import { registerToast }        from '../../vendor/williamalmeida/mary-v1-community/libs/toast';
import '../../vendor/williamalmeida/mary-v1-community/libs/currency'; // exposes window.Currency

// Register the Alpine store BEFORE Alpine starts
document.addEventListener('alpine:init', () => {
    registerColorPalette(Alpine);
});

// Register Livewire listeners AFTER Livewire is available on window
registerDialog();
registerToast();

window.Alpine = Alpine;
Alpine.start();
Livewire.start();
```

> Notes
> - `registerDialog()` and `registerToast()` use the global `window.Livewire` (Livewire 3), so call them after Livewire has been loaded.
> - `registerColorPalette(Alpine)` must be called inside the `alpine:init` event so the store is available before Alpine boots.
> - `currency.js` only needs to be imported once — it auto-attaches `window.Currency`.

### Tailwind: scan the package sources

Make sure your `tailwind.config.js` includes the package components so Tailwind picks up the classes used inside them:

```js
module.exports = {
    content: [
        // ...
        './vendor/williamalmeida/mary-v1-community/src/View/Components/**/*.php',
    ],
    // ...
}
```

Tip: se você está desenvolvendo localmente usando um repositório `path` (link simbólico via `composer`), inclua também o caminho para a cópia local do pacote para que o Vite/Tailwind detectem as classes durante o desenvolvimento. Exemplo com ambas as opções:

```js
module.exports = {
    content: [
        // arquivos da sua app
        './resources/js/**/*.js',
        './resources/views/**/*.blade.php',

        // quando o pacote está instalado via Composer
        './vendor/williamalmeida/mary-v1-community/src/View/Components/**/*.php',

        // quando estiver usando o pacote localmente via `repositories.type = path`
        '../mary-v1-community/src/View/Components/**/*.php',
    ],
}
```

Isso garante que o Tailwind processe as classes usadas por `<x-color-palette>` (e outros componentes do pacote) tanto em instalações via `vendor` quanto em desenvolvimento local.

## Color Palette component

`<x-color-palette>` renders a Tailwind color palette picker (all 22 color families × shades 50–950) and stores the selected hex value in your model.

It depends on the `maryColorPalette` Alpine store registered by `libs/color-palette.js` (see the import section above).

```blade
{{-- Livewire model --}}
<x-color-palette
    label="Background color"
    wire:model="background_color"
    clearable
    hint="Click the swatch to open the palette" />

{{-- Plain Alpine model --}}
<div x-data="{ color: '' }">
    <x-color-palette label="Pick a color" x-model="color" />
</div>
```

Available props: `label`, `hint`, `hintClass`, `inline`, `clearable`, plus the standard error props (`errorField`, `errorClass`, `omitError`, `firstErrorOnly`). Standard HTML attributes such as `disabled`, `readonly`, `required` and `placeholder` are also supported.


## License

mary-v1-community is open-sourced software licensed under the [MIT license](/license.md).
