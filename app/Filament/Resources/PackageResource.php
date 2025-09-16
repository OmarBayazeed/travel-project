<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Filament\Resources\PackageResource\RelationManagers;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->placeholder('Name of the package (e.g., "7-Day Egypt Adventure").'),

                Forms\Components\Textarea::make('description')
                    ->placeholder('Details of the package.'),

                Forms\Components\TextInput::make('duration')
                    ->placeholder('e.g. 5 days / 4 nights'),

                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required()
                    ->placeholder('Enter price'),

                Forms\Components\Select::make('currency')
                    ->options([
                        'EGP' => 'EGP',
                        'USD' => 'USD',
                    ])
                    ->default('USD')
                    ->placeholder('Select currency'),

                Forms\Components\TextInput::make('accommodation')
                    ->placeholder('Hotel info (name, stars, room type).')
                    ->nullable(),

                Forms\Components\DatePicker::make('start_date')
                    ->placeholder('Select start date'),

                Forms\Components\DatePicker::make('end_date')
                    ->placeholder('Select end date'),

                Forms\Components\TextInput::make('transport')
                    ->placeholder('Flights, buses, transfers.')
                    ->nullable(),

                Forms\Components\TextInput::make('meals')
                    ->placeholder('Included meals (breakfast/lunch/dinner).')
                    ->nullable(),

                Forms\Components\FileUpload::make('images')
                    ->multiple()
                    ->image()
                    ->directory('packages')
                    ->reorderable()
                    ->placeholder('Upload package images'),

                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
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
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('duration'),
                Tables\Columns\TextColumn::make('price')->money(fn ($record) => $record->currency),
                Tables\Columns\TextColumn::make('currency')->searchable(),
                Tables\Columns\IconColumn::make('is_on_offer')
                    ->boolean()
                    ->label('On Offer'),

                Tables\Columns\TextColumn::make('final_price')
                    ->label('Final Price')
                    ->money(fn ($record) => $record->currency)
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->is_on_offer && $record->offer_price) {
                            return "💲 {$record->offer_price} (was {$record->price})";
                        }
                        return $record->price;
                    }),
                Tables\Columns\TextColumn::make('start_date')->date(),
                Tables\Columns\TextColumn::make('end_date')->date(),
                Tables\Columns\IconColumn::make('is_active')->boolean()->searchable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }
}
