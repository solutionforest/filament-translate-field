@php
    use Illuminate\Support\Arr;
    use SolutionForest\FilamentTranslateField\Forms\Component\Translate\Tab;

    $locales = $getLocales() ?? [];
    $defaultLocale = $locales[0] ?? null;
    
    $isContained = $isContained();

    $visibleTabClasses = \Illuminate\Support\Arr::toCssClasses([
        'p-6' => $isContained,
        'mt-6' => ! $isContained,
    ]);

    $invisibleTabClasses = 'invisible h-0 overflow-y-hidden p-0';

    $childComponentsWithLocale = collect($getChildComponentContainers())
        ->map(fn ($container) => $container->getComponents());
    $tabs = collect($childComponentsWithLocale)
        ->map(fn ($components) => Arr::first($components, fn ($component) => $component instanceof Tab));

    $livewireKey = "{$this->getId()}.{$getStatePath()}." . \SolutionForest\FilamentTranslateField\Forms\Component\Translate::class . '.container';
@endphp

<div 
    wire:ignore.self
    x-cloak
    x-data="{ 
        tab: @if ($isTabPersisted() && filled($persistenceId = $getId())) $persist(null).as('tabs-{{ $persistenceId }}') @else null @endif,
        
        getTabs: function () {
            if (! this.$refs.tabsData) {
                return []
            }

            return JSON.parse(this.$refs.tabsData.value)
        },

        updateQueryString: function () {
            if (! @js($isTabPersistedInQueryString())) {
                return
            }

            const url = new URL(window.location.href)
            url.searchParams.set(@js($getTabQueryStringKey()), this.tab)

            history.pushState(null, document.title, url.toString())
        },
    }"
    x-init="() => { 
        $watch('tab', () => updateQueryString())

        const tabs = getTabs()

        if (! tab || ! tabs.includes(tab)) {
            tab = tabs[@js($getActiveTab()) - 1]
        }

        Livewire.hook('commit', ({ component, commit, succeed, fail, respond }) => {
            succeed(({ snapshot, effect }) => {
                $nextTick(() => {
                    if (component.id !== @js($getId())) {
                        return
                    }

                    const tabs = getTabs()

                    if (! tabs.includes(tab)) {
                        tab = tabs[@js($getActiveTab()) - 1]
                    }
                })
            })
        })
    }"
    {{
        $attributes
            ->merge([
                'id' => $getId(),
                'wire:key' => $livewireKey,
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)
            ->merge($getExtraAlpineAttributes(), escape: false)
            ->class([
                'fi-fo-translate flex flex-col',
                'fi-contained rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10' => $isContained,
            ])
    }}
>
    <input
        type="hidden"
        value="{{
            collect($tabs)
                ->map(static fn ($tab) => $tab?->getId())
                ->values()
                ->toJson()
        }}"
        x-ref="tabsData"
    />
    <x-filament::tabs :contained="$isContained" :label="$getLabel()">
        @foreach ($tabs as $tab)
            @php
                $tabId = $tab->getId();
            @endphp

            <x-filament::tabs.item
                :alpine-active="'tab === \'' . $tabId . '\''"
                :badge="$tab->getBadge()"
                :badge-color="$tab->getBadgeColor()"
                :icon="$tab->getIcon()"
                :icon-position="$tab->getIconPosition()"
                :x-on:click="'tab = \'' . $tabId . '\''"
            >
                {{ $tab->getLabel() }}
            </x-filament::tabs.item>
        @endforeach
    </x-filament::tabs>

    @foreach ($tabs as $locale => $tab)
        {{ $tab }}
    @endforeach
</div>