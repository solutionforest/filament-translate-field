@php
    $id = $getId();
    $isContained = $getContainer()->getParentComponent()->isContained();

    $activeTabClasses = 'fi-active translate-field-tab translate-field-tab-active';
    $inactiveTabClasses = 'translate-field-tab translate-field-tab-inactive';
@endphp

<div 
    x-bind:class="{
        @js($activeTabClasses): tab === @js($id),
        @js($inactiveTabClasses): tab !== @js($id),
    }"
    x-on:expand="tab = @js($id)"
    {{
        $attributes
            ->merge([
                'aria-labelledby' => $id,
                'id' => $id,
                'role' => 'tabpanel',
                'tabindex' => '0',
                'wire:key' => "{$this->getId()}.{$getStatePath()}." . \SolutionForest\FilamentTranslateField\Forms\Component\Translate::class . ".tabs.{$id}",
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)
            ->class(['fi-fo-tabs-tab outline-none'])
    }}
>
    {{ $getChildComponentContainer() }}
</div>
