<?php

namespace SolutionForest\FilamentTranslateField;

use Closure;
use Filament\Panel;
use Filament\Contracts\Plugin;

class FilamentTranslateFieldPlugin implements Plugin
{
    /**
     * @var array<string>
     */
    protected array $defaultLocales = [];

    protected ?Closure $getLocaleLabelUsing = null;
    
    public function getId(): string
    {
        return 'filament-translate-field';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    /**
     * @param  array<string> | null  $defaultLocales
     */
    public function defaultLocales(?array $defaultLocales = null): static
    {
        $this->defaultLocales = $defaultLocales;

        return $this;
    }

    public function getLocaleLabelUsing(?Closure $callback): static
    {
        $this->getLocaleLabelUsing = $callback;

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getDefaultLocales(): array
    {
        return $this->defaultLocales;
    }

    public function getLocaleLabel(string $locale, ?string $displayLocale = null): ?string
    {
        $displayLocale ??= app()->getLocale();

        $label = null;

        if ($callback = $this->getLocaleLabelUsing) {
            $label = $callback($locale, $displayLocale);
        }

        return $label ?? (locale_get_display_name($locale, $displayLocale) ?: null);
    }
}
