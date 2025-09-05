<?php

namespace SolutionForest\FilamentTranslateField\Tests\Stubs\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;
use SolutionForest\FilamentTranslateField\Tests\Stubs\Filament\Resources\PostResource;

class EditPostVertical extends TestingEditRecord
{
    protected static string $resource = PostResource::class;

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Translate::make()
                ->vertical(true)
                ->locales(['en', 'fr'])
                ->schema([
                    TextInput::make('title'),
                ]),
        ])->statePath('data');
    }

    public function render(): View
    {
        return view('filament.pages.edit');
    }
}
