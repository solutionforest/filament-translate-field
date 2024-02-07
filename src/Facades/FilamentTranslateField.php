<?php

namespace SolutionForest\FilamentTranslateField\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 
 * @method static array getDefaultLocales()
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