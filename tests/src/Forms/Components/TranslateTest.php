<?php

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
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
    ];
});

class TestComponentWithTranslate extends FormLivewireComponent
{
    public array $translateConfig = [];

    public function form(Form $form): Form
    {
        $exclude = $this->translateConfig['exclude'] ?? [];
        $locales = $this->translateConfig['locales'] ?? [];
        return $form
            ->schema([
                Translate::make()
                    ->schema([
                        TextInput::make('title')->required(),
                        Textarea::make('content'),
                    ])
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