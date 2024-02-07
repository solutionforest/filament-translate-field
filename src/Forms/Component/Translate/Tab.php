<?php

namespace SolutionForest\FilamentTranslateField\Forms\Component\Translate;

use Filament\Forms\Components\Tabs\Tab as BaseComponent;

class Tab extends BaseComponent
{
    protected string $view = 'filament-translate-field::forms.components.translate-tab';

    protected ?string $locale = null;

    public function locale(?string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    public function getKey(): ?string
    {
        return parent::getKey() ?? ($this->getActions() ? $this->getId() : null);
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }
}
