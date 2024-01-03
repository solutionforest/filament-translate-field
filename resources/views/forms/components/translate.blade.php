@php
    $locales = $getLocales() ?? [];
    $defaultLocale = $locales[0] ?? null;
@endphp

<div x-data="{ activeTab: '{{ $defaultLocale }}' }">
    <x-filament::tabs>
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
        <div class="pt-2" x-show="activeTab == '{{ $locale }}'">
            {{ $container }}
        </div>
    @endforeach
</div>