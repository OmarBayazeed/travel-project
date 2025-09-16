<?php

namespace App\Filament\Resources\PackageBookingResource\Pages;

use App\Filament\Resources\PackageBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPackageBookings extends ListRecords
{
    protected static string $resource = PackageBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
