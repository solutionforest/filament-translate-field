<?php

namespace SolutionForest\FilamentTranslateField;

use Livewire\Features\SupportTesting\Testable;
use SolutionForest\FilamentTranslateField\Testing\TestsFilamentTranslateField;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentTranslateFieldServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-translate-field';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews()
            ->hasConfigFile();
    }

    public function packageBooted(): void
    {
        // Testing
        Testable::mixin(new TestsFilamentTranslateField);
    }
}
