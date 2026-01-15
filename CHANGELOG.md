# Changelog

All notable changes to `filament-translate-field` will be documented in this file.

## 2.1.0 - 2026-01-15

### 🎉 What's New

This minor release includes several improvements, bug fixes, and dependency updates to enhance compatibility and stability with Filament v4.0.

### 🐛 Bug Fixes

- **Component Fixes**: Resolved issues with the translate component functionality.
- **Styling Corrections**: Fixed various styling inconsistencies.
- **Deprecated Methods**: Updated code to remove usage of deprecated methods.

### What's Changed

* Bump nick-fields/retry from 2 to 3 by @dependabot[bot] in https://github.com/solutionforest/filament-translate-field/pull/29
* Bump actions/checkout from 4 to 5 by @dependabot[bot] in https://github.com/solutionforest/filament-translate-field/pull/28
* Remove filament/upgrade requirement in composer.json by @ctoma in https://github.com/solutionforest/filament-translate-field/pull/30
* Bump dependabot/fetch-metadata from 2.4.0 to 2.5.0 by @dependabot[bot] in https://github.com/solutionforest/filament-translate-field/pull/35
* Bump actions/cache from 4 to 5 by @dependabot[bot] in https://github.com/solutionforest/filament-translate-field/pull/34
* Bump actions/checkout from 5 to 6 by @dependabot[bot] in https://github.com/solutionforest/filament-translate-field/pull/33
* Bump stefanzweifel/git-auto-commit-action from 6 to 7 by @dependabot[bot] in https://github.com/solutionforest/filament-translate-field/pull/32

### New Contributors

* @ctoma made their first contribution in https://github.com/solutionforest/filament-translate-field/pull/30

**Full Changelog**: https://github.com/solutionforest/filament-translate-field/compare/2.0.1...2.1.0

## 2.0.1 - 2025-09-05

### What's Changed

* Bump aglipanci/laravel-pint-action from 2.5 to 2.6 by @dependabot[bot] in https://github.com/solutionforest/filament-translate-field/pull/24
* fix: replace getChildComponentContainers with getChildSchemas by @webard in https://github.com/solutionforest/filament-translate-field/pull/21
* Bump actions/checkout from 4 to 5 by @dependabot[bot] in https://github.com/solutionforest/filament-translate-field/pull/22
* Feat: Fix translate component and introduce more tests by @repl6669 in https://github.com/solutionforest/filament-translate-field/pull/23

### New Contributors

* @webard made their first contribution in https://github.com/solutionforest/filament-translate-field/pull/21
* @repl6669 made their first contribution in https://github.com/solutionforest/filament-translate-field/pull/23

**Full Changelog**: https://github.com/solutionforest/filament-translate-field/compare/2.0.0...2.0.1

## 2.0.0 - 2025-07-22

### 🎉 Major Update: Filament v4.0 Support

This release brings full compatibility with **Filament ^4.0**.

### ⚠️ Breaking Changes

- **Minimum Requirements**: Filament 4.0+
- **For Filament v3.x users**: Please stay on Filament Newsletter v1.x.

### 📦 Version Compatibility

- **v2.0+**: Filament ^4.0 + Laravel ^11.0
- **v1.x**: Filament ^3.0 + Laravel ^10.0

### 📚 Upgrade Guide

Before the package assets can be used, you’ll need to run `php artisan filament:assets` and `php artisan optimize`.

## 1.4.1 - 2025-04-11

### What's Changed

* Adding missing `$locales` parameter to method definition in docblock by @rojtjo in https://github.com/solutionforest/filament-translate-field/pull/15

### New Contributors

* @rojtjo made their first contribution in https://github.com/solutionforest/filament-translate-field/pull/15

**Full Changelog**: https://github.com/solutionforest/filament-translate-field/compare/1.4.0...1.4.1

## 1.4.0 - 2025-02-27

### What's Changed

- Support for laravel 12
- Add test case
- Add github workflow

**Full Changelog**: https://github.com/solutionforest/filament-translate-field/compare/1.3.2...1.4.0

## 1.0.0 - 202X-XX-XX

- initial release
