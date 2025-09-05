<?php

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Livewire;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate;
use SolutionForest\FilamentTranslateField\Tests\Fixtures\Models\Post;
use SolutionForest\FilamentTranslateField\Tests\Stubs\Filament\Pages\TestingEditRecord as EditRecord;
use SolutionForest\FilamentTranslateField\Tests\Stubs\Filament\Resources\PostResource;
use SolutionForest\FilamentTranslateField\Tests\Stubs\Filament\Resources\PostResource\Pages\EditPost;
use SolutionForest\FilamentTranslateField\Tests\TestCase;

uses(TestCase::class);

function makePost(): Post
{
    return Post::create([
        'title' => ['en' => 'Hello', 'fr' => 'Bonjour'],
        'content' => ['en' => 'World', 'fr' => 'Monde'],
    ]);
}

it('renders tabs per locale with default labels and active state', function () {
    $post = makePost();

    Livewire::test(EditPost::class, ['record' => $post->getKey()])
        ->assertSee('Title')
        ->assertSee('title.en')
        ->assertSee('title.fr')
        ->assertSee('fi-sc-tabs');
});

it('uses custom locale labels and prefix/suffix and transformer', function () {
    $post = makePost();

    Livewire::test(new class extends EditRecord
    {
        protected static string $resource = PostResource::class;

        public function form(Schema $schema): Schema
        {
            return $schema->components([
                Translate::make()
                    ->locales(['en', 'fr'])
                    ->localeLabels(fn (string $locale) => strtoupper($locale))
                    ->prefixLocaleLabel(true)
                    ->suffixLocaleLabel(true)
                    ->preformLocaleLabelUsing(fn (string $locale, string $label) => strtoupper($locale))
                    ->schema([
                        TextInput::make('title')->label('Title'),
                    ]),
            ])->statePath('data');
        }

        public function render(): View
        {
            return view('filament.pages.edit');
        }
    }, ['record' => $post->getKey()])
        ->assertSeeInOrder(['EN', 'FR'])
        ->assertSee('EN Title EN');
});

it('excludes specified fields from translation and keeps state shape', function () {
    $post = makePost();

    $content = Str::random(8);

    Livewire::test(new class extends EditRecord
    {
        protected static string $resource = PostResource::class;

        public function form(Schema $schema): Schema
        {
            return $schema->components([
                Translate::make()
                    ->locales(['en', 'fr'])
                    ->exclude(['content'])
                    ->schema([
                        TextInput::make('title')->label('Title'),
                        Textarea::make('content')->label('Content'),
                    ]),
            ])->statePath('data');
        }

        public function render(): View
        {
            return view('filament.pages.edit');
        }
    }, ['record' => $post->getKey()])
        ->fillForm([
            'title' => ['en' => 'A', 'fr' => 'B'],
            'content' => $content,
        ])
        ->assertFormSet([
            'title' => ['en' => 'A', 'fr' => 'B'],
            'content' => $content,
        ]);
});

it('applies per-locale closure and nested schema', function () {
    $post = makePost();

    Livewire::test(new class extends EditRecord
    {
        protected static string $resource = PostResource::class;

        public function form(Schema $schema): Schema
        {
            return $schema->components([
                Translate::make()
                    ->locales(['en', 'fr'])
                    ->schema(function (string $locale) {
                        return [
                            Section::make()->schema([
                                TextInput::make('title')->label('Title')->disabled(fn () => $locale === 'fr'),
                                Textarea::make('content'),
                            ]),
                        ];
                    }),
            ])->statePath('data');
        }

        public function render(): View
        {
            return view('filament.pages.edit');
        }
    }, ['record' => $post->getKey()])
        ->assertSee('title.en')
        ->assertSee('title.fr')
        ->assertSeeHtml('disabled');
});

it('fills and sets locale-suffixed state', function () {
    $post = makePost();

    $titleEn = Str::random(6);
    $titleFr = Str::random(6);

    Livewire::test(EditPost::class, ['record' => $post->getKey()])
        ->fillForm([
            'title' => ['en' => $titleEn, 'fr' => $titleFr],
            'content' => ['en' => 'A', 'fr' => 'B'],
        ])
        ->assertFormSet([
            'title' => ['en' => $titleEn, 'fr' => $titleFr],
            'content' => ['en' => 'A', 'fr' => 'B'],
        ]);
});

it('renders tab persistence attributes and supports livewire property', function () {
    $post = makePost();

    // Query string persistence attributes are present in markup
    Livewire::test(new class extends EditRecord
    {
        protected static string $resource = PostResource::class;

        public function form(Schema $schema): Schema
        {
            return $schema->components([
                Translate::make()
                    ->locales(['en', 'fr'])
                    ->schema([
                        TextInput::make('title'),
                    ])
                    ->persistTabInQueryString('tab'),
            ])->statePath('data');
        }

        public function render(): View
        {
            return view('filament.pages.edit');
        }
    }, ['record' => $post->getKey()])
        ->assertSee('tabQueryStringKey');

    // Livewire property branch
    Livewire::test(new class extends EditRecord
    {
        public $activeLocale = 'en';

        protected static string $resource = PostResource::class;

        public function form(Schema $schema): Schema
        {
            return $schema->components([
                Translate::make()
                    ->locales(['en', 'fr'])
                    ->livewireProperty('activeLocale')
                    ->schema([
                        TextInput::make('title'),
                    ]),
            ])->statePath('data');
        }

        public function render(): View
        {
            return view('filament.pages.edit');
        }
    }, ['record' => $post->getKey()])
        ->set('activeLocale', 'fr')
        ->assertSet('activeLocale', 'fr');
});
