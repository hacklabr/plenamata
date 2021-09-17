# WP Plugin

## Get Started

One-line install:
```
composer create-project wppunk/wpplugin your-plugin-directory
```

Or you can copy the archive or clone via Git and then run:
```
composer init-project
```

## Requirements

Make sure all dependencies have been installed before moving on:

- WordPress
- PHP >= 7.2.5 (You can easily downgrade it)
    - DOM extension
    - CURL extension
- Composer
- Node.js >= 14.8
- npm

## Structure

```
plugins/your-awesome-plugin/        # → Root of your plugin.
├── .github/                        # → GitHub additional directories.
│   └── workflows/                  # → Workflows.
│       ├── plenamata-plugin.conf        # → Config for the server.
│       └── plenamata-plugin.yml         # → Actions for GitHub.
├── assets/                         # → Assets directory.
│   ├── build/                      # → Assets build directory.
│   └── src/                        # → Assets source directory.
├── node_modules/                   # → JS packages (never edit).
├── src/                            # → PHP directory.
├── templates/                      # → Templates for plugin views.
├── vendor/                         # → Composer packages (never edit).
├── vendor_prefixes/                # → Prefixed composer packages for non-conflict mode (never edit).
├── .eslintignore                   # → JS Coding Standards ignore file.
├── .eslintrc.js                    # → JS Coding Standards config.
├── .gitconfig                      # → Config for git.
├── .gitignore                      # → Git ignore file.
├── .phpcs.xml                      # → Custom PHP Coding Standards.
├── .stylelintrc                    # → Config for the style linter.
├── CHANGELOG.md                    # → Changelog file for GH.
├── composer.json                   # → Composer dependencies and scripts.
├── composer.lock                   # → Composer lock file (never edit).
├── LICENSE                         # → License file.
├── package.json                    # → JS dependencies and scripts.
├── package-lock.json               # → Package lock file (never edit).
├── plenamata-plugin.php            # → Bootstrap plugin file.
├── webpack.mix.js                  # → Laravel Mix configuration file.
├── README.md                       # → Readme MD for GitHub repository.
├── readme.txt                      # → Readme TXT for the wp.org repository.
└── uninstall.php                   # → Uninstall file.
```

## Autoload

We use PSR-4 and composer autoload for PSR-4. You can find it in `composer.json` in directive `autoload`.

## Coding Standards

To check all coding standards:
```
npm run cs
```

### PHP Coding Standard (PHPCS)

We use a custom coding standard based on [WordPress Coding Standard](https://github.com/WordPress/WordPress-Coding-Standards). We disabled rules for the naming of WordPress files for using PSR-4 autoload. Also, we have a [feature](https://github.com/PHPCompatibility/PHPCompatibilityWP), which can allow testing your code using different PHP environments.

Custom PHPCS your can find in the `.phpcs.xml`.

Your can check PHPCS using a CLI:
```
composer cs
```
or
```
npm run cs:php
```

PHPCS checked before each commit, before the push, and in GH Actions.

### JS Coding Standard (JSCS)

We use a default WordPress JSCS, but you can modify it in the `.eslintrc` file.

You can check JSCS using a CLI:

```
npm run cs:js
```

### SCSS Coding Standard (SCSSCS)

We use a default standards for SCSS, but you can modify it in the `.stylelintrc` file.

You can check SCSSCS using a CLI:

```
npm run cs:scss
```

## Frontend

All assets are located in `assets/src/*`.

All builds are located in `assets/build/*`.

CSS preprocessor is SCSS.

We use [Laravel Mix](https://laravel-mix.com/) for the assets build. You can modify it in `webpack.mix.js` file.

For run Laravel mix you can use the next commands depend on situation:
```
npm run build
npm run build:production
npm run start
```

## GitHub

### GH Actions
All steps for GH Actions you can find in `.github/workflows/plenamata-plugin.yml` file. Also, for wake up a webserver, we need to add `.github/workflows/plenamata-plugin.conf`

### GH Hooks

Just make you GH repository clear. We use the [Husky](https://github.com/typicode/husky) library to add actions before commit, push, etc. This helps developers make their GH more clear.

### GH Templates

Basic GH templates for better security issues, support requests, bug reports, enhancements, feature requests, pull requests, and contributing templates.
