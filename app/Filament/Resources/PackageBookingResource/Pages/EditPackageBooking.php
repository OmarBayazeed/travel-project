<?php

namespace App\Filament\Resources\PackageBookingResource\Pages;

use App\Filament\Resources\PackageBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPackageBooking extends EditRecord
{
    protected static string $resource = PackageBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
