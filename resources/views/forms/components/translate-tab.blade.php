@php
    $id = $getId();
    $isContained = $getContainer()->getParentComponent()->isContained();

    $activeTabClasses = \Illuminate\Support\Arr::toCssClasses([
        'fi-active',
        'p-6' => $isContained,
        'mt-6' => ! $isContained,
    ]);

    $inactiveTabClasses = 'invisible h-0 overflow-hidden p-0';

    $actions = $getActions();
    $hasActions = filled($actions);

    $locale = $getLocale() ?? $id;
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

    @if ($hasActions)
        <div class="flex justify-end">
            @foreach ($actions as $action)
                @if (($action)(['locale' => $locale])->isVisible())
                    {{ ($action)(['locale' => $locale]) }}
                @endif
            @endforeach
        </div>
    @endif
    <div>
        {{ $getChildComponentContainer() }}
    </div>
</div>
