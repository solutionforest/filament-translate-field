@php
    use SolutionForest\FilamentTranslateField\Forms\Component\Translate\Tab;

    $activeTab = $getActiveTab();
    $isContained = $isContained();
    $isVertical = $isVertical();
    $label = $getLabel();
    $livewireProperty = $getLivewireProperty();
    $renderHookScopes = $getRenderHookScopes();
    $locales = $getLocales() ?? [];
    $defaultLocale = $locales[0] ?? null;

    $visibleTabClasses = \Illuminate\Support\Arr::toCssClasses([
        'p-6' => $isContained,
        'mt-6' => !$isContained,
    ]);

    $invisibleTabClasses = 'invisible h-0 overflow-y-hidden p-0';

    $childComponentsWithLocale = collect($getChildComponentContainers())->map(
        fn($container) => $container->getComponents(),
    );
    $tabs = collect($childComponentsWithLocale)->map(
        fn($components) => Arr::first($components, fn($component) => $component instanceof Tab),
    );

@endphp

@if (blank($livewireProperty))
    <div x-load
        x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('tabs', 'filament/schemas') }}"
        x-data="tabsSchemaComponent({
            activeTab: @js($activeTab),
            isTabPersistedInQueryString: @js($isTabPersistedInQueryString()),
            livewireId: @js($this->getId()),
            tab: @if ($isTabPersisted() && filled($persistenceKey = $getKey())) $persist(null).as('tabs-{{ $persistenceKey }}') @else @js(null) @endif,
            tabQueryStringKey: @js($getTabQueryStringKey()),
        })" wire:ignore.self
        {{ $attributes->merge(
                [
                    'id' => $getId(),
                    'wire:key' => $getLivewireKey() . '.container',
                ],
                escape: false,
            )->merge($getExtraAttributes(), escape: false)->merge($getExtraAlpineAttributes(), escape: false)->class(['fi-sc-tabs', 'fi-contained' => $isContained, 'fi-vertical' => $isVertical]) }}>
        <input type="hidden"
            value="{{ collect($tabs)->filter(static fn(Tab $tab): bool => $tab->isVisible())->map(static fn(Tab $tab) => $tab->getKey(isAbsolute: false))->values()->toJson() }}"
            x-ref="tabsData" />

        <x-filament::tabs :contained="$isContained" :label="$label" :vertical="$isVertical" x-cloak>
            @foreach ($getStartRenderHooks() as $startRenderHook)
                {{ \Filament\Support\Facades\FilamentView::renderHook($startRenderHook, scopes: $renderHookScopes) }}
            @endforeach

            @foreach ($tabs as $tab)
                @php
                    $tabKey = $tab->getKey(isAbsolute: false);
                    $tabBadge = $tab->getBadge();
                    $tabBadgeColor = $tab->getBadgeColor();
                    $tabBadgeIcon = $tab->getBadgeIcon();
                    $tabBadgeIconPosition = $tab->getBadgeIconPosition();
                    $tabBadgeTooltip = $tab->getBadgeTooltip();
                    $tabIcon = $tab->getIcon();
                    $tabIconPosition = $tab->getIconPosition();
                    $tabExtraAttributeBag = $tab->getExtraAttributeBag();
                @endphp

                <x-filament::tabs.item :alpine-active="'tab === \'' . $tabKey . '\''" :badge="$tabBadge" :badge-color="$tabBadgeColor" :badge-icon="$tabBadgeIcon"
                    :badge-icon-position="$tabBadgeIconPosition" :badge-tooltip="$tabBadgeTooltip" :icon="$tabIcon" :icon-position="$tabIconPosition"
                    :x-on:click="'tab = \'' . $tabKey . '\''" :attributes="$tabExtraAttributeBag">
                    {{ $tab->getLabel() }}
                </x-filament::tabs.item>
            @endforeach

            @foreach ($getEndRenderHooks() as $endRenderHook)
                {{ \Filament\Support\Facades\FilamentView::renderHook($endRenderHook, scopes: $renderHookScopes) }}
            @endforeach
        </x-filament::tabs>

        <div class="fi-section" style="--tw-shadow: none;">
            <div class="fi-section-content-ctn">
                <div class="fi-sc  fi-sc-has-gap fi-grid  fi-section-content">

                    @foreach ($getChildSchema()->getComponents() as $tab)
                        {{ $tab }}
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@else
    @php
        $activeTab = strval($this->{$livewireProperty});
    @endphp

    <div
        {{ $attributes->merge(
                [
                    'id' => $getId(),
                    'wire:key' => $getLivewireKey() . '.container',
                ],
                escape: false,
            )->merge($getExtraAttributes(), escape: false)->class(['fi-sc-tabs', 'fi-contained' => $isContained, 'fi-vertical' => $isVertical]) }}>
        <x-filament::tabs :contained="$isContained" :label="$label" :vertical="$isVertical">
            @foreach ($getStartRenderHooks() as $startRenderHook)
                {{ \Filament\Support\Facades\FilamentView::renderHook($startRenderHook, scopes: $renderHookScopes) }}
            @endforeach

            @foreach ($getChildSchema()->getComponents(withOriginalKeys: true) as $tabKey => $tab)
                @php
                    $tabBadge = $tab->getBadge();
                    $tabBadgeColor = $tab->getBadgeColor();
                    $tabBadgeIcon = $tab->getBadgeIcon();
                    $tabBadgeIconPosition = $tab->getBadgeIconPosition();
                    $tabBadgeTooltip = $tab->getBadgeTooltip();
                    $tabIcon = $tab->getIcon();
                    $tabIconPosition = $tab->getIconPosition();
                    $tabExtraAttributeBag = $tab->getExtraAttributeBag();
                    $tabKey = strval($tabKey);
                @endphp

                <x-filament::tabs.item :active="$activeTab === $tabKey" :badge="$tabBadge" :badge-color="$tabBadgeColor" :badge-icon="$tabBadgeIcon"
                    :badge-icon-position="$tabBadgeIconPosition" :badge-tooltip="$tabBadgeTooltip" :icon="$tabIcon" :icon-position="$tabIconPosition"
                    :wire:click="'$set(\'' . $livewireProperty . '\', ' . (filled($tabKey) ? ('\'' . $tabKey . '\'') : 'null') . ')'"
                    :attributes="$tabExtraAttributeBag">
                    {{ $tab->getLabel() ?? $this->generateTabLabel($tabKey) }}
                </x-filament::tabs.item>
            @endforeach

            @foreach ($getEndRenderHooks() as $endRenderHook)
                {{ \Filament\Support\Facades\FilamentView::renderHook($endRenderHook, scopes: $renderHookScopes) }}
            @endforeach
        </x-filament::tabs>
        asdasd
        @foreach ($getChildSchema()->getComponents(withOriginalKeys: true) as $tabKey => $tab)
            <div class="p-4">
                {{ $tab->key($tabKey) }}
            </div>
        @endforeach
    </div>
@endif
