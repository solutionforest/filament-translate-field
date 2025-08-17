<?php

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Livewire;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;
use SolutionForest\FilamentTranslateField\Tests\Forms\Fixtures\Livewire as FormLivewireComponent;
use SolutionForest\FilamentTranslateField\Tests\TestCase;

uses(TestCase::class);

it('can fill and assert data in a translate', function (array $list) {

    $data = $list['data'] ?? [];

    $livewireConfig = Arr::except($list, ['data']);

    Livewire::test(TestComponentWithTranslate::class, $livewireConfig)
        ->fillForm($data)
        ->assertFormSet($data);

})->with(function () {

    $locales = ['en', 'fr'];
    $buildTranslatableArray = fn () => collect($locales)->mapWithKeys(fn ($locale) => [$locale => Str::random()])->all();

    return [
        'normal' => fn () => [
            'data' => [
                'title' => $buildTranslatableArray(),
                'content' => $buildTranslatableArray(),
            ],
            'locales' => $locales,
            'exclude' => [],
        ],
        'exclude_content' => fn () => [
            'data' => [
                'title' => $buildTranslatableArray(),
                'content' => Str::random(),
            ],
            'locales' => $locales,
            'exclude' => ['content'],
        ],
        'closure_schema' => fn () => [
            'data' => [
                'title' => $buildTranslatableArray(),
                'content' => $buildTranslatableArray(),
            ],
            'locales' => $locales,
            'exclude' => [],
            'use_closure_schema' => true,
        ],
        'nested_schema' => fn () => [
            'data' => [
                'title' => $buildTranslatableArray(),
                'content' => $buildTranslatableArray(),
            ],
            'locales' => $locales,
            'exclude' => [],
            'use_nested_schema' => true,
        ],
        'locale_label_closure' => fn () => [
            'data' => [
                'title' => $buildTranslatableArray(),
                'content' => $buildTranslatableArray(),
            ],
            'locales' => $locales,
            'exclude' => [],
            'use_locale_label_closure' => true,
        ],
        'prefix_suffix_locale_labels' => fn () => [
            'data' => [
                'title' => $buildTranslatableArray(),
                'content' => $buildTranslatableArray(),
            ],
            'locales' => $locales,
            'exclude' => [],
            'prefix_locale_label' => true,
            'suffix_locale_label' => true,
            'preform_locale_label_using' => fn (string $locale, string $label) => strtoupper($locale),
        ],
        'per_locale_field_disabled_via_closure' => fn () => [
            'data' => [
                'title' => $buildTranslatableArray(),
                'content' => $buildTranslatableArray(),
            ],
            'locales' => $locales,
            'exclude' => [],
            'schema_per_locale_disabled' => true,
        ],
    ];
});

class TestComponentWithTranslate extends FormLivewireComponent
{
    public array $translateConfig = [];

    public function form(Schema $schema): Schema
    {
        $exclude = $this->translateConfig['exclude'] ?? [];
        $locales = $this->translateConfig['locales'] ?? [];
        $useClosureSchema = $this->translateConfig['use_closure_schema'] ?? false;
        $useNestedSchema = $this->translateConfig['use_nested_schema'] ?? false;
        $useLocaleLabelClosure = $this->translateConfig['use_locale_label_closure'] ?? false;
        $prefixLocaleLabel = $this->translateConfig['prefix_locale_label'] ?? false;
        $suffixLocaleLabel = $this->translateConfig['suffix_locale_label'] ?? false;
        $preformLocaleLabelUsing = $this->translateConfig['preform_locale_label_using'] ?? null;
        $schemaPerLocaleDisabled = $this->translateConfig['schema_per_locale_disabled'] ?? false;
        $schemaDefinition = $useClosureSchema
            ? fn (string $locale) => [
                TextInput::make('title')->label('Title')->required(),
                Textarea::make('content'),
            ]
            : (
                $useNestedSchema
                    ? [
                        Section::make()
                            ->schema([
                                TextInput::make('title')->label('Title')->required(),
                                Textarea::make('content'),
                            ]),
                    ]
                    : [
                        TextInput::make('title')->label('Title')->required(),
                        Textarea::make('content'),
                    ]
            );

        if ($schemaPerLocaleDisabled) {
            $schemaDefinition = fn (string $locale) => [
                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->disabled(fn () => $locale === 'fr'),
                Textarea::make('content'),
            ];
        }

        return $schema
            ->components([
                Translate::make()
                    ->schema($schemaDefinition)
                    ->when($useLocaleLabelClosure, fn ($component) => $component->localeLabels(fn (string $locale) => strtoupper($locale)))
                    ->when($prefixLocaleLabel, fn ($component) => $component->prefixLocaleLabel(true))
                    ->when($suffixLocaleLabel, fn ($component) => $component->suffixLocaleLabel(true))
                    ->when(
                        filled($preformLocaleLabelUsing),
                        fn ($component) => $component->preformLocaleLabelUsing($preformLocaleLabelUsing)
                    )
                    ->locales($locales)
                    ->exclude($exclude),
            ])
            ->statePath('data');
    }

    public function render(): View
    {
        return view('forms.fixtures.form');
    }
}
