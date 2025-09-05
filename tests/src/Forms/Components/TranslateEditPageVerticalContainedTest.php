<?php

use Livewire\Livewire;
use SolutionForest\FilamentTranslateField\Tests\Fixtures\Models\Post;
use SolutionForest\FilamentTranslateField\Tests\Stubs\Filament\Pages\EditPostVertical;
use SolutionForest\FilamentTranslateField\Tests\TestCase;

uses(TestCase::class);

function makePostVertical(): Post
{
    return Post::create([
        'title' => ['en' => 'Hello', 'fr' => 'Bonjour'],
        'content' => ['en' => 'World', 'fr' => 'Monde'],
    ]);
}

it('renders vertical class when vertical is true', function () {
    $post = makePostVertical();

    Livewire::test(EditPostVertical::class, [
        'record' => $post->getKey(),
    ])->assertSee('fi-vertical');
});
