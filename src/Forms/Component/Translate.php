<?php

namespace SolutionForest\FilamentTranslateField\Forms\Component;

use Closure;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Field;
use Filament\Support\Concerns\CanBeContained;
use Filament\Support\Concerns\CanPersistTab;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Illuminate\Support\Collection;
use SolutionForest\FilamentTranslateField\Facades\FilamentTranslateField;
use SolutionForest\FilamentTranslateField\Forms\Component\Translate\Tab;

class Translate extends Component
{
    use CanBeContained;
    use CanPersistTab;
    use HasExtraAlpineAttributes;

    /**
     * @var view-string
     */
    protected string $view = 'filament-translate-field::forms.components.translate';

    protected null | Closure | array | Collection $locales = null;

    protected null | Closure | array | Collection $exclude = [];

    protected null | Closure | array | Collection $localeLabels = null;

    protected Closure | bool $hasPrefixLocaleLabel = false;

    protected Closure | bool $hasSuffixLocaleLabel = false;

    protected ?Closure $fieldTranslatableLabel = null;

    protected ?Closure $preformLocaleLabelUsing = null;

    protected int | Closure $activeTab = 1;

    protected string | Closure | null $tabQueryStringKey = null;

    final public function __construct(array $schema = [])
    {
        $this->schema($schema);
    }

    public static function make(array $schema = []): static
    {
        $static = app(static::class, ['schema' => $schema]);
        $static->configure();

        return $static;
    }

    public function exclude(Closure | array | Collection $exclude): static
    {
        $this->exclude = $exclude;

        return $this;
    }

    /**
     * @param  \Closure|array<string>|\Illuminate\Support\Collection<string>  $locales
     */
    public function locales(Closure | array | Collection $locales): static
    {
        $this->locales = $locales;

        return $this;
    }

    public function localeLabels(Closure | array | Collection $labels): static
    {
        $this->localeLabels = $labels;

        return $this;
    }

    public function prefixLocaleLabel(Closure | bool $condition = true): static
    {
        $this->hasPrefixLocaleLabel = $condition;

        return $this;
    }

    public function suffixLocaleLabel(Closure | bool $condition = true): static
    {
        $this->hasSuffixLocaleLabel = $condition;

        return $this;
    }

    public function fieldTranslatableLabel(?Closure $fieldTranslatableLabel = null): static
    {
        $this->fieldTranslatableLabel = $fieldTranslatableLabel;

        return $this;
    }

    public function preformLocaleLabelUsing(?Closure $preformLocaleLabelUsing = null): static
    {
        $this->preformLocaleLabelUsing = $preformLocaleLabelUsing;

        return $this;
    }

    public function actions(null | Closure | array $actions): static
    {
        $this->actions = $actions;

        return $this;
    }

    public function activeTab(string | Closure $activeTab): static
    {
        $this->activeTab = $activeTab;

        return $this;
    }

    public function persistTabInQueryString(string | Closure | null $key = 'tab'): static
    {
        $this->tabQueryStringKey = $key;

        return $this;
    }

    /**
     * @return array<string>|\Illuminate\Support\Collection<string>
     */
    public function getLocales(): array | Collection
    {
        return $this->evaluate($this->locales) ?? FilamentTranslateField::getDefaultLocales();
    }

    /**
     * @return array<string>|\Illuminate\Support\Collection<string>
     */
    public function getLocaleLabels(): array | Collection
    {
        return $this->evaluate($this->localeLabels)
            ?? collect($this->getLocales())
                ->map(fn ($locale) => FilamentTranslateField::getLocaleLabel($locale, $locale))
                ->all();
    }

    public function getLocaleLabel(string $locale): string
    {
        $labels = $this->evaluate($this->localeLabels, [
            'locale' => $locale,
        ]) ?? FilamentTranslateField::getLocaleLabel($locale, $locale);

        $label = null;

        if ($labels && is_array($labels)) {
            $label = data_get($labels, $locale);
        } elseif ($labels && is_string($labels)) {
            $label = $labels;
        }

        return $label ?? $locale;
    }

    public function hasPrefixLocaleLabel(Component $component, string $locale): bool
    {
        return boolval($this->evaluate($this->hasPrefixLocaleLabel, [
            'field' => $component,
            'locale' => $locale,
        ]) ?? false);
    }

