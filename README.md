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


## License

mary-v1-community is open-sourced software licensed under the [MIT license](/license.md).
