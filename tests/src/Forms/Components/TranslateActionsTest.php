<?php

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Livewire;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;
use SolutionForest\FilamentTranslateField\Tests\Forms\Fixtures\Livewire as FormLivewireComponent;
use SolutionForest\FilamentTranslateField\Tests\TestCase;

uses(TestCase::class);

it('sends the locale to the browser when rendering the actions', function () {
    $html = Livewire::test(TranslateActionComponent::class)->html();

    expect($html)
        ->toContain('mountAction(\'fill_en\', JSON.parse(\'{\\u0022locale\\u0022:\\u0022en\\u0022}\')')
        // visible() hides the action on the "fr" tab
        ->not->toContain('fill_fr');
});

it('runs the action with the locale it was rendered for', function () {
    Livewire::test(TranslateActionComponent::class)
        ->call('mountAction', 'fill_en', ['locale' => 'en'], ['schemaComponent' => 'form'])
        ->assertSet('ran', ['en']);
});

class TranslateActionComponent extends FormLivewireComponent implements HasActions
{
    use InteractsWithActions;

    public array $ran = [];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Translate::make()
                    ->locales(['en', 'fr'])
                    ->schema([TextInput::make('title')])
                    ->actions([
                        Action::make('fill')
                            ->visible(fn (array $arguments) => $arguments['locale'] == 'en')
                            ->action(function (array $arguments) {
                                $this->ran[] = $arguments['locale'];
                            }),
                    ]),
            ])
            ->statePath('data');
    }

    public function render(): View
    {
        return view('forms.fixtures.form');
    }
}
