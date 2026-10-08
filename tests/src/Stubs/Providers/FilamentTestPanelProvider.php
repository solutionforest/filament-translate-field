<?php

namespace SolutionForest\FilamentTranslateField\Tests\Stubs\Providers;

use Filament\Panel;
use Filament\PanelProvider;

class FilamentTestPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('test')
            ->path('admin');
    }
}
