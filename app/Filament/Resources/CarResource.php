<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Filament\Resources\CarResource\RelationManagers;
use App\Models\Car;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('brand')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., Toyota, Hyundai.'),
                Forms\Components\TextInput::make('model')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., Corolla, Elantra.'),
                Forms\Components\TextInput::make('year')
                    ->numeric()
                    ->placeholder('Car year'),
                Forms\Components\TextInput::make('type')
                    ->maxLength(255)
                    ->nullable()
                    ->placeholder('SUV, Sedan, etc.'),
                Forms\Components\TextInput::make('seats')
                    ->numeric()
                    ->default(null),
                Forms\Components\Select::make('transmission')
                    ->options([
                        'manual' => 'Manual',
                        'automatic' => 'Automatic',
                    ])
                    ->required(),

                Forms\Components\Select::make('fuel_type')
                    ->options([
                        'petrol'   => 'Petrol',
                        'diesel'   => 'Diesel',
                        'hybrid'   => 'Hybrid',
                        'electric' => 'Electric',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('price_per_day')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\Select::make('currency')
                    ->options([
                        'EGP' => 'EGP',
                        'USD' => 'USD',
                    ])
                    ->required()
                    ->default('USD'),
                Forms\Components\Toggle::make('availability')
                    ->label('Available for rent')
                    ->default(true),
                Forms\Components\FileUpload::make('images')
                    ->multiple()              // allows multiple
                    ->image()                 // restricts to images
                    ->directory('cars')       // where to store
                    ->maxFiles(5)            // optional: limit
                    ->reorderable()
                    ->nullable(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Active Listing')
                    ->default(true),

                Forms\Components\Toggle::make('is_on_offer')
                    ->label('On Offer')
                    ->live() // 👈 makes the field reactive in v3
                    ->afterStateUpdated(function (Set $set, $state) {
                        if (! $state) {
                            // If turned OFF, clear the offer value in the form state
                            $set('offer_price', null);
                        }
                    }),

                Forms\Components\TextInput::make('offer_price')
                    ->numeric()
                    ->minValue(0)
                    ->nullable()
                    // show & require only when on offer:
                    ->visible(fn (Get $get) => (bool) $get('is_on_offer'))
                    ->required(fn (Get $get) => (bool) $get('is_on_offer'))
                    // only dehydrate (save) when on offer; otherwise ignore form value:
                    ->dehydrated(fn (Get $get) => (bool) $get('is_on_offer')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('brand')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('model')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('year')
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->searchable(),

                Tables\Columns\ImageColumn::make('images')
                    ->getStateUsing(fn ($record) => $record->images[0] ?? null)
                    ->label('Preview')
                    ->square(),

                Tables\Columns\TextColumn::make('seats')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('transmission')
                    ->sortable(),

                Tables\Columns\TextColumn::make('fuel_type')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price_per_day')
                    ->numeric()
                    ->money(fn ($record) => $record->currency) // shows formatted price
                    ->sortable(),

                Tables\Columns\TextColumn::make('currency'),

                Tables\Columns\IconColumn::make('is_on_offer')
                    ->boolean()
                    ->label('On Offer'),

                Tables\Columns\TextColumn::make('final_price')
                    ->label('Final Price')
                    ->money(fn ($record) => $record->currency)
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->is_on_offer && $record->offer_price) {
                            return "💲 {$record->offer_price} (was {$record->price_per_day})";
                        }
                        return $record->price_per_day;
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('availability')
                    ->boolean()
                    ->label('Available'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('availability')
                    ->options([
                        true => 'Available',
                        false => 'Unavailable',
                    ]),
                Tables\Filters\SelectFilter::make('is_active')
                    ->options([
                        true => 'Active',
                        false => 'Inactive',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCar::route('/create'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
        ];
    }
}
