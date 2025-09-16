<?php

namespace App\Filament\Resources\CruiseBookingResource\Pages;

use App\Filament\Resources\CruiseBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCruiseBooking extends EditRecord
{
    protected static string $resource = CruiseBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
