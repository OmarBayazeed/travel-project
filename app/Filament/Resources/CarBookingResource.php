<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarBookingResource\Pages;
use App\Filament\Resources\CarBookingResource\RelationManagers;
use App\Models\CarBooking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarBookingResource extends Resource
{
    protected static ?string $model = CarBooking::class;

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
                Forms\Components\Select::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                        'completed' => 'Completed',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('car.brand')->label('Car')->searchable(),
                Tables\Columns\TextColumn::make('client.name')->label('Client')->searchable(),
                Tables\Columns\TextColumn::make('pickup_date')->date(),
                Tables\Columns\TextColumn::make('dropoff_date')->date(),
                Tables\Columns\TextColumn::make('pickup_location'),
                Tables\Columns\TextColumn::make('dropoff_location'),
                Tables\Columns\TextColumn::make('total_days'),
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
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'danger'  => 'cancelled',
                        'gray'    => 'completed',
                    ]),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->label('Update Status')
                    ->icon('heroicon-o-check')
                    ->modalHeading('Update Booking Status'),
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
            'index' => Pages\ListCarBookings::route('/'),
            'edit' => Pages\EditCarBooking::route('/{record}/edit'),
        ];
    }
}
