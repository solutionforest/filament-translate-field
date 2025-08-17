<?php

namespace SolutionForest\FilamentTranslateField;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
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
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        // Testing
        Testable::mixin(new TestsFilamentTranslateField);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'solution-forest/filament-translate-field';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            Css::make('filament-translate-field-styles', __DIR__ . '/../resources/dist/filament-translate-fields.css'),
        ];
    }
}
