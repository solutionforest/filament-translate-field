# Filament Translate Field

[![Latest Version on Packagist](https://img.shields.io/packagist/v/solution-forest/filament-translate-field.svg?style=flat-square)](https://packagist.org/packages/solution-forest/filament-translate-field)
[![Total Downloads](https://img.shields.io/packagist/dt/solution-forest/filament-translate-field.svg?style=flat-square)](https://packagist.org/packages/solution-forest/filament-translate-field)


Filament Translate Field is a library for Filament CMS that simplifies managing multiple translatable fields in different languages.

![filament-translate-field-1](https://github.com/solutionforest/filament-translate-field/assets/68525320/fc7af90f-1e4d-45a2-8988-0ef36632db40)


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

## Adding the plugin to a panel
To add a plugin to a panel, you must include it in the configuration file using the plugin() method:
```php
use SolutionForest\FilamentTranslateField\FilamentTranslateFieldPlugin;
 
public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(FilamentTranslateFieldPlugin::make());
}
```

## Setting the default translatable locales
To set up the locales that can be used to translate content, you can pass an array of locales to the `defaultLocales()` plugin method:

```php
use SolutionForest\FilamentTranslateField\FilamentTranslateFieldPlugin;
 
public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(
            FilamentTranslateFieldPlugin::make()
               ->defaultLocales(['en', 'es', 'fr']),
        );
}
```

## Usage

### Form component

By using the `Translate` component, you can easily configure your [form fields](https://filamentphp.com/docs/3.x/forms/fields/getting-started) to support multiple languages and provide translations for each locale.

```php
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

Translate::make()
    ->schema([
        // ...
    ])
```


#### Setting the translatable locales for a particular fields
By default, the translatable locales can be set globally for all translate form component in the plugin configuration. Alternatively, you can customize the translatable locales for a particular resource by overriding the `locales()` method in `Translate` class:

```php
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

Translate::make()->locales(['en', 'es'])
```

#### Label

##### Setting the translatable label for a particular field

You have the flexibility to customize the translate label for each field in each locale. You can use the `fieldTranslatableLabel()` method to provide custom labels based on the field instance and the current locale.

```php
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

 Translate::make()
    ->schema([
        // ...
    ])
    ->fieldTranslatableLabel(fn ($field, $locale) => __($field->getName(), locale: $locale))
```

##### Adding prefix/suffix locale label to the field

If you simply want to add a prefix or suffix locale label to the form field, you can use the `prefixLocaleLabel()` or `suffixLocaleLabel()` method. This makes it easier for users to identify the language associated with each field.

```php
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

Translate::make()
    ->schema([
        // ...
    ])
    ->prefixLocaleLabel()
    ->suffixLocaleLabel()
```

> `prefixLocaleLabel:
> 
> ![filament-translate-field-3](https://github.com/solutionforest/filament-translate-field/assets/68525320/0203e682-f324-4957-8680-4cffccab300c)

> `suffixLocaleLabel`:
> 
> ![filament-translate-field-4](https://github.com/solutionforest/filament-translate-field/assets/68525320/7f4403e9-c857-4ebf-b022-8fed12094426)



#### Injecting the current form field

Additionally, if you need to access the current form field instance, you can inject the `$field` parameter into the callback functions. This allows you to perform specific actions or conditions based on the field being processed.

```php
use Filament\Forms\Components\Component;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

Translate::make()
    // ...
    ->prefixLocaleLabel(function(Component $field) {
        // need return boolean value
        return $field->getName() == 'title';
    })
    ->suffixLocaleLabel(function(Component $field) {
        // need return boolean value
        return $field->getName() == 'title';
    })

```

![filament-translate-field-5](https://github.com/solutionforest/filament-translate-field/assets/68525320/a88fcb69-a63d-43a6-857b-5323df877134)



## Publishing Views

To publish the views, use:

```bash
php artisan vendor:publish --provider="SolutionForest\\FilamentTranslateField\\FilamentTranslateFieldServiceProvider" --tag="filament-translate-field-views"
```

## Example

### Demo
https://github.com/solutionforest/filament-translate-field/assets/68525320/c4d52db5-1970-4f95-a5a2-30e1636dc2c8

### Sample Code

In Filament panel:
```php
use SolutionForest\FilamentTranslateField\FilamentTranslateFieldPlugin;
 
public function panel(Panel $panel): Panel
{
    return $panel
        // ...
            ->plugin(FilamentTranslateFieldPlugin::make()
                ->defaultLocales(['en', 'es', 'fr'])
            );
}
```

In app/Filament/Resources/NewsResource.php:
```php
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Resources\Resource;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;

class NewsResource extends Resource
{
    // ...
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Translate::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')->required(), 
                        Textarea::make('short_desc'),
                       RichEditor::make('description')->columnSpanFull(),
                    ])
                    ->fieldTranslatableLabel(fn ($field, $locale) => __($field->getName(), locale: $locale)),
            ]);
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Panel::make([
                    Tables\Columns\TextColumn::make('data')
                        ->getStateUsing(fn ($record) => $record->setVisible(['title', 'short_desc', 'description'])->toJson()),
                ]),
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
    
    // ...
}
```

In app/Models/News.php:
```php
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class News extends Model
{
    use HasTranslations;
    // ...

    protected $guarded = ['id'];

    public $translatable = ['title', 'short_desc', 'description'];

    // ...
}
```

In resources/lang/en.json: 
```json
{
    "title": "Title",
    "short_desc": "Short description",
    "description": "Description"
}
```

In resources/lang/es.json: 
```json
{
    "title": "Título",
    "short_desc": "Breve descripción",
    "description": "Descripción"
}
```

In resources/lang/fr.json: 
```json
{
    "title": "Titre",
    "short_desc": "Brève description",
    "description": "Description"
}
```

In the given example, when you save the model, the data will be stored in the following format:

```json
{
  "id": 1,
  "title": {
    "en": "Dump",
    "es": "Dump",
    "fr": "Dump"
  },
  "short_desc": {
    "en": null,
    "es": null,
    "fr": null
  },
  "description": {
    "en": null,
    "es": null,
    "fr": null
  }
}
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
