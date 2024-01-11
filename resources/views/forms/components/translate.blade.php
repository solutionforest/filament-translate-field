@php
    $locales = $getLocales() ?? [];
    $defaultLocale = $locales[0] ?? null;
    
    $isContained = $isContained();

    $visibleTabClasses = \Illuminate\Support\Arr::toCssClasses([
        'p-6' => $isContained,
        'mt-6' => ! $isContained,
    ]);

    $invisibleTabClasses = 'invisible h-0 overflow-y-hidden p-0';

@endphp

<div 
    x-data="{ activeTab: '{{ $defaultLocale }}' }"
    {{
        $attributes
            ->merge([
                'id' => $getId(),
                'wire:key' => "{$this->getId()}.{$getStatePath()}." . \SolutionForest\FilamentTranslateField\Forms\Component\Translate::class . '.container',
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)
            ->merge($getExtraAlpineAttributes(), escape: false)
            ->class([
                'fi-fo-translate flex flex-col',
                'fi-contained rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10' => $isContained,
            ])
    }}
>
    <x-filament::tabs :contained="$isContained">
        @foreach ($locales as $locale)
            <x-filament::tabs.item
                alpine-active="activeTab === '{{ $locale }}'"
                x-on:click="activeTab = '{{ $locale }}'"
            >
                {{ $getLocaleLabel($locale) }}
            </x-filament::tabs.item>
        @endforeach
    </x-filament::tabs>
    @foreach ($locales as $locale)
        @php
            $container = $getChildComponentContainer($locale);
        @endphp
        <div
            x-show="activeTab == '{{ $locale }}'"
            x-bind:class="{
                @js($visibleTabClasses): activeTab === @js($locale),
                @js($invisibleTabClasses): activeTab !== @js($locale),
            }"
            x-on:expand="tab = @js($id)"
            class="outline-none"
        >
            <div>
                {{ $container }}
            </div>
        </div>
    @endforeach
</div>