    public function hasSuffixLocaleLabel(Component $component, string $locale): bool
    {
        return boolval($this->evaluate($this->hasSuffixLocaleLabel, [
            'field' => $component,
            'locale' => $locale,
        ]) ?? false);
    }

    public function getFieldTranslatableLabel(Component $component, string $locale): ?string
    {
        return $this->evaluate($this->fieldTranslatableLabel, [
            'field' => $component,
            'locale' => $locale,
        ]);
    }

    /**
     * @return array<Component>
     */
    public function getChildComponentsByLocale(string $locale): array
    {
        /** @var array<Component> */
        return $this->evaluate($this->childComponents, [
            'locale' => $locale,
        ]) ?? [];
    }

    public function getActiveTab(): int
    {
        if ($this->isTabPersistedInQueryString()) {

            $queryStringTab = request()->query($this->getTabQueryStringKey());

            $tabs = collect($this->getChildComponentContainers())
                ->map(fn (ComponentContainer $container) => collect($container->getComponents())->first() ?? null)
                ->values();

            foreach ($tabs as $index => $tab) {

                if ($tab?->getId() !== $queryStringTab) {
                    continue;
                }

                return $index + 1;
            }
        }

        return $this->evaluate($this->activeTab, ['locales' => $this->getLocales()]);
    }

    public function getTabQueryStringKey(): ?string
    {
        return $this->evaluate($this->tabQueryStringKey);
    }

    public function isTabPersistedInQueryString(): bool
    {
        return filled($this->getTabQueryStringKey());
    }

    /**
     * @return array<ComponentContainer>
     */
    public function getChildComponentContainers(bool $withHidden = false): array
    {
        $containers = [];

        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $containers[$locale] = ComponentContainer::make($this->getLivewire())
                ->parentComponent($this)
                ->components([
                    Tab::make($locale)
                        ->label($this->getLocaleLabel($locale))
                    // Tab::make($this->getLocaleLabel($locale))
                        ->locale($locale)
                        ->registerActions($this->getActions())
                        ->schema(
                            collect($this->getChildComponentsByLocale($locale))
                                ->map(fn ($component) => $this->prepareTranslateLocaleComponent($component, $locale))
                                ->all()
                        ),
                ])
                ->getClone();
        }

        return $containers;
    }

    protected function prepareTranslateLocaleComponent(Component $component, string $locale): Component
    {
        $localeComponent = clone $component;

        if ($localeComponent instanceof Field || method_exists($localeComponent, 'getName')) {

            $localeComponentName = $localeComponent->getName();

            if (filled($localeComponentName) && is_string($localeComponentName) && ! in_array($localeComponentName, $this->exclude)) {

                $localeComponent->label($this->getFieldTranslatableLabel($component, $locale) ?? $component->getLabel());

                $localeLabel = $this->getLocaleLabel($locale);
                $performedLocaleLabel = $this->preformLocaleLabelUsing
                    ? $this->evaluate($this->preformLocaleLabelUsing, [
                        'locale' => $locale,
                        'label' => $localeLabel,
                    ])
                    : null;
                if (! $performedLocaleLabel) {
                    $performedLocaleLabel = "({$localeLabel})";
                }
                if ($this->hasPrefixLocaleLabel($component, $locale)) {
                    $localeComponent->label("{$performedLocaleLabel} {$localeComponent->getLabel()}");
                }
                if ($this->hasSuffixLocaleLabel($component, $locale)) {
                    $localeComponent->label("{$localeComponent->getLabel()} {$performedLocaleLabel}");
                }

                // Spatie transltable field format
                if (method_exists($localeComponent, 'name')) {
                    $localeComponent->name($localeComponentName . '.' . $locale);
                }
                if (method_exists($localeComponent, 'statePath')) {
                    $localeComponent->statePath($localeComponent->getName());
                }
            }

        } else {

            $childComponents = $localeComponent->getChildComponents();

            if ($childComponents) {
                $localeComponent->schema(
                    collect($childComponents)
                        ->map(fn ($childComponent) => $this->prepareTranslateLocaleComponent($childComponent, $locale))
                        ->all()
                );
            }
        }

        return $localeComponent;
    }

    /**
     * @return array<mixed>
     */
    protected function resolveDefaultClosureDependencyForEvaluationByName(string $parameterName): array
    {
        if ($parameterName == 'locales') {
            return [$this->getLocales()];
        }

        return parent::resolveDefaultClosureDependencyForEvaluationByName($parameterName);
    }
}
