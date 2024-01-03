# Filament Translate Field

[![Latest Version on Packagist](https://img.shields.io/packagist/v/solution-forest/filament-translate-field.svg?style=flat-square)](https://packagist.org/packages/solution-forest/filament-translate-field)
[![Total Downloads](https://img.shields.io/packagist/dt/solution-forest/filament-translate-field.svg?style=flat-square)](https://packagist.org/packages/solution-forest/filament-translate-field)


Filament Translate Field is a library for Filament CMS that simplifies managing multiple translatable fields in different languages.

## Installation

You can install the package via composer:

```bash
composer require solution-forest/filament-translate-field
```

## Important

- There is a conflict with the `Translatable` trait in the [filament/spatie-laravel-translatable-plugin](https://filamentphp.com/plugins/filament-spatie-translatable) library when used on the EditPage. It is advised not to utilize the `Translatable` while editing.


## Configuration:

- Define the `translatable` fields in your model using the translatable package of your choice, such as "spatie/laravel-translatabl" or "dimsav/laravel-translatable".
- Configure the translatable fields in the model's *$translatable* property, specifying the translatable attributes.


## Usage

### Form component

By using the `Translate` component, you can easily configure your [form fields](https://filamentphp.com/docs/3.x/forms/fields/getting-started) to support multiple languages and provide translations for each locale.

```php
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

Translate::make()
    ->columnSpanFull()
    ->columns(2)
    ->schema([
        TextInput::make('title')->required(), 
        Textarea::make('short_desc'),
        RichEditor::make('description')->columnSpanFull(),
    ])
    ->locales(['en', 'zh-HK', 'zh-CN'])
    ->localeLabels([
        'en' => 'English',
        'zh-HK' => '繁',
        'zh-CN' => '簡',
    ])
```


In the given example, when you save the model, the data will be stored in the following format:

```json
{
  "id": 1,
  "title": {
    "en": "Dump",
    "zh-HK": "Dump",
    "zh-CN": "Dump"
  },
  "short_desc": {
    "en": null,
    "zh-HK": null,
    "zh-CN": null
  },
  "description": {
    "en": null,
    "zh-HK": null,
    "zh-CN": null
  }
}
```

#### Label

You have the flexibility to customize the translate label for each field in each locale. You can use the `fieldTranslatableLabel` method to provide custom labels based on the field instance and the current locale.

```php
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

 Translate::make()
    ->schema([
        TextInput::make('title')->required(), 
        Textarea::make('short_desc'),
        RichEditor::make('description')->columnSpanFull(),
    ])
    ->locales(['en', 'zh-HK', 'zh-CN'])
    ->localeLabels([
        'en' => 'English',
        'zh-HK' => '繁',
        'zh-CN' => '簡',
    ])
    ->fieldTranslatableLabel(fn ($field, $locale) => __($field->getName(), locale: $locale))
```

If you simply want to add a prefix or suffix locale label to the form field, you can use the `prefixLocaleLabel` or `suffixLocaleLabel` method. This makes it easier for users to identify the language associated with each field.

```php
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

Translate::make()
    ->schema([
        TextInput::make('title')->required(), 
        Textarea::make('short_desc'),
        RichEditor::make('description')->columnSpanFull(),
    ])
    ->locales(['en', 'zh-HK', 'zh-CN'])
    ->localeLabels([
        'en' => 'English',
        'zh-HK' => '繁',
        'zh-CN' => '簡',
    ])
    ->prefixLocaleLabel()
    ->suffixLocaleLabel()
```

#### Injecting the current form field

Additionally, if you need to access the current form field instance, you can inject the `$field` parameter into the callback functions. This allows you to perform specific actions or conditions based on the field being processed.

```php
use Filament\Forms\Components\Component;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

Translate::make()
    ...
    ->prefixLocaleLabel(function(Component $field) {
        // need return boolean value
        return $field->getName() == 'title';
    })
    ->suffixLocaleLabel(function(Component $field) {
        // need return boolean value
        return $field->getName() == 'title';
    })

```


## Publishing Views

To publish the views, use:

```bash
php artisan vendor:publish --provider="SolutionForest\\FilamentTranslateField\\FilamentTranslateFieldServiceProvider" --tag="filament-translate-field-views"
```

## Testing

```bash
composer test
```

## Changelog

See the [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

See [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

If you discover any security related issues, please email info+package@solutionforest.net instead of using the issue tracker.

## Credits

- [Carly]
- [All Contributors](../../contributors)

## License

Filament Tree is open-sourced software licensed under the [MIT license](LICENSE.md).


<p align="center"><a href="https://solutionforest.com" target="_blank"><img src="https://github.com/solutionforest/.github/blob/main/docs/images/sf.png?raw=true" width="200"></a></p>


## About Solution Forest

[Solution Forest](https://solutionforest.com) Web development agency based in Hong Kong. We help customers to solve their problems. We Love Open Soruces. 

We have built a collection of best-in-class products:

- [VantagoAds](https://vantagoads.com): A self manage Ads Server, Simplify Your Advertising Strategy.
- [GatherPro.events](https://gatherpro.events): A Event Photos management tools, Streamline Your Event Photos.
- [Website CMS Management](https://filamentphp.com/plugins/solution-forest-cms-website): Website CMS Management
