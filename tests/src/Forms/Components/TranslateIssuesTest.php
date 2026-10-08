<?php

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Livewire;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;
use SolutionForest\FilamentTranslateField\Tests\Forms\Fixtures\Livewire as FormLivewireComponent;
use SolutionForest\FilamentTranslateField\Tests\TestCase;

uses(TestCase::class);

it('renders and saves a RichEditor whose name is not "content"', function () {
    Livewire::test(RichEditorNamedTextComponent::class)
        ->assertSuccessful()
        ->fillForm(['text' => ['en' => '<p>Hello</p>', 'fr' => '<p>Bonjour</p>']])
        ->call('save')
        ->assertSet('saved', ['text' => ['en' => '<p>Hello</p>', 'fr' => '<p>Bonjour</p>']]);
});

class RichEditorNamedTextComponent extends FormLivewireComponent
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Translate::make()->schema([RichEditor::make('text')])->locales(['en', 'fr']),
            ])
            ->statePath('data');
    }

    public array $saved = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function save(): void
    {
        $this->saved = $this->form->getState();
    }

    public function render(): View
    {
        return view('forms.fixtures.form');
    }
}

it('spaces the active panel from the tab bar when not contained', function (?string $livewireProperty) {
    $html = Livewire::test(UncontainedTranslateComponent::class, ['livewireProperty' => $livewireProperty])->html();

    expect($html)
        ->not->toContain('fi-contained')
        ->toContain('translate-field-tab-active');
})->with([
    'alpine tabs' => [null],
    'livewire property' => ['activeLocale'],
]);

class UncontainedTranslateComponent extends FormLivewireComponent
{
    public ?string $livewireProperty = null;

    public string $activeLocale = 'en';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Translate::make()
                    ->contained(false)
                    ->locales(['en', 'fr'])
                    ->livewireProperty($this->livewireProperty)
                    ->schema([TextInput::make('title')]),
            ])
            ->statePath('data');
    }

    public function render(): View
    {
        return view('forms.fixtures.form');
    }
}
