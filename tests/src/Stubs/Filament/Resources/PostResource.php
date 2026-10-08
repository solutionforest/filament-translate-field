<?php

namespace SolutionForest\FilamentTranslateField\Tests\Stubs\Filament\Resources;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;
use SolutionForest\FilamentTranslateField\Tests\Fixtures\Models\Post;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Translate::make()
                ->locales(['en', 'fr'])
                ->schema([
                    TextInput::make('title')->label('Title')->required(),
                    Textarea::make('content'),
                ]),
        ])->statePath('data');
    }

    public static function getPages(): array
    {
        return [
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}

namespace SolutionForest\FilamentTranslateField\Tests\Stubs\Filament\Resources\PostResource\Pages;

use Illuminate\Contracts\View\View;
use SolutionForest\FilamentTranslateField\Tests\Stubs\Filament\Pages\TestingEditRecord as EditRecord;
use SolutionForest\FilamentTranslateField\Tests\Stubs\Filament\Resources\PostResource;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    public function render(): View
    {
        return view('filament.pages.edit');
    }
}
