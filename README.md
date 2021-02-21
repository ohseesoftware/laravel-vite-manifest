# Laravel Vite Manifest

[![Current Release](https://img.shields.io/github/release/ohseesoftware/laravel-vite-manifest.svg?style=flat-square)](https://github.com/ohseesoftware/laravel-vite-manifest/releases)
![Build Status Badge](https://github.com/ohseesoftware/laravel-vite-manifest/workflows/Build/badge.svg)
[![Downloads](https://img.shields.io/packagist/dt/ohseesoftware/laravel-vite-manifest.svg?style=flat-square)](https://packagist.org/packages/ohseesoftware/laravel-vite-manifest)
[![MIT License](https://img.shields.io/github/license/ohseesoftware/laravel-vite-manifest.svg?style=flat-square)](https://github.com/ohseesoftware/laravel-vite-manifest/blob/master/LICENSE)

## Overview

The Laravel Vite Manifest package adds a Blade directive to include Vite's JS and CSS output files, pulled from the generated manifest file. The main logic of this package was sourced from https://github.com/andrefelipe/vite-php-setup.

## Usage

```
composer require ohseesoftware/laravel-vite-manifest
```

Add the `@vite` directive where you want to include the JS and CSS files:

```
// app.blade.php

@vite
```

By default, the directive will attempt to include the `js/app.js` file. However, if you have a different entrypoint file, you can pass that into the directive:

```
// app.blade.php

@vite('js/main.js')
```

It is recommended you include your source `.css` files from within your source `js/app.js` file. This will allow Vite to include it as a dependency.


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email security@ohseesoftware.com instead of using the issue tracker.

## Credits

-   [Owen Conti](https://github.com/ohseesoftware)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
