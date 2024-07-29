<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('rush_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('progress')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('rush_value')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('booster1_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('booster2_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('booster3_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('booster4_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('paid')
                    ->required(),
                Forms\Components\DatePicker::make('payment_date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rush_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('progress')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rush_value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booster1_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booster2_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booster3_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booster4_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('paid')
                    ->boolean(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
