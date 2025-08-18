<?php

namespace SolutionForest\FilamentTranslateField\Tests\Stubs\Filament\Pages;

use Filament\Resources\Pages\EditRecord as BaseEditRecord;
use Illuminate\Contracts\Support\MessageBag as MessageBagContract;
use Illuminate\Support\MessageBag;

abstract class TestingEditRecord extends BaseEditRecord
{
    protected ?MessageBagContract $errorBag = null;

    public function getErrorBag(): MessageBag
    {
        if ($this->errorBag instanceof MessageBagContract) {
            return $this->errorBag;
        }

        return new MessageBag;
    }
}
