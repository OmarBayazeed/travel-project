<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageBookingResource\Pages;
use App\Filament\Resources\PackageBookingResource\RelationManagers;
use App\Models\PackageBooking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackageBookingResource extends Resource
{
    protected static ?string $model = PackageBooking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('package.title')->label('Package')->searchable(),
                Tables\Columns\TextColumn::make('client.name')->label('Client')->searchable(),
                Tables\Columns\TextColumn::make('full_name')->searchable(),
                Tables\Columns\TextColumn::make('phone')->searchable(),
                Tables\Columns\TextColumn::make('nationality')->searchable(),
                Tables\Columns\TextColumn::make('special_requests'),
                Tables\Columns\TextColumn::make('number_of_guests'),
                Tables\Columns\TextColumn::make('total_price')->money(fn ($record) => $record->currency),
                Tables\Columns\TextColumn::make('currency')->searchable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger'  => 'cancelled',
                    ])
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
            ])
            ->bulkActions([]); // disable bulk delete etc.
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackageBookings::route('/'),
            'create' => Pages\CreatePackageBooking::route('/create'),
            'edit' => Pages\EditPackageBooking::route('/{record}/edit'),
        ];
    }
}
