<?php

namespace SolutionForest\FilamentTranslateField\Facades;

use Closure;
use Illuminate\Support\Facades\Facade;

/**
 * @method static static defaultLocales(?array $locales = null)
 * @method static array getDefaultLocales()
 * @method static ?string getLocaleLabel(string $locale, ?string $displayLocale = null)
 * @method static static getLocaleLabelUsing(?Closure $callback)
 *
 * @see \SolutionForest\FilamentTranslateField\FilamentTranslateField
 */
class FilamentTranslateField extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \SolutionForest\FilamentTranslateField\FilamentTranslateField::class;
    }
}
