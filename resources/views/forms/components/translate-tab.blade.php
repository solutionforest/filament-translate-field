@php
    $id = $getId();
    $key = $getKey(isAbsolute: false);
    
    $tabs = $getContainer()->getParentComponent();
    $isContained = $tabs->isContained();
    $livewireProperty = $tabs->getLivewireProperty();

    $activeTabClasses = 'fi-active translate-field-tab translate-field-tab-active';
    $inactiveTabClasses = 'translate-field-tab translate-field-tab-inactive';

    $childSchema = $getChildSchema();
@endphp

@if (! empty($childSchema->getComponents()))
    @if (blank($livewireProperty))
        <div
            x-bind:class="{
                @js($activeTabClasses): tab === @js($key),
                @js($inactiveTabClasses): tab !== @js($key),
            }"
            x-on:expand="tab = @js($key)"
            {{
                $attributes
                    ->merge([
                        'aria-labelledby' => $id,
                        'id' => $id,
                        'role' => 'tabpanel',
                        'wire:key' => $getLivewireKey() . '.container',
                    ], escape: false)
                    ->merge($getExtraAttributes(), escape: false)
                    ->class(['fi-sc-tabs-tab'])
            }}
        >
            {{ $childSchema }}
        </div>
    @elseif (strval($this->{$livewireProperty}) === strval($getLocale()))
        <div
            {{
                $attributes
                    ->merge([
                        'aria-labelledby' => $id,
                        'id' => $id,
                        'role' => 'tabpanel',
                        'wire:key' => $getLivewireKey() . '.container',
                    ], escape: false)
                    ->merge($getExtraAttributes(), escape: false)
                    ->class(['fi-sc-tabs-tab fi-active'])
            }}
        >
            {{ $childSchema }}
        </div>
    @endif
@endif