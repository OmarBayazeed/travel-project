<?php

namespace App\Filament\Resources\CruiseBookingResource\Pages;

use App\Filament\Resources\CruiseBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCruiseBookings extends ListRecords
{
    protected static string $resource = CruiseBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
