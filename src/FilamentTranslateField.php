<?php

namespace SolutionForest\FilamentTranslateField;

use Closure;


class FilamentTranslateField
{
    /**
     * @var array<string>
     */
    protected ?array $defaultLocales = null;
    
    protected ?Closure $getLocaleLabelUsing = null;

    /**
     * @param  array<string> | null  $defaultLocales
     */
    public function defaultLocales(?array $defaultLocales = null): static
    {
        $this->defaultLocales = $defaultLocales;

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getDefaultLocales(): array
    {
        return $this->defaultLocales ?? config('filament-translate-field.locales', []);
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

    public function getLocaleLabelUsing(?Closure $callback): static
    {
        $this->getLocaleLabelUsing = $callback;

        return $this;
    }
}