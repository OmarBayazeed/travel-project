<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CruiseResource\Pages;
use App\Filament\Resources\CruiseResource\RelationManagers;
use App\Models\Cruise;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CruiseResource extends Resource
{
    protected static ?string $model = Cruise::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter cruise title'),

                Forms\Components\Textarea::make('description')
                    ->placeholder('Enter detailed description of the cruise')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('ship_name')
                    ->placeholder('e.g., Nile Queen'),

                Forms\Components\TextInput::make('stars')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(5)
                    ->placeholder('e.g., 5'),

                Forms\Components\TextInput::make('duration')
                    ->placeholder('e.g., 7 Nights / 8 Days'),

                Forms\Components\TextInput::make('route')
                    ->placeholder('e.g., Luxor → Aswan'),

                Forms\Components\TextInput::make('departure_day')
                    ->placeholder('e.g., Friday'),

                Forms\Components\TextInput::make('departure_city')
                    ->placeholder('e.g., Luxor'),

                Forms\Components\TextInput::make('arrival_city')
                    ->placeholder('e.g., Aswan'),

                Forms\Components\TextInput::make('cabin_types')
                    ->placeholder('e.g., Deluxe Cabin')
                    ->nullable(),

                Forms\Components\TextInput::make('price_per_person')
                    ->required()
                    ->numeric()
                    ->placeholder('e.g., 1500'),

                Forms\Components\Select::make('currency')
                    ->options([
                        'EGP' => 'EGP',
                        'USD' => 'USD',
                    ])
                    ->default('USD')
                    ->required()
                    ->placeholder('Select currency'),

                Forms\Components\TextInput::make('meals')
                    ->placeholder('e.g., Breakfast, Lunch, Dinner')
                    ->nullable(),

                Forms\Components\TextInput::make('facilities')
                    ->placeholder('e.g., Pool, Gym, Spa, WiFi')
                    ->nullable(),

                Forms\Components\FileUpload::make('images')
                    ->multiple()
                    ->image()
                    ->directory('cruises')
                    ->reorderable()
                    ->nullable()
                    ->placeholder('Upload cruise images'),

                Forms\Components\Toggle::make('is_active')
                    ->default(true)
                    ->label('Active Cruise'),

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
                Tables\Columns\ImageColumn::make('images')
                    ->getStateUsing(fn ($record) => $record->images[0] ?? null)
                    ->label('Preview')
                    ->square(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('ship_name')
                    ->label('Ship'),

                Tables\Columns\TextColumn::make('stars')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('duration'),

                Tables\Columns\TextColumn::make('route'),

                Tables\Columns\TextColumn::make('price_per_person')
                    ->money(fn ($record) => $record->currency)
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_on_offer')
                    ->boolean()
                    ->label('On Offer'),

                Tables\Columns\TextColumn::make('final_price')
                    ->label('Final Price')
                    ->money(fn ($record) => $record->currency)
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->is_on_offer && $record->offer_price) {
                            return "💲 {$record->offer_price} (was {$record->price_per_person})";
                        }
                        return $record->price_per_person;
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('currency')
                    ->options([
                        'EGP' => 'EGP',
                        'USD' => 'USD',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
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
            'index' => Pages\ListCruises::route('/'),
            'create' => Pages\CreateCruise::route('/create'),
            'edit' => Pages\EditCruise::route('/{record}/edit'),
        ];
    }
}
