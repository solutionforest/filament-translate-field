<?php

namespace SolutionForest\FilamentTranslateField\Tests\Forms\Fixtures;

use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\Support\MessageBag as MessageBagContract;
use Illuminate\Support\MessageBag;
use Livewire\Component;

class Livewire extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public $data = [];

    public function getErrorBag(): MessageBag
    {
        if (property_exists($this, 'errorBag') && $this->errorBag instanceof MessageBagContract) {
            return $this->errorBag;
        }

        return new MessageBag;
    }
}